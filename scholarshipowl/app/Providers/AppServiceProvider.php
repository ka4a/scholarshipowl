<?php

namespace App\Providers;

use App\Entity\Account;
use App\Extensions\DatabaseStoreCustom;
use App\Logging\CoregsLogger;
use App\Logging\HasOffersLogger;
use App\Logging\StdoutFormatter;
use App\Services\Account\AccountService;
use App\Services\Account\ForgotPasswordService;
use App\Services\Account\SocialAccountService;
use App\Services\Account\AccountLoginTokenService;
use App\Services\Admin\ActivityLogger;
use App\Services\ApplicationService;
use App\Services\CmsService;
use App\Services\DocumentGenerator;
use App\Services\DomainService;
use App\Services\EligibilityCacheService;
use App\Services\EligibilityService;
use App\Services\FacebookService;
use App\Services\FileService;
use App\Services\Mailbox\MailboxService;
use App\Services\Mailbox\MailboxAPIDriver;
use App\Services\Mailbox\MailboxStubDriver;
use App\Services\Marketing\CoregRequirementsRuleService;
use App\Services\Marketing\CoregService;
use App\Services\Marketing\PushnamiService;
use App\Services\Marketing\RedirectRulesService;
use App\Services\Marketing\SubmissionService;
use App\Services\OptionsManager;
use App\Services\PaymentManager;
use App\Services\HasOffersService;
use App\Services\PopupService;
use App\Services\ScholarshipService;
use App\Services\SettingService;
use App\Services\SubscriptionService;
use App\Services\UnsubscribeEmailService;
use App\Services\WebStorage;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\DoctrineManager;
use League\Flysystem\Filesystem;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;
use Symfony\Component\HttpFoundation\Request;

class AppServiceProvider extends ServiceProvider
{

    const DOCTRINE_NAMESPACE = "App\\Entity\\";

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Ignore "migrations" table in Doctrine
        $this->app->make(DoctrineManager::class)->onResolve(function (ManagerRegistry $registry) {
            /** @var EntityManager $em */
            $em = $registry->getManager($registry->getDefaultManagerName());
            $em->getConfiguration()->setFilterSchemaAssetsExpression('/^((?!migrations).)*$/');
        });

        $this->customValidations();

        $this->bootGoogleCloudStorage();

        Cache::extend('databaseCustom', function ($app) {
            $config = $app['config']->get("cache.stores.databaseCustom");
            $connection = $this->app['db']->connection($config['connection'] ?? null);
            return Cache::repository(new DatabaseStoreCustom(
                $connection,
                $config['table'],
                $config['prefix'] ?? $this->app['config']['cache.prefix']
            ));
        });

        if (is_production() || app()->environment('kubernetes')) {
            \URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        \Request::setTrustedProxies(['104.155.92.77', '10.132.0.11'], Request::HEADER_X_FORWARDED_ALL);

        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );

        if (!$this->app->environment('production', 'staging')) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->bind(HasOffersLogger::class, function($app) {
            if (app()->environment('production')) {
                $handler = new StreamHandler('php://stdout');
                $formatter = new StdoutFormatter();
            } else {
                $handler = new RotatingFileHandler(
                    storage_path('logs/hasoffers.log'),
                    30,
                    \Monolog\Logger::DEBUG
                );
                $formatter = new LineFormatter(null, null, true, true);
            }
            $handler->setFormatter($formatter);

            return new HasOffersLogger(
                'HasOffers', [$handler]
            );
        });

        $this->app->bind(CoregsLogger::class, function($app) {
            if (app()->environment('production')) {
                $handler = new StreamHandler('php://stdout');
                $formatter = new StdoutFormatter();
            } else {
                $handler = new RotatingFileHandler(
                    storage_path('logs/coreg.error.log'),
                    10,
                    \Monolog\Logger::ERROR
                );
                $formatter = new LineFormatter(null, null, true, true);
            }
            $handler->setFormatter($formatter);

            return new CoregsLogger(
                'CoregsLogger', [$handler]
            );
        });

        $this->registerCms();
        $this->registerServices();
        $this->registerAccountServices();
        $this->registerScholarshipServices();
        $this->registerSocialServices();
        $this->registerMarketingServices();
    }

    protected function registerCms()
    {
        $this->app->alias(CmsService::class, 'cms');
        $this->app->singleton(CmsService::class, function(Container $app) {
            return new CmsService($app->make('em'), $app->make('cache.store'));
        });
    }

    protected function registerServices()
    {
        $this->app->singleton(SubscriptionService::class, function(Container $app) {
            return new SubscriptionService($app->make('em'), $app->make(PaymentManager::class));
        });

        $this->app->alias(DomainService::class, 'domain.service');
        $this->app->singleton(DomainService::class, function() {
            return new DomainService();
        });

        $this->app->alias(HasOffersLogger::class, 'log.hasoffers');
        $this->app->alias(CoregsLogger::class, 'log.coreg');

        $this->app->alias(SettingService::class, 'app.setting');
        $this->app->singleton(SettingService::class, function(Container $app) {
            return new SettingService($app->make('em'));
        });

        $this->app->singleton(ActivityLogger::class, function(Container $app) {
            return new ActivityLogger($app->make('em'));
        });

        $this->app->singleton(OptionsManager::class, function(Container $app) {
            return new OptionsManager(
                $app->make(EntityManager::class)
            );
        });

        $this->app->singleton(FileService::class, function(Container $app) {
            return new FileService();
        });

        $this->app->singleton(WebStorage::class, function(Container $app) {
            return new WebStorage(
                $app->make(OptionsManager::class),
                $app->make(SettingService::class),
                $app->make(CoregService::class)
            );
        });

        $this->app->singleton(HasOffersService::class, function() {
            return new HasOffersService();
        });

        $this->app->singleton(UnsubscribeEmailService::class, function(Container $app) {
            return new UnsubscribeEmailService($app->make('em'));
        });

        $this->app->singleton(\App\Services\PubSub\AccountService::class, function(Container $app) {
            return new \App\Services\PubSub\AccountService(
                $this->getPubSubClient(),
                $app->make(ManagerRegistry::class),
                $app->make(ScholarshipService::class),
                $app->make(AccountLoginTokenService::class),
                $app->make(EligibilityCacheService::class),
                $app->make(MailboxService::class)
            );
        });

        $this->app->singleton(\App\Services\Mailbox\MailboxService::class, function(Container $app) {
            if (in_array(\App::environment(), ['dev', 'testing'])) {
                $driver = new MailboxStubDriver();
            } else {
                $driver = new MailboxAPIDriver($this->getPubSubClient());
            }

            return new \App\Services\Mailbox\MailboxService($driver);
        });

        $this->app->singleton(\App\Services\PubSub\DigestService::class, function(Container $app) {
            return new \App\Services\PubSub\DigestService(
                $this->getPubSubClient()
            );
        });

        $this->app->singleton(\App\Services\PubSub\TransactionalEmailService::class, function(Container $app) {
            return new \App\Services\PubSub\TransactionalEmailService(
                $this->getPubSubClient(),
                $app->make(EntityManager::class)
            );
        });

        $this->app->bind(PopupService::class, function (Container $app) {
            return new PopupService(
                $app->make('em')
            );
        });
    }

    /**
     * @return PubSubClient
     */
    protected function getPubSubClient()
    {
        return new PubSubClient([
            'projectId' => is_production() ? 'scholarshipowl-1244' : 'sowl-tech',
            'keyFilePath' => is_production() ? null : base_path().'/config/google/sowl-tech-321f8bd9f681.json',
            'retries' => 3,
        ]);
    }

    protected function registerAccountServices()
    {
        $this->app->singleton(AccountLoginTokenService::class, function(Container $app) {
            return new AccountLoginTokenService(
                $app->make('em')
            );
        });

        $this->app->alias(AccountService::class, 'service.account');
        $this->app->singleton(AccountService::class, function(Container $app) {
            return new AccountService(
                $app->make(EntityManager::class),
                $app->make(DomainService::class),
                $app->make(FacebookService::class)
            );
        });

        $this->app->alias(ForgotPasswordService::class, 'service.account.forgot-password');
        $this->app->singleton(ForgotPasswordService::class, function(Container $app) {
            return new ForgotPasswordService(
                $app->make(EntityManager::class),
                $app->make(AccountService::class)
            );
        });
    }

    protected function registerScholarshipServices()
    {
        $this->app->singleton(EligibilityService::class, function(Container $app) {
            return new EligibilityService(
                $app->make(EntityManager::class),
                $app->make('cache')
            );
        });

        $this->app->singleton(DocumentGenerator::class, function() {
            return new DocumentGenerator();
        });

        $this->app->alias(ScholarshipService::class, 'scholarship.service');
        $this->app->singleton(ScholarshipService::class, function(Container $app) {
            return new ScholarshipService(
                $app->make(EligibilityService::class),
                $app->make(DocumentGenerator::class),
                $app->make(EntityManager::class)
            );
        });

        $this->app->alias(ApplicationService::class, 'application.service');
        $this->app->singleton(ApplicationService::class, function(Container $app) {
            return new ApplicationService(
                $app->make(ScholarshipService::class),
                $app->make(SubscriptionService::class),
                $app->make(EntityManager::class)
            );
        });
    }

    protected function registerSocialServices()
    {
        $this->app->singleton(SocialAccountService::class, function(Container $app) {
            return new SocialAccountService(
                $app->make(LaravelFacebookSdk::class),
                $app->make(EntityManager::class)
            );
        });
    }

    protected function registerMarketingServices()
    {
        $this->app->singleton(CoregService::class, function(Container $app) {
            return new CoregService(
                $app->make(CoregRequirementsRuleService::class),
                $app->make(EntityManager::class)
            );
        });

        $this->app->singleton(RedirectRulesService::class, function(Container $app) {
            return new RedirectRulesService(
                $app->make('em')
            );
        });

        $this->app->singleton(SubmissionService::class, function(Container $app) {
            return new SubmissionService(
                $app->make('em'),
                $app->make(CoregService::class)
            );
        });

        $this->app->singleton(PushnamiService::class, function() {
            return new PushnamiService();
        });
    }

    protected function customValidations()
    {
        /**
         * Validates that entity with provided ID exist.
         *
         * Usage:
         * 'attriubte': 'entity:Account'
         */
        \Validator::extend('entity', function($attribute, $value, $params, $validator) {
            if (count($params) < 1) {
                throw new \InvalidArgumentException("Validation rule 'entity' requires at least 1 parameter.");
            }

            $entity = self::DOCTRINE_NAMESPACE . str_replace(self::DOCTRINE_NAMESPACE, '', ltrim($params[0], '\\'));

            return \DoctrineManager::getManagerForClass($entity)->find($entity, $value) !== null;
        }, "Entity :parameters with id ':value' no found!");
    }

    protected function bootGoogleCloudStorage()
    {
        /** @var FilesystemManager $factory */
        $factory = $this->app->make('filesystem');
        $factory->extend('gcs', function($app, $config) {
            $storageClient = new StorageClient([
                'projectId'     => $config['project_id'],
                'keyFilePath'   => $config['key_file'],
            ]);

            $bucket = $storageClient->bucket($config['bucket']);
            $pathPrefix = $config['path_prefix'];
            $storageApiUrl = $config['storage_api_uri'];

            $adapter = new GoogleStorageAdapter($storageClient, $bucket, $pathPrefix, $storageApiUrl);

            return new Filesystem($adapter);
        });
    }
}
