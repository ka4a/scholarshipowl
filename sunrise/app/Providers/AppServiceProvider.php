<?php namespace App\Providers;

use App\Doctrine\Extensions\Uploadable\PrivateListener;
use App\Doctrine\Extensions\Uploadable\PublicListener;
use App\Services\UserManager;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Google\Cloud\ErrorReporting\Bootstrap;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use Mautic\Api\Contacts;
use Mautic\Api\Emails;
use App\Services\MauticService\Api\Smses;
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
        Passport::ignoreMigrations();
        if (isset($_SERVER['GAE_SERVICE'])) {
            Bootstrap::init();
        }

        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (in_array(App::environment(), ['production', 'staging'])) {
            \URL::forceScheme('https');
        }

        $this->registerMautic();
        $this->registerGoogleCloudStorage();
        $this->registerRepositories();
        $this->registerSunriseServices();
    }

    /**
     * Register doctrine repositories.
     */
    protected function registerRepositories()
    {
        $this->app->bind(UserRepository::class, function(Container $app) {
            return $app->make(\App\Bridge\Passport\UserRepository::class);
        });
    }

    /**
     * Register mautic APIs.
     */
    protected function registerMautic()
    {
        $mauticAPIURL = sprintf('%s/api', config('services.mautic.base_url'));

        $this->app->singleton(OAuth::class, function() {
            $apiAuth = new ApiAuth();
            $config = config('services.mautic');
            $credentials = array(
                'version'           => 'OAuth1a',
                'baseUrl'           => $config['base_url'],
                'clientKey'         => $config['public_key'],
                'clientSecret'      => $config['secret_key'],
                'accessToken'       => $config['access_token'],
                'accessTokenSecret' => $config['access_token_secret'],
            );

            return $apiAuth->newAuth($credentials, 'OAuth');
        });

        $this->app->singleton(Contacts::class, function(Container $app) use ($mauticAPIURL) {
            return (new MauticApi())->newApi('contacts', $app->make(OAuth::class), $mauticAPIURL);
        });

        $this->app->singleton(Emails::class, function(Container $app) use ($mauticAPIURL) {
            return (new MauticApi())->newApi('emails', $app->make(OAuth::class), $mauticAPIURL);
        });

        $this->app->singleton(Smses::class, function(Container $app) use ($mauticAPIURL) {
            return new Smses($app->make(OAuth::class), $mauticAPIURL);
        });
    }

    /**
     * Register google cloud storages.
     */
    protected function registerGoogleCloudStorage()
    {
        $this->app->singleton(PrivateListener::class, function() {
            return new PrivateListener();
        });
        $this->app->singleton(PublicListener::class, function() {
            return new PublicListener();
        });
    }

    /**
     * Register Sunrise application services
     */
    protected function registerSunriseServices()
    {
        $this->app->singleton(UserManager::class, function(Container $app) {
            return new UserManager(
                $app->make(EntityManager::class)
            );
        });
    }
}
