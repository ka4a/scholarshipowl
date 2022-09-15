<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),
    

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => storage_path().'/database.sqlite',
            'prefix'   => '',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'write'     => [
                'host' => env('DB_HOST_MASTER', 'sowl-sql')
            ],
            'read' => [
                [
                    'host' => env('DB_HOST_REPLICA', 'sowl-sql')
                ],
            ],
            'database'  => env('DB_DATABASE', 'scholarship_owl'),
            'username'  => env('DB_USERNAME', 'scholarship_owl'),
            'password'  => env('DB_PASSWORD', 'M4ElpojNx9sv9SUT'),
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'migration_db' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'sowl-sql'),
            'database'  => env('DB_DATABASE', 'scholarship_owl'),
            'username'  => env('DB_USERNAME', 'scholarship_owl'),
            'password'  => env('DB_PASSWORD', 'M4ElpojNx9sv9SUT'),
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => env("REDIS_HOST", "127.0.0.1"),
            'port'     => 6379,
            'database' => 0,
            'password' => env('REDIS_AUTH', 'Ctf7BBPW3rzhEEGe'),
        ],

        'shared' => [
            'host'     => env('REDIS_SHARED_HOST'),
            'port'     => env('REDIS_SHARED_PORT'),
            'database' => 0,
            'password' => env('REDIS_SHARED_AUTH', null)
        ],

        'queue' => [
            'host'     => env('REDIS_QUEUE_HOST', '127.0.0.1'),
            'port'     => env('REDIS_QUEUE_PORT', 6379),
            'database' => 0,
            'password' => env('REDIS_QUEUE_AUTH', 'Ctf7BBPW3rzhEEGe')
        ],

    ],

];
