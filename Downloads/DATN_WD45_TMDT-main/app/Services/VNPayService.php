<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class VNPayService
{
    protected $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    protected $vnp_Returnurl;
    protected $vnp_TmnCode;
    protected $vnp_HashSecret;
    protected $vnp_ApiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

    public function __construct()
    {
        // Cấu hình VNPay Sandbox (Demo)
        // Lưu ý: Cần đăng ký tài khoản VNPay để lấy TMN Code và Hash Secret thật
        // Hoặc sử dụng thông tin demo từ tài liệu VNPay
        $this->vnp_TmnCode = config('payment.vnpay.tmn_code', '2QXUI4J4'); // Mã merchant demo
        $this->vnp_HashSecret = config('payment.vnpay.hash_secret', 'RAOCTZKRVBMGIESULXWYDFLAPNVHQKBI'); // Secret key demo

        // Return URL phải là full URL và không được là localhost
        $returnUrl = config('payment.vnpay.return_url');
        if ($returnUrl && !filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            $this->vnp_Returnurl = url($returnUrl);
        } else {
            $this->vnp_Returnurl = $returnUrl ?: url(route('payment.callback'));
        }

        // Cảnh báo nếu dùng localhost (VNPay có thể không chấp nhận)
        if (strpos($this->vnp_Returnurl, '127.0.0.1') !== false || strpos($this->vnp_Returnurl, 'localhost') !== false) {
            Log::warning('VNPay Return URL is localhost - VNPay may not accept this. Consider using ngrok or a public domain.');
        }

        $this->vnp_Url = config('payment.vnpay.url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->vnp_ApiUrl = config('payment.vnpay.api_url', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction');
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($orderId, $amount, $orderDescription, $orderType = 'other', $locale = 'vn')
    {
        $vnp_TxnRef = $orderId . '_' . time(); // Mã tham chiếu giao dịch
        $vnp_Amount = (int)($amount * 100); // VNPay yêu cầu số tiền nhân 100, phải là số nguyên
        $vnp_OrderInfo = $orderDescription;
        $vnp_OrderType = $orderType;
        $vnp_Locale = $locale;
        $vnp_IpAddr = request()->ip() ?: '127.0.0.1'; // Fallback nếu không lấy được IP

        // Đảm bảo ReturnUrl là full URL
        $vnp_ReturnUrl = $this->vnp_Returnurl;
        if (!filter_var($vnp_ReturnUrl, FILTER_VALIDATE_URL)) {
            $vnp_ReturnUrl = url($vnp_ReturnUrl);
        }

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        // KHÔNG loại bỏ các tham số rỗng - VNPay yêu cầu tất cả tham số
        // Sắp xếp theo key (theo thứ tự bảng chữ cái)
        ksort($inputData);

        // Tạo query string và hash data
        $query = "";
        $hashdata = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            // URL encode - KHÔNG thay %20 bằng + (giữ nguyên)
            $encodedKey = urlencode($key);
            $encodedValue = urlencode($value);

            if ($i == 1) {
                $hashdata .= '&' . $encodedKey . "=" . $encodedValue;
            } else {
                $hashdata .= $encodedKey . "=" . $encodedValue;
                $i = 1;
            }
            $query .= $encodedKey . "=" . $encodedValue . '&';
        }

        // Tạo secure hash bằng HMAC SHA512
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);

        // Log để debug (chỉ trong môi trường development)
        if (config('app.debug')) {
            Log::info('VNPay Payment URL Data', [
                'input_data' => $inputData,
                'hashdata' => $hashdata,
                'hash_secret_length' => strlen($this->vnp_HashSecret),
                'hash_secret_preview' => substr($this->vnp_HashSecret, 0, 10) . '...',
                'secure_hash' => $vnpSecureHash,
                'return_url' => $vnp_ReturnUrl,
                'tmn_code' => $this->vnp_TmnCode,
            ]);
        }

        // Tạo URL cuối cùng
        $vnp_Url = $this->vnp_Url . "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        return [
            'url' => $vnp_Url,
            'txn_ref' => $vnp_TxnRef
        ];
    }

    /**
     * Xác thực callback từ VNPay
     */
    public function verifyPayment($inputData)
    {
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);

        // KHÔNG loại bỏ các tham số rỗng - VNPay yêu cầu tất cả tham số
        // Sắp xếp theo key
        ksort($inputData);

        // Tạo hash data - giống như khi tạo payment URL
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            $encodedKey = urlencode($key);
            $encodedValue = urlencode($value);

            if ($i == 1) {
                $hashData .= '&' . $encodedKey . "=" . $encodedValue;
            } else {
                $hashData .= $encodedKey . "=" . $encodedValue;
                $i = 1;
            }
        }

        // Tạo secure hash
        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        // So sánh hash (case-insensitive)
        if (strtoupper($secureHash) == strtoupper($vnp_SecureHash)) {
            return [
                'success' => true,
                'response_code' => $inputData['vnp_ResponseCode'] ?? '',
                'transaction_no' => $inputData['vnp_TransactionNo'] ?? '',
                'txn_ref' => $inputData['vnp_TxnRef'] ?? '',
                'amount' => $inputData['vnp_Amount'] ?? 0,
                'order_info' => $inputData['vnp_OrderInfo'] ?? '',
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid signature. Expected: ' . $secureHash . ', Received: ' . $vnp_SecureHash
        ];
    }

    /**
     * Kiểm tra trạng thái giao dịch
     */
    public function checkTransactionStatus($txnRef)
    {
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_Command" => "querydr",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_TxnRef" => $txnRef,
            "vnp_CreateDate" => date('YmdHis'),
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->vnp_ApiUrl . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Gọi API kiểm tra (có thể dùng Guzzle)
        // Ở đây chỉ return demo
        return [
            'success' => true,
            'status' => 'success'
        ];
    }
}

