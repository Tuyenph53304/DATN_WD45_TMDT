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
        'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'), // Partner code demo
        'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'), // Access key demo
        'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'), // Secret key demo
        'return_url' => env('MOMO_RETURN_URL', '/payment/callback'),
        'notify_url' => env('MOMO_NOTIFY_URL', '/payment/ipn'),
        'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
    ],
];

