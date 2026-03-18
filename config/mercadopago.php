<?php

return [
    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
    'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
    'base_url' => env('MERCADOPAGO_BASE_URL', 'https://api.mercadopago.com'),

    // Optional shared secret via query param (?secret=...) for webhook validation
    'webhook_secret' => env('MERCADOPAGO_WEBHOOK_SECRET'),

    'success_url' => env('MERCADOPAGO_SUCCESS_URL'),
    'failure_url' => env('MERCADOPAGO_FAILURE_URL'),
    'pending_url' => env('MERCADOPAGO_PENDING_URL'),
];
