<?php

return [
    'store_id' => env('STORE_ID', 'store-id'),
    'store_password' => env('STORE_PASSWORD', 'store-password'),
    'is_production' => env('IS_PRODUCTION', false),
    'api_domain' => [
        'sandbox' => 'https://sandbox.sslcommerz.com',
        'production' => 'https://securepay.sslcommerz.com',
    ],
    'api_url' => [
        'init_payment' => '/gwprocess/v4/api.php',
        'transaction_status' => '/validator/api/merchantTransIDvalidationAPI.php',
        'order_validate' => '/validator/api/validationserverAPI.php',
        'refund_payment' => '/validator/api/merchantTransIDvalidationAPI.php',
        'refund_status' => '/validator/api/merchantTransIDvalidationAPI.php',
    ]
];
