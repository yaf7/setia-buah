<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
        'notification_url' => env('MIDTRANS_NOTIFICATION_URL'),
    ],

    'biteship' => [
        'api_key' => env('BITESHIP_API_KEY'),
        'base_url' => env('BITESHIP_BASE_URL', 'https://api-sandbox.biteship.com'),
        'origin_contact_name' => env('BITESHIP_ORIGIN_CONTACT_NAME', 'Setia Buah'),
        'origin_contact_phone' => env('BITESHIP_ORIGIN_CONTACT_PHONE', '0800000000'),
        'origin_address' => env('BITESHIP_ORIGIN_ADDRESS', 'Alamat gudang Setia Buah'),
        'origin_postal_code' => env('BITESHIP_ORIGIN_POSTAL_CODE', '40115'),
        'origin_province' => env('BITESHIP_ORIGIN_PROVINCE', 'Jawa Barat'),
        'origin_city' => env('BITESHIP_ORIGIN_CITY', 'Bandung'),
    ],

];
