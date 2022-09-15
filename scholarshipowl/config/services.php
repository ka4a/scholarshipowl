<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

    'mandrill' => [
		'secret' => env('MANDRILL_SECRET'),
	],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

    'recurly' => [
        'subdomain'   => env('RECURLY_SUBDOMAIN'),
        'private_key' => env('RECURLY_PRIVATE_KEY'),
        'public_key'  => env('RECURLY_PUBLIC_KEY'),
    ],

    'braintree' => [
        'merchant_id'   => env('BRAINTREE_MERCHANT_ID'),
        'public_key'    => env('BRAINTREE_PUBLIC_KEY'),
        'private_key'   => env('BRAINTREE_PRIVATE_KEY'),
    ],

    'stripe' => [
        'public_key'        => env('STRIPE_PUBLIC_KEY'),
        'key'               => env('STRIPE_SECRET'),
        'endpoint_secret'   => env('STRIPE_ENDPOINT_SECRET')
    ],

    'application' => [
        'sunrise' => [
            'api_base_url' => env('SUNRISE_API_BASE_URL'),
            'oauth2_url' => env('SUNRISE_OAUTH2_URL'),
            'oauth2_client_id' => env('SUNRISE_OAUTH2_CLIENT_ID'),
            'oauth2_client_secret' => env('SUNRISE_OAUTH2_CLIENT_SECRET')
        ]
    ],

    'mautic' => [
        'api_key' => env('MAUTIC_API_KEY'),
        'white_ips' => env('MAUTIC_WHITE_IPS'),
    ],

    'digest' => [
        'api_key' => env('DIGEST_API_KEY'),
    ],

    'zipService' => [
        'api_key' => env('GOOGLE_API_KEY'),
    ],

    // this config is needed along with config/sentry.php
    'sentry' => [
        'phpReport' => env('SENTRY_PHP_REPORT', false),
        'jsReport' => env('SENTRY_JS_REPORT', false),
        'crashReport' => env('SENTRY_CRASH_REPORT', false),
        'dnsPublicJs' => env('SENTRY_DSN_PUBLIC_JS', ''),
        'server' => env('SENTRY_ENV', 'dev'),
    ],

    'mailbox' => [
        'api_key' => env('MAILBOX_API_KEY'),
        'api_base_url' => env('MAILBOX_API_BASE_URL'),
    ]
];
