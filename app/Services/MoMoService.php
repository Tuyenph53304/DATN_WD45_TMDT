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

        // Endpoint
        $this->endpoint = config('payment.momo.endpoint', 'https://test-payment.momo.vn/v2/gateway/api/create');
    }

    /**
     * Tạo URL thanh toán MoMo
     */
    public function createPaymentUrl($orderId, $amount, $orderDescription, $orderInfo = '')
    {
        $requestId = time() . '';
        $orderId = $orderId . '_' . time();
        $extraData = '';

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
                   "&requestType=captureWallet";

        // Tạo signature
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        // Tạo request data
        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => config('constants.site.name', 'BeeFast'),
            'storeId' => 'BeeFast Store',
            'requestId' => $requestId,
            'amount' => (int)$amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo ?: $orderDescription,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => 'captureWallet',
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
            $response = Http::timeout(30)->post($this->endpoint, $data);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['payUrl'])) {
                    return [
                        'success' => true,
                        'url' => $result['payUrl'],
                        'order_id' => $orderId,
                        'request_id' => $requestId,
                    ];
                } else {
                    Log::error('MoMo Payment Error', [
                        'response' => $result,
                    ]);
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Không thể tạo URL thanh toán',
                    ];
                }
            } else {
                Log::error('MoMo API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối với MoMo: ' . $response->status(),
                ];
            }
        } catch (\Exception $e) {
            Log::error('MoMo Exception', [
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
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

