<?php

return [
    'ios:applyme'     => [
        'environment' => env('APP_DEBUG') ? 'development' : 'production',
        'certificate' => env('APP_DEBUG') ?
            base_path() . '/resources/certificates/' . env('PUSH_DEV_CERT', 'applyme_push_dev.pem') :
            base_path() . '/resources/certificates/' . env('PUSH_PROD_CERT', 'applyme_push_prod.pem'),
        'passPhrase'  => env('PUSH_NOTIFICATION_PASS_PHRASE','xhHcowVIjCNcPCCtCcM7Uk2Co'),
        'service'     => 'apns'
    ],
];