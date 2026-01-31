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



 'razorpay' => [
        'key_id' => env('RAZORPAY_KEY_ID'),
        'key_secret' => env('RAZORPAY_KEY_SECRET'),
    ],

    'shiprocket' => [
        'base_url' => env('SHIPROCKET_BASE_URL', 'https://apiv2.shiprocket.in/v1/external/'),
        'email' => env('SHIPROCKET_EMAIL'),
        'password' => env('SHIPROCKET_PASSWORD'),
        'pickup_pincode' => env('SHIPROCKET_PICKUP_PINCODE'),
        'pickup_address' => env('SHIPROCKET_PICKUP_ADDRESS'),
        'pickup_city' => env('SHIPROCKET_PICKUP_CITY'),
        'pickup_state' => env('SHIPROCKET_PICKUP_STATE'),
        'pickup_country' => env('SHIPROCKET_PICKUP_COUNTRY'),
        'pickup_phone' => env('SHIPROCKET_PICKUP_PHONE'),
        'pickup_location' => env('SHIPROCKET_PICKUP_LOCATION', 'Primary'),
    ],

    'shipping' => [
        'free_shipping_min_amount' => env('FREE_SHIPPING_MIN_AMOUNT', 999),
        'standard_cost' => env('STANDARD_SHIPPING_COST', 50),
        'express_cost' => env('EXPRESS_SHIPPING_COST', 199),
        'overnight_cost' => env('OVERNIGHT_SHIPPING_COST', 499),
    ],

];
