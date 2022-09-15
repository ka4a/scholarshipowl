<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entity Mangers
    |--------------------------------------------------------------------------
    |
    | Configure your Entity Managers here. You can set a different connection
    | and driver per manager and configure events and filters. Change the
    | paths setting to the appropriate path and replace App namespace
    | by your own namespace.
    |
    | Available meta drivers: fluent|annotations|yaml|xml|config|static_php
    |
    | Available connections: mysql|oracle|pgsql|sqlite|sqlsrv
    | (Connections can be configured in the database config)
    |
    | --> Warning: Proxy auto generation should only be enabled in dev!
    |
    */
    'managers'                  => [
        'default' => [
            'dev'        => env('APP_DEBUG', false),
            'meta'       => env('DOCTRINE_METADATA', 'annotations'),
            'connection' => env('DB_CONNECTION', 'mysql'),
            'namespaces' => [
                'App\Entity',
            ],
            'paths'      => [
                base_path('app/Entity'),
            ],
            'repository' => App\Entity\Repository\EntityRepository::class,
            'proxies'    => [
                'namespace'     => false,
                'path'          => storage_path('proxies'),
                'auto_generate' => env('DOCTRINE_PROXY_AUTOGENERATE', false)
            ],
            /*
            |--------------------------------------------------------------------------
            | Doctrine events
            |--------------------------------------------------------------------------
            |
            | The listener array expects the key to be a Doctrine event
            | e.g. Doctrine\ORM\Events::onFlush
            |
            */
            'events'     => [
                'listeners'   => [],
                'subscribers' => []
            ],
            'filters'    => [],
            /*
            |--------------------------------------------------------------------------
            | Doctrine mapping types
            |--------------------------------------------------------------------------
            |
            | Link a Database Type to a Local Doctrine Type
            |
            | Using 'enum' => 'string' is the same of:
            | $doctrineManager->extendAll(function (\Doctrine\ORM\Configuration $configuration,
            |         \Doctrine\DBAL\Connection $connection,
            |         \Doctrine\Common\EventManager $eventManager) {
            |     $connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
            | });
            |
            | References:
            | http://doctrine-orm.readthedocs.org/en/latest/cookbook/custom-mapping-types.html
            | http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#custom-mapping-types
            | http://doctrine-orm.readthedocs.org/en/latest/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html
            | http://doctrine-orm.readthedocs.org/en/latest/reference/basic-mapping.html#reference-mapping-types
            | http://symfony.com/doc/current/cookbook/doctrine/dbal.html#registering-custom-mapping-types-in-the-schematool
            |--------------------------------------------------------------------------
            */
            'mapping_types' => [
                'enum' => 'string',
                'json' => 'json',
            ]
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine Extensions
    |--------------------------------------------------------------------------
    |
    | Enable/disable Doctrine Extensions by adding or removing them from the list
    |
    | If you want to require custom extensions you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'extensions'                => [
        //LaravelDoctrine\ORM\Extensions\TablePrefix\TablePrefixExtension::class,
        LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class,
        LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension::class,
        //LaravelDoctrine\Extensions\Sluggable\SluggableExtension::class,
        //LaravelDoctrine\Extensions\Sortable\SortableExtension::class,
        //LaravelDoctrine\Extensions\Tree\TreeExtension::class,
        //LaravelDoctrine\Extensions\Loggable\LoggableExtension::class,
        //LaravelDoctrine\Extensions\Blameable\BlameableExtension::class,
        //LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension::class,
        //LaravelDoctrine\Extensions\Translatable\TranslatableExtension::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine custom types
    |--------------------------------------------------------------------------
    |
    | Create a custom or override a Doctrine Type
    |--------------------------------------------------------------------------
    */
    'custom_types'              => [
        'json' => LaravelDoctrine\ORM\Types\Json::class
    ],
    /*
    |--------------------------------------------------------------------------
    | DQL custom datetime functions
    |--------------------------------------------------------------------------
    */
    'custom_datetime_functions' => [
        'now' => DoctrineExtensions\Query\Mysql\Now::class,
        'date' => DoctrineExtensions\Query\Mysql\Date::class,
        'date_format' => DoctrineExtensions\Query\Mysql\DateFormat::class,
        'dateadd' => DoctrineExtensions\Query\Mysql\DateAdd::class,
        'datesub' => DoctrineExtensions\Query\Mysql\DateSub::class,
        'datediff' => DoctrineExtensions\Query\Mysql\DateDiff::class,
        'day' => DoctrineExtensions\Query\Mysql\Day::class,
        'dayname' => DoctrineExtensions\Query\Mysql\DayName::class,
        'dayofweek' => DoctrineExtensions\Query\Mysql\DayOfWeek::class,
        'from_unixtime' => DoctrineExtensions\Query\Mysql\FromUnixtime::class,
        'last_day' => DoctrineExtensions\Query\Mysql\LastDay::class,
        'minute' => DoctrineExtensions\Query\Mysql\Minute::class,
        'second' => DoctrineExtensions\Query\Mysql\Second::class,
        'strtodate' => DoctrineExtensions\Query\Mysql\StrToDate::class,
        'time' => DoctrineExtensions\Query\Mysql\Time::class,
        'timediff' => DoctrineExtensions\Query\Mysql\TimeDiff::class,
        'timestampadd' => DoctrineExtensions\Query\Mysql\TimestampAdd::class,
        'timestampdiff' => DoctrineExtensions\Query\Mysql\TimestampDiff::class,
        'week' => DoctrineExtensions\Query\Mysql\Week::class,
        'weekday' => DoctrineExtensions\Query\Mysql\WeekDay::class,
        'year' => DoctrineExtensions\Query\Mysql\Year::class,
        'yearweek' => DoctrineExtensions\Query\Mysql\YearWeek::class,
        'unix_timestamp' => DoctrineExtensions\Query\Mysql\UnixTimestamp::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | DQL custom numeric functions
    |--------------------------------------------------------------------------
    */
    'custom_numeric_functions'  => [
        'acos' => DoctrineExtensions\Query\Mysql\Acos::class,
        'asin' => DoctrineExtensions\Query\Mysql\Asin::class,
        'atan2' => DoctrineExtensions\Query\Mysql\Atan2::class,
        'atan' => DoctrineExtensions\Query\Mysql\Atan::class,
        'bit_count' => DoctrineExtensions\Query\Mysql\BitCount::class,
        'bit_xor' => DoctrineExtensions\Query\Mysql\BitXor::class,
        'ceil' => DoctrineExtensions\Query\Mysql\Ceil::class,
        'cos' => DoctrineExtensions\Query\Mysql\Cos::class,
        'cot' => DoctrineExtensions\Query\Mysql\Cot::class,
        'floor' => DoctrineExtensions\Query\Mysql\Floor::class,
        'hour' => DoctrineExtensions\Query\Mysql\Hour::class,
        'pi' => DoctrineExtensions\Query\Mysql\Pi::class,
        'power' => DoctrineExtensions\Query\Mysql\Power::class,
        'quarter' => DoctrineExtensions\Query\Mysql\Quarter::class,
        'rand' => DoctrineExtensions\Query\Mysql\Rand::class,
        'round' => DoctrineExtensions\Query\Mysql\Round::class,
        'sin' => DoctrineExtensions\Query\Mysql\Sin::class,
        'std' => DoctrineExtensions\Query\Mysql\Std::class,
        'tan' => DoctrineExtensions\Query\Mysql\Tan::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | DQL custom string functions
    |--------------------------------------------------------------------------
    */
    'custom_string_functions'   => [
        'ascii' => DoctrineExtensions\Query\Mysql\Ascii::class,
        'binary' => DoctrineExtensions\Query\Mysql\Binary::class,
        'char_length' => DoctrineExtensions\Query\Mysql\CharLength::class,
        'concat_ws' => DoctrineExtensions\Query\Mysql\ConcatWs::class,
        'countif' => DoctrineExtensions\Query\Mysql\CountIf::class,
        'crc32' => DoctrineExtensions\Query\Mysql\Crc32::class,
        'degrees' => DoctrineExtensions\Query\Mysql\Degrees::class,
        'field' => DoctrineExtensions\Query\Mysql\Field::class,
        'find_in_set' => DoctrineExtensions\Query\Mysql\FindInSet::class,
        'group_concat' => DoctrineExtensions\Query\Mysql\GroupConcat::class,
        'ifelse' => DoctrineExtensions\Query\Mysql\IfElse::class,
        'ifnull' => DoctrineExtensions\Query\Mysql\IfNull::class,
        'least' => DoctrineExtensions\Query\Mysql\Least::class,
        'lpad' => DoctrineExtensions\Query\Mysql\Lpad::class,
        'match_against' => DoctrineExtensions\Query\Mysql\MatchAgainst::class,
        'md5' => DoctrineExtensions\Query\Mysql\Md5::class,
        'month' => DoctrineExtensions\Query\Mysql\Month::class,
        'monthname' => DoctrineExtensions\Query\Mysql\MonthName::class,
        'nullif' => DoctrineExtensions\Query\Mysql\NullIf::class,
        'radians' => DoctrineExtensions\Query\Mysql\Radians::class,
        'regexp' => DoctrineExtensions\Query\Mysql\Regexp::class,
        'replace' => DoctrineExtensions\Query\Mysql\Replace::class,
        'rpad' => DoctrineExtensions\Query\Mysql\Rpad::class,
        'sha1' => DoctrineExtensions\Query\Mysql\Sha1::class,
        'sha2' => DoctrineExtensions\Query\Mysql\Sha2::class,
        'soundex' => DoctrineExtensions\Query\Mysql\Soundex::class,
        'substring_index' => DoctrineExtensions\Query\Mysql\SubstringIndex::class,
        'uuid_short' => DoctrineExtensions\Query\Mysql\UuidShort::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Enable query logging with laravel file logging,
    | debugbar, clockwork or an own implementation.
    | Setting it to false, will disable logging
    |
    | Available:
    | - LaravelDoctrine\ORM\Loggers\LaravelDebugbarLogger
    | - LaravelDoctrine\ORM\Loggers\ClockworkLogger
    | - LaravelDoctrine\ORM\Loggers\FileLogger
    |--------------------------------------------------------------------------
    */
    'logger'                    => env('PHP_DEBUG_BAR') ?
        \LaravelDoctrine\ORM\Loggers\LaravelDebugbarLogger::class : null,

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configure meta-data, query and result caching here.
    | Optionally you can enable second level caching.
    |
    | Available: acp|array|file|memcached|redis|void
    |
    */
    'cache'                     => [
        'default'                => env('DOCTRINE_CACHE', 'redis'),
        'namespace'              => sprintf('DOCTRINE_CACHE_%s', revision('VERSION')),
        'second_level'           => false,
    ],
    /*
    |--------------------------------------------------------------------------
    | Gedmo extensions
    |--------------------------------------------------------------------------
    |
    | Settings for Gedmo extensions
    | If you want to use this you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'gedmo'                     => [
        'all_mappings' => false
    ]
];
