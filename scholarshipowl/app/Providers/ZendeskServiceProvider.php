<?php

namespace App\Providers;

use App\Services\Zendesk\ZendeskService;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use \Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Zendesk\API\HttpClient as ZendeskAPI;


class ZendeskServiceProvider extends ServiceProvider
{
    /**
     * Method that creates a new instance of Zendesk API
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('zendesk.client', function(){

            $client = new ZendeskAPI(
                config('zendesk.subdomain'),
                config('zendesk.token')
            );

            $client->setAuth('basic',[
                'username' => config('zendesk.username'),
                'token' => config('zendesk.token')
            ]);

            return $client;
        });

        $this->app->singleton('zendesk', function(\Illuminate\Foundation\Application $app){
            return new ZendeskService(config('zendesk'), $app->make('zendesk.client'));
        });
    }
}
