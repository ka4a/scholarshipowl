<?php return [
    'web' => [
        'app_id'    => env('ONESIGNAL_APP_ID'),
        'subdomain' => env('ONESIGNAL_SUBDOMAIN'),
        'api_key'   => env('ONESIGNAL_API_KEY'),
    ],
    'mobile' => [
        'app_id'    => env('ONESIGNAL_APPLYME_APP_ID'),
        'api_key'   => env('ONESIGNAL_APPLYME_API_KEY'),
    ]
];
