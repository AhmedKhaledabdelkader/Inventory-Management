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

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],



    'external_transfer_api' => [
    'auth_url' => env('EXTERNAL_AUTH_URL'),
    'base_url' => env('EXTERNAL_API_BASE_URL'),
    'client_id' => env('EXTERNAL_API_CLIENT_ID'),
    'client_secret' => env('EXTERNAL_API_CLIENT_SECRET'),
    'grant_type' => env('EXTERNAL_API_GRANT_TYPE', 'client_credentials'),
    'scope' => env('EXTERNAL_API_SCOPE'),
    'username' => env('EXTERNAL_API_USERNAME'),
    'password' => env('EXTERNAL_API_PASSWORD'),
],

];
