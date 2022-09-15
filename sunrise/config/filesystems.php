<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'private'),

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

    'cloud' => env('FILESYSTEM_CLOUD', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'tmp' => [
            'driver'            => 'local',
            'root'              => sys_get_temp_dir()
        ],

        'local' => [
            'driver'            => 'local',
            'root'              => public_path('storage'),
            'url'               => env('APP_URL', '') . '/storage'
        ],

        'private' => [
            'driver'            => 'gcs',
            'key_file'          => storage_path(env('GOOGLE_CLOUD_KEY_FILE')),
            'project_id'        => env('GOOGLE_CLOUD_PROJECT'),
            'bucket'            => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
            'path_prefix'       => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX'),
            'storage_api_uri'   => env('GOOGLE_CLOUD_STORAGE_API_URI'),
        ],

        'public' => [
            'driver'            => 'gcs',
            'key_file'          => storage_path(env('GOOGLE_CLOUD_KEY_FILE')),
            'project_id'        => env('GOOGLE_CLOUD_PROJECT'),
            'bucket'            => env('GOOGLE_CLOUD_PUBLIC_STORAGE_BUCKET'),
            'path_prefix'       => env('GOOGLE_CLOUD_PUBLIC_STORAGE_PATH_PREFIX'),
            'storage_api_uri'   => env('GOOGLE_CLOUD_STORAGE_API_URI'),
        ],

    ],

];
