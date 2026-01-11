<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MoMoService
{
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;
    protected $returnUrl;
    protected $notifyUrl;
    protected $endpoint;

    public function __construct()
    {
        // MoMo Sandbox credentials (Demo)
        $this->partnerCode = config('payment.momo.partner_code', 'MOMOBKUN20180529');
        $this->accessKey = config('payment.momo.access_key', 'klm05TvNBzhg7h7j');
        $this->secretKey = config('payment.momo.secret_key', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');

        // Return URL
        $returnUrl = config('payment.momo.return_url');
        if ($returnUrl && !filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            $this->returnUrl = url($returnUrl);
        } else {
            $this->returnUrl = $returnUrl ?: url(route('payment.callback'));
        }

        // Notify URL (IPN)
        $notifyUrl = config('payment.momo.notify_url');
        if ($notifyUrl && !filter_var($notifyUrl, FILTER_VALIDATE_URL)) {
            $this->notifyUrl = url($notifyUrl);
        } else {
            $this->notifyUrl = $notifyUrl ?: url(route('payment.ipn'));
        }

        // Endpoint - Cập nhật endpoint mới nhất của MoMo
        // Sandbox: https://test-payment.momo.vn/v2/gateway/api/create
        // Production: https://payment.momo.vn/v2/gateway/api/create
        $this->endpoint = config('payment.momo.endpoint', 'https://test-payment.momo.vn/v2/gateway/api/create');
    }

    /**
     * Tạo URL thanh toán MoMo
     * @param string $orderId ID đơn hàng
     * @param float $amount Số tiền
     * @param string $orderDescription Mô tả đơn hàng
     * @param string $orderInfo Thông tin đơn hàng
     * @param string $paymentType Loại thanh toán: 'wallet' (ví MoMo), 'card' (thẻ), 'atm' (thẻ ATM)
     */
    public function createPaymentUrl($orderId, $amount, $orderDescription, $orderInfo = '', $paymentType = 'wallet')
    {
        $requestId = time() . '';
        $orderId = $orderId . '_' . time();
        $extraData = '';

        // Xác định requestType dựa trên paymentType
        // Lưu ý: Với MoMo Demo, payWithCC và payWithATM sẽ hiển thị form nhập thẻ
        // Thứ tự trong rawHash phải đúng: requestType phải ở cuối cùng
        $requestTypeMap = [
            'wallet' => 'captureWallet',      // Thanh toán qua ví MoMo (hiển thị QR)
            'card' => 'payWithCC',            // Thanh toán bằng thẻ tín dụng (hiển thị form thẻ)
            'atm' => 'payWithATM',           // Thanh toán bằng thẻ ATM (hiển thị form thẻ)
        ];
        
        $requestType = $requestTypeMap[$paymentType] ?? 'captureWallet';
        
        // Log để debug
        if (config('app.debug')) {
            Log::info('MoMo Payment Type Selected', [
                'payment_type' => $paymentType,
                'request_type' => $requestType,
            ]);
        }

        // Tạo raw signature
        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . $amount .
                   "&extraData=" . $extraData .
                   "&ipnUrl=" . $this->notifyUrl .
                   "&orderId=" . $orderId .
                   "&orderInfo=" . $orderInfo .
                   "&partnerCode=" . $this->partnerCode .
                   "&redirectUrl=" . $this->returnUrl .
                   "&requestId=" . $requestId .
                   "&requestType=" . $requestType;

        // Tạo signature
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        // Tạo request data
        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => config('constants.site.name', 'LaptopStore'),
            'storeId' => config('constants.site.name', 'LaptopStore') . ' Store',
            'requestId' => $requestId,
            'amount' => (int)$amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo ?: $orderDescription,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        // Log để debug
        if (config('app.debug')) {
            Log::info('MoMo Payment Request', [
                'data' => $data,
                'raw_hash' => $rawHash,
                'signature' => $signature,
            ]);
        }

        // Gọi API MoMo
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->endpoint, $data);

            if ($response->successful()) {
                $result = $response->json();

                // Log response để debug
                if (config('app.debug')) {
                    Log::info('MoMo Payment Response', [
                        'response' => $result,
                    ]);
                }

                if (isset($result['payUrl'])) {
                    // Log để kiểm tra URL và requestType
                    if (config('app.debug')) {
                        Log::info('MoMo Payment URL Created', [
                            'pay_url' => $result['payUrl'],
                            'request_type' => $requestType,
                            'payment_type' => $paymentType,
                            'order_id' => $orderId,
                        ]);
                    }
                    
                    return [
                        'success' => true,
                        'url' => $result['payUrl'],
                        'order_id' => $orderId,
                        'request_id' => $requestId,
                        'payment_type' => $paymentType,
                        'request_type' => $requestType,
                    ];
                } else {
                    // Kiểm tra các mã lỗi phổ biến
                    $errorCode = $result['resultCode'] ?? null;
                    $errorMessage = $result['message'] ?? 'Không thể tạo URL thanh toán';
                    
                    // Mã lỗi phổ biến
                    $errorMessages = [
                        '1001' => 'Giao dịch bị từ chối. Vui lòng thử lại hoặc liên hệ hỗ trợ.',
                        '1002' => 'Giao dịch đang được xử lý. Vui lòng đợi và kiểm tra lại sau.',
                        '1003' => 'Giao dịch bị hủy. Vui lòng thử lại.',
                        '1004' => 'Giao dịch không thành công. Vui lòng kiểm tra lại thông tin thanh toán.',
                        '1005' => 'Giao dịch hết hạn. QR code hoặc thông tin demo đã hết hạn. Vui lòng đăng ký tài khoản MoMo Merchant tại https://developers.momo.vn/ hoặc thử lại sau.',
                        '1006' => 'Giao dịch thất bại. Vui lòng thử lại hoặc chọn phương thức thanh toán khác.',
                        '1007' => 'Thông tin không hợp lệ. Vui lòng kiểm tra lại cấu hình MoMo trong file .env.',
                    ];

                    if ($errorCode && isset($errorMessages[$errorCode])) {
                        $errorMessage = $errorMessages[$errorCode];
                        
                        // Thêm thông tin chi tiết cho mã lỗi 1005
                        if ($errorCode == '1005') {
                            $errorMessage .= ' (Mã lỗi: 1005)';
                        }
                    }

                    Log::error('MoMo Payment Error', [
                        'response' => $result,
                        'error_code' => $errorCode,
                        'error_message' => $errorMessage,
                    ]);
                    
                    return [
                        'success' => false,
                        'message' => $errorMessage,
                        'error_code' => $errorCode,
                        'raw_response' => $result, // Thêm raw response để debug
                    ];
                }
            } else {
                $errorBody = $response->body();
                Log::error('MoMo API Error', [
                    'status' => $response->status(),
                    'body' => $errorBody,
                    'endpoint' => $this->endpoint,
                ]);
                
                $errorMessage = 'Lỗi kết nối với MoMo';
                if ($response->status() == 404) {
                    $errorMessage = 'Endpoint MoMo không tồn tại. Vui lòng kiểm tra lại cấu hình endpoint.';
                } elseif ($response->status() == 401) {
                    $errorMessage = 'Xác thực thất bại. Vui lòng kiểm tra lại Partner Code, Access Key và Secret Key.';
                }
                
                return [
                    'success' => false,
                    'message' => $errorMessage . ' (HTTP ' . $response->status() . ')',
                ];
            }
        } catch (\Exception $e) {
            Log::error('MoMo Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Lỗi kết nối: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Xác thực callback từ MoMo
     */
    public function verifyPayment($data)
    {
        // Tạo raw signature từ callback data
        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . ($data['amount'] ?? 0) .
                   "&extraData=" . ($data['extraData'] ?? '') .
                   "&message=" . ($data['message'] ?? '') .
                   "&orderId=" . ($data['orderId'] ?? '') .
                   "&orderInfo=" . ($data['orderInfo'] ?? '') .
                   "&orderType=" . ($data['orderType'] ?? '') .
                   "&partnerCode=" . ($data['partnerCode'] ?? '') .
                   "&payType=" . ($data['payType'] ?? '') .
                   "&requestId=" . ($data['requestId'] ?? '') .
                   "&responseTime=" . ($data['responseTime'] ?? '') .
                   "&resultCode=" . ($data['resultCode'] ?? '') .
                   "&transId=" . ($data['transId'] ?? '');

        // Tạo signature
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        // So sánh signature
        if ($signature == ($data['signature'] ?? '')) {
            return [
                'success' => true,
                'result_code' => $data['resultCode'] ?? '',
                'transaction_id' => $data['transId'] ?? '',
                'order_id' => $data['orderId'] ?? '',
                'amount' => $data['amount'] ?? 0,
                'message' => $data['message'] ?? '',
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid signature',
        ];
    }
}

