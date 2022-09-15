<?php

use Monolog\Handler\StreamHandler;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */
    'default' => env('LOG_CHANNEL', 'stack'),
    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            //'channels' => env('APP_ENV', 'production') === 'production' ? ['stream'] : ['daily'],
            'channels' => ['stream', 'daily'],
        ],
        'daily' => [
            'driver' => 'custom',
            'via' => \App\Logging\DefaultLogger::class,
            'path' => storage_path('logs/laravel.{severity}.log'),
            'level' => 'debug',
            'permission' => 0776,
        ],
        'stream' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => \App\Logging\StdoutFormatter::class,
            'with' => [
                'stream' => 'php://stdout',
            ],
        ],

//        'single' => [
//            'driver' => 'single',
//            'path' => storage_path('logs/laravel.log'),
//            'level' => 'debug',
//        ],
//        'slack' => [
//            'driver' => 'slack',
//            'url' => env('LOG_SLACK_WEBHOOK_URL'),
//            'username' => 'Laravel Log',
//            'emoji' => ':boom:',
//            'level' => 'critical',
//        ],
//        'stderr' => [
//            'driver' => 'monolog',
//            'handler' => StreamHandler::class,
//            'with' => [
//                'stream' => 'php://stderr',
//            ],
//        ],
//        'syslog' => [
//            'driver' => 'syslog',
//            'level' => 'debug',
//        ],
//        'errorlog' => [
//            'driver' => 'errorlog',
//            'level' => 'debug',
//        ],
    ],
];