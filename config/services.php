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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'sftp' => [
        'host' => env('HS_SFTP_EMAIL'),
        'port' => env('HS_SFTP_PORT'),
        'username' => env('HS_USERNAME'),
        'password' => env('HS_PASSWORD'),
    ],
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'staples' => [
        'production' => env('STAPLE_PRODUCTION'),
    ],
    'fedex' => [
        'account_number' => env('FEDEX_ACCOUNT_NUMBER'),
        'client_id' => env('FEDEX_CLIENT_ID'),
        'client_secret' => env('FEDEX_CLIENT_SECRET')
    ],
    'cah' => [
        'host' => env('CAH_HOST'),
        'port' => env('CAH_PORT'),
        'username' => env('CAH_USERNAME'),
        'password' => env('CAH_PASSWORD'),
        'binary_address' => env('CAH_BINARY_ADDRESS'),
    ],
    'ups' => [
        'access_key' => env('UPS_ACCESS_KEY'),
        'username' => env('UPS_USERNAME'),
        'password' => env('UPS_PASSWORD'),
        'account_number' => env('UPS_ACCOUNT_NUMBER'),
        'sandbox' => env('UPS_SANDBOX', true),
    ],


];
