<?php

return [

    'name' => 'Scholarship Owl',

    'env' => env('APP_ENV', 'production'),

    'srv' => env('APP_SRV', 'prod'),

    /**
     * Define if application is master.
     * Master it is server where all queue jobs are run.
     */
    'master' => env('APP_MASTER', false),

    /*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => env('APP_DEBUG', false),

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => env('APP_URL', 'https://scholarshipowl.com'),

	/*
	 * Live amazon s3 bucket
	 */


	's3bucket' => env('APP_S3BUCKET', 'scholarship-live'),

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'Europe/Berlin',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Application Fallback Locale
	|--------------------------------------------------------------------------
	|
	| The fallback locale determines the locale to use when the current one
	| is not available. You may change the value to correspond to any of
	| the language folders that are provided through your application.
	|
	*/

	'fallback_locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => env('APP_KEY', 'base64:HUoWPNZhqiK8DVKDXe2jCDtDqZRTuZfBn0TO60zp7Wg='),

	'cipher' => 'AES-256-CBC',

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => [

		/**
		 * Laravel Framework Service Providers...
		 */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
//        Illuminate\Queue\QueueServiceProvider::class,
        \App\Providers\CanaryQueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,

        /**
         * Extensions
         */
        LaravelDoctrine\Extensions\GedmoExtensionsServiceProvider::class,
        MaxBrokman\SafeQueue\DoctrineQueueProvider::class,
        Intervention\Image\ImageServiceProviderLaravel5::class,
        Collective\Html\HtmlServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
        Roumen\Sitemap\SitemapServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,
        Wilgucki\Csv\CsvServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
        Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
        Sorskod\Larasponse\LarasponseServiceProvider::class,
        Appstract\Opcache\OpcacheServiceProvider::class,
        Davibennun\LaravelPushNotification\LaravelPushNotificationServiceProvider::class,
        SammyK\LaravelFacebookSdk\LaravelFacebookSdkServiceProvider::class,
        \Sentry\Laravel\ServiceProvider::class,

        /**
         * Register Stripe provider
         */
        Cartalyst\Stripe\Laravel\StripeServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\DoctrineServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\ZendeskServiceProvider::class,
        App\Providers\PaymentServiceProvider::class,
        App\Providers\ApplyMe\FractalServiceProvider::class,
        \App\Providers\MigrationServiceProvider::class,

        /**
         * TODO: REMOVE
         */
        Aws\Laravel\AwsServiceProvider::class,
	],

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => [
		'App'       => Illuminate\Support\Facades\App::class,
		'Artisan'   => Illuminate\Support\Facades\Artisan::class,
		'Auth'      => Illuminate\Support\Facades\Auth::class,
		'Blade'     => Illuminate\Support\Facades\Blade::class,
		'Bus'       => Illuminate\Support\Facades\Bus::class,
		'Cache'     => Illuminate\Support\Facades\Cache::class,
		'Config'    => Illuminate\Support\Facades\Config::class,
		'Cookie'    => Illuminate\Support\Facades\Cookie::class,
		'Crypt'     => Illuminate\Support\Facades\Crypt::class,
		'DB'        => Illuminate\Support\Facades\DB::class,
		'Event'     => Illuminate\Support\Facades\Event::class,
		'File'      => Illuminate\Support\Facades\File::class,
        'Gate'      => Illuminate\Support\Facades\Gate::class,
		'Hash'      => Illuminate\Support\Facades\Hash::class,
		'Input'     => Illuminate\Support\Facades\Input::class,
		'Inspiring' => Illuminate\Foundation\Inspiring::class,
		'Lang'      => Illuminate\Support\Facades\Lang::class,
		'Log'       => Illuminate\Support\Facades\Log::class,
		'Mail'      => Illuminate\Support\Facades\Mail::class,
		'Password'  => Illuminate\Support\Facades\Password::class,
		'Queue'     => Illuminate\Support\Facades\Queue::class,
		'Redirect'  => Illuminate\Support\Facades\Redirect::class,
		'Redis'     => Illuminate\Support\Facades\Redis::class,
		'Request'   => Illuminate\Support\Facades\Request::class,
		'Response'  => Illuminate\Support\Facades\Response::class,
		'Route'     => Illuminate\Support\Facades\Route::class,
		'Schema'    => Illuminate\Support\Facades\Schema::class,
		'Session'   => Illuminate\Support\Facades\Session::class,
		'URL'       => Illuminate\Support\Facades\URL::class,
		'Validator' => Illuminate\Support\Facades\Validator::class,
		'View'      => Illuminate\Support\Facades\View::class,
        'Str'       => Illuminate\Support\Str::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,


        /**
         * Plugins and extensions
         */
        'Agent'            => Jenssegers\Agent\Facades\Agent::class,
        'Form'             => Collective\Html\FormFacade::class,
        'HTML'             => Collective\Html\HtmlFacade::class,
        'AWS'              => Aws\Laravel\AwsFacade::class,
        'Image'            => Intervention\Image\Facades\Image::class,
        'DoctrineManager'  => LaravelDoctrine\ORM\Facades\Registry::class,
        'PDF'              => Barryvdh\DomPDF\Facade::class,
        'CsvReader'        => Wilgucki\Csv\Facades\Reader::class,
        'CsvWriter'        => Wilgucki\Csv\Facades\Writer::class,
        'Sentry'           => \Sentry\Laravel\Facade::class,
        'Debugbar'         => Barryvdh\Debugbar\Facade::class,
        'JWTAuth'          => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory'       => Tymon\JWTAuth\Facades\JWTFactory::class,
        'PushNotification' => Davibennun\LaravelPushNotification\Facades\PushNotification::class,
        'FacebookFacade' => SammyK\LaravelFacebookSdk\FacebookFacade::class,

        'Stripe' => Cartalyst\Stripe\Laravel\Facades\Stripe::class,

        /**
         * Application Facades
         */

        "Registry"           => LaravelDoctrine\ORM\Facades\Registry::class, // register it here because auto-discovery for laravel-doctrine/orm is disabled (see composer.json)
        "Doctrine"           => LaravelDoctrine\ORM\Facades\Doctrine::class, // register it here because auto-discovery for laravel-doctrine/orm is disabled (see composer.json)
        'EntityManager'      => App\Facades\EntityManager::class,
        'PaymentManager'     => App\Facades\PaymentManager::class,
        'ScholarshipService' => App\Facades\ScholarshipService::class,
        'Storage'            => App\Facades\Storage::class,
        'Zendesk'            => App\Facades\Zendesk::class,
        'Mailbox'            => App\Facades\Mailbox::class,
        'HasOffers'          => App\Facades\LogHasoffers::class,
        'CoregLogger'        => App\Facades\CoregLogger::class,
        'Setting'            => App\Facades\Setting::class,
        'DocumentGenerator'  => App\Facades\DocumentGenerator::class,
        'Minify'             => ScholarshipOwl\Minify\Facades\MinifyFacade::class,
        'Domain'             => App\Facades\Domain::class,
        'CMS'                => App\Facades\CMS::class,
        'Options'            => App\Facades\Options::class,
        'WebStorage'         => App\Facades\WebStorage::class,
    ],

];
