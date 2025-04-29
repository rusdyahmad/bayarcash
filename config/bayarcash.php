<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bayarcash API Credentials
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Bayarcash API credentials. These are used when
    | authenticating with the Bayarcash payment gateway API.
    |
    */

    'pat' => env('BAYARCASH_PAT'),
    'api_secret_key' => env('BAYARCASH_API_SECRET_KEY'),
    'portal_key' => env('BAYARCASH_PORTAL_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Bayarcash API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Bayarcash API settings. The sandbox option
    | allows you to test your integration without making real transactions.
    |
    */

    'sandbox' => env('BAYARCASH_SANDBOX', false),
    'api_version' => env('BAYARCASH_API_VERSION', 'v3'),
    'debug' => env('BAYARCASH_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Bayarcash Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Bayarcash payment settings. The default_channel
    | option allows you to set a default payment channel for all transactions.
    |
    */

    'default_channel' => env('BAYARCASH_DEFAULT_CHANNEL', 1), // FPX by default
    'return_url' => env('BAYARCASH_RETURN_URL'),
    'callback_url' => env('BAYARCASH_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Bayarcash Channel IDs
    |--------------------------------------------------------------------------
    |
    | Here are the available payment channel IDs for reference. You can use
    | these constants in your code to specify the payment channel.
    |
    */

    'channels' => [
        'fpx' => 1,
        'fpx_direct_debit' => 3,
        'fpx_line_of_credit' => 4,
        'duitnow_dobw' => 5,
        'duitnow_qr' => 6,
        'spaylater' => 7,
        'boost_payflex' => 8,
        'qrisob' => 9,
        'qriswallet' => 10,
        'nets' => 11,
    ],
];
