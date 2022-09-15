<?php

$keyFile = env('GOOGLE_CLOUD_KEY_FILE');

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    /**
     * Use mailgun for DEV environment.
     */
    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'google_tag_manager' => [
        'id' => env('GTM_ID'),
    ],

    'google' => [
        'client_id'             => env('GOOGLE_CLIENT_ID', null),
        'client_secret'         => env('GOOGLE_CLIENT_SECRET', null),
        'redirect'              => '/auth/google',

        'json_key'              => storage_path(env('GOOGLE_AUTH_KEY_FILE')),
        'cloud_key'             => storage_path(env('GOOGLE_CLOUD_KEY_FILE')),
        'project_id'            => env('GOOGLE_CLOUD_PROJECT'),
        'application_name'      => env('GOOGLE_APPLICATION_NAME', 'Sunrise'),
        'state'                 => null,

        /**
         * Enable it for staging and production credentials.
         */
        'use_application_default_credentials' => false,
    ],

    'google_pubsub' => [
        'projectId'             => env('GOOGLE_CLOUD_PROJECT'),
        'keyFilePath'           => $keyFile ? storage_path($keyFile) : null,
    ],

    'barn' => [
        'hosted_domain' => env('BARN_HOSTED_DOMAIN', 'sunrise.local'),
        'hosted_scheme' => env('BARN_HOSTED_SCHEME', 'http'),
    ],

    /**
     * OAuth1a configuration for mautic REST API.
     * Please use `apitester` inside `mautic/api-library` for getting access token.
     */
    'mautic' => [
        'base_url'              => env('MAUTIC_BASE_URL'),
        'public_key'            => env('MAUTIC_PUBLIC_KEY'),
        'secret_key'            => env('MAUTIC_SECRET_KEY'),
        'access_token'          => env('MAUTIC_ACCESS_TOKEN'),
        'access_token_secret'   => env('MAUTIC_ACCESS_TOKEN_SECRET'),
    ],

    'zapier' => [
        'redirect_uri' => env('ZAPIER_REDIRECT_URI'),
        'invite_uri' => env('ZAPIER_INVITE_URI'),
    ],

];
