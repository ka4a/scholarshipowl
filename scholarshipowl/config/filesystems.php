<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "s3", "rackspace"
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 'gcs',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path().'/app',
        ],

        'gcs' => [
            'driver'            => 'gcs',
            'bucket'            => env('GCS_BUCKET', 'storage.scholarshipowl.com'),
            'project_id'        => env('GCS_PROJECT_ID', 'scholarshipowl-1244'),
            'key_file'          => env('GCS_KEY_FILE', config_path('google/ScholarshipOwl-07ec15e30558.json')),
            'mobile_key_file'   => env('GCS_MOBILE_KEY_FILE', config_path('google/sowl-mobile-firebase-adminsdk-udj68-69dae43161.json')),
            'path_prefix'       => env('GCS_STORAGE_PATH_PREFIX', null),
            'storage_api_uri'   => env('GCS_STORAGE_API_URI', null),
        ],

        's3' => [
            'driver' => 's3',
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

        'rackspace' => [
            'driver'    => 'rackspace',
            'username'  => 'your-username',
            'key'       => 'your-key',
            'container' => 'your-container',
            'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'IAD',
            'url_type'  => 'publicURL'
        ],

        'edvisors' => [
            'driver' => 'sftp',
            'host' => env('EDVISORS_FTP_HOST'),
            'username' => env('EDVISORS_FTP_USERNAME'),
            'password' => env('EDVISORS_FTP_PASSWORD')
        ],
    ],

];
