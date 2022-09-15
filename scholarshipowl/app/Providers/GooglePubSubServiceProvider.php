<?php

namespace App\Providers;

use App\Queue\Connectors\PubSubConnector;
use Illuminate\Support\ServiceProvider;

class GooglePubSubServiceProvider extends ServiceProvider
{
    /**
     * Register the application's event listeners.
     * Publish config file
     *
     * @return void
     */
    public function boot()
    {
        app('queue')->addConnector('pubsub', function () {
            return new PubSubConnector();
        });
    }
}
