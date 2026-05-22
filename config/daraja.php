<?php

return [
    'env'                 => env('MPESA_ENV', 'sandbox'),
    'consumer_key'        => env('MPESA_CONSUMER_KEY'),
    'consumer_secret'     => env('MPESA_CONSUMER_SECRET'),
    'b2c_consumer_key'    => env('MPESA_B2C_CONSUMER_KEY'),
    'b2c_consumer_secret' => env('MPESA_B2C_CONSUMER_SECRET'),
    'shortcode'           => env('MPESA_SHORTCODE', '174379'),
    'passkey'             => env('MPESA_PASSKEY'),
    'b2c_shortcode'       => env('MPESA_B2C_SHORTCODE', '600000'),
    'b2c_initiator_name'  => env('MPESA_B2C_INITIATOR_NAME', 'testapi'),
    'b2c_initiator_password' => env('MPESA_B2C_INITIATOR_PASSWORD'),
    'callback_url'        => env('MPESA_CALLBACK_URL'),
    'b2c_result_url'      => env('MPESA_B2C_RESULT_URL'),
    'b2c_timeout_url'     => env('MPESA_B2C_TIMEOUT_URL'),
    'b2b_result_url'      => env('MPESA_B2B_RESULT_URL', env('MPESA_B2C_RESULT_URL')),
    'b2b_timeout_url'     => env('MPESA_B2B_TIMEOUT_URL', env('MPESA_B2C_TIMEOUT_URL')),
];
