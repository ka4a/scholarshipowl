<?php namespace App\Providers;

use App\Doctrine\Types\RecurrenceConfigType;
use Doctrine\DBAL\Types\Type;
use App\Doctrine\DoctrineUserProvider;
use \LaravelDoctrine\ORM\DoctrineServiceProvider as BaseServiceProvider;

class DoctrineServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
        if (!isset(Type::getTypesMap()[RecurrenceConfigType::NAME])) {
            Type::addType(RecurrenceConfigType::NAME, RecurrenceConfigType::class);
        }

        parent::boot();
    }

    public function register()
    {
        parent::register();

        $this->registerRepositories();
    }

    /**
     * Extend the auth manager
     */
    protected function extendAuthManager()
    {
        if ($this->app->bound('auth')) {
            $this->app->make('auth')->provider('doctrine', function ($app, $config) {
                return new DoctrineUserProvider($app['hash'], $app['em'], $config['model']);
            });
        }
    }

    public function registerRepositories()
    {
    }
}
