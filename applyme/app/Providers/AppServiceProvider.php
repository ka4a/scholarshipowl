<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mautic\Api\Contacts;
use Mautic\Api\Segments;
use Mautic\Auth\ApiAuth;
use Mautic\Auth\OAuth;
use Mautic\MauticApi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMautic();
    }

    /**
     * Register the Mautic APIs.
     *
     * @return void
     */
    protected function registerMautic()
    {
        $this->app->singleton(OAuth::class, function($app) {
            $initAuth = new ApiAuth();

            $config = config('mautic');

            $settings = [
                'version'           => 'OAuth1a',
                'baseUrl'           => $config['base_url'],
                'clientKey'         => $config['public_key'],
                'clientSecret'      => $config['secret_key'],
                'accessToken'       => $config['access_token'],
                'accessTokenSecret' => $config['access_token_secret'],
            ];

            return $initAuth->newAuth($settings, 'OAuth');
        });

        $this->app->singleton(Contacts::class, function($app) {
            return (new MauticApi())
                ->newApi('contacts', $app->make(OAuth::class), config('mautic.base_url') . '/api');
        });

        $this->app->singleton(Segments::class, function($app) {
            return (new MauticApi())
                ->newApi('segments', $app->make(OAuth::class), config('mautic.base_url') . '/api');
        });
    }

}
