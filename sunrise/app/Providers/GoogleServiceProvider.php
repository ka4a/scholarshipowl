<?php namespace App\Providers;

use App\PubSub\Queue\PubSubConnector;
use App\Services\GoogleVision;
use App\Services\PubSubService;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Illuminate\Container\Container;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

use Google\Cloud\PubSub\PubSubClient;

/**
 * Provides all google services.
 */
class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Boot google services
     */
    public function boot()
    {
        /** @var QueueManager $queueManager */
        $queueManager = app('queue');
        $queueManager->addConnector('pubsub', function() {
            return new PubSubConnector(
                app(PubSubClient::class)
            );
        });
    }

    /**
     * Register google services in container.
     */
    public function register()
    {
        /*
        $this->app->singleton(\Google_Client::class, function() {
            $config = config('services.google');
            $client = new \Google_Client($config);

            if (isset($config['json_key']) && file_exists($config['json_key'])) {
                $client->setAuthConfig($config['json_key']);
            }

            $client->setScopes([
                \Google_Service_Oauth2::PLUS_ME,
                \Google_Service_Oauth2::USERINFO_EMAIL,
                \Google_Service_Oauth2::USERINFO_PROFILE,
            ]);

            return $client;
        });
        */

        $this->app->bind(ImageAnnotatorClient::class, function() {
            $options = [];
            $config = config('services.google');

            if (isset($config['cloud_key']) && file_exists($config['cloud_key'])) {
                $options['credentials'] = $config['cloud_key'];
            }

            return new ImageAnnotatorClient($options);
        });

        $this->app->bind(GoogleVision::class, function(Container $app) {
            return new GoogleVision(
                $app->make(ImageAnnotatorClient::class)
            );
        });

        $this->app->bind(PubSubClient::class, function() {
            $config = config('services.google_pubsub');

            return new PubSubClient($config);
        });

        $this->app->bind(PubSubService::class, function(Container $app) {
            return new PubSubService(
                $app->make(PubSubClient::class)
            );
        });
    }
}
