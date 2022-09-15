<?php
return [
	'default' => env('CACHE_DRIVER', 'redis'),
	'stores' => [

		'apc' => [
			'driver' => 'apc'
		],

		'array' => [
			'driver' => 'array'
		],

		'databaseCustom' => [
			'driver' => 'databaseCustom',
			'table'  => 'cache',
			'connection' => null,
		],

		'file' => [
			'driver' => 'file',
			'path'   => storage_path().'/framework/cache',
		],

		'memcached' => [
			'driver'  => 'memcached',
			'servers' => [
				[
					'host' => env('MEMCACHED_HOST', '127.0.0.1'), 'port' => env('MEMCACHED_PORT', '11211'), 'weight' => 100
				],
			],
		],

		'redis' => [
			'driver' => 'redis',
			'connection' => 'default',
		],

        'redisShared' => [
			'driver' => env('CACHE_DRIVER', 'redis'),
			'connection' => 'shared',
		]

	],

	'prefix' => 'laravel',

];
