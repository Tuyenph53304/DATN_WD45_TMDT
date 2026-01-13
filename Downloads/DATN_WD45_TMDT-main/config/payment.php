<?php

return [
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', '2QXUI4J4'), // Mã merchant demo
        'hash_secret' => env('VNPAY_HASH_SECRET', 'RAOCTZKRVBMGIESULXWYDFLAPNVHQKBI'), // Secret key demo
        'return_url' => env('VNPAY_RETURN_URL', '/payment/callback'),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'api_url' => env('VNPAY_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
    ],
    'momo' => [
        'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'), // Partner code - lấy từ MoMo Developer Portal
        'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'), // Access key - lấy từ MoMo Developer Portal
        'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'), // Secret key - lấy từ MoMo Developer Portal
        'return_url' => env('MOMO_RETURN_URL', '/payment/callback'), // URL callback sau khi thanh toán
        'notify_url' => env('MOMO_NOTIFY_URL', '/payment/ipn'), // URL IPN nhận thông báo từ MoMo
        // Endpoint: Sandbox (test) hoặc Production
        'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
        // Số điện thoại tài khoản MoMo (để tham khảo)
        'account_phone' => env('MOMO_ACCOUNT_PHONE', '0977675028'),
    ],
];

