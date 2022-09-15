<?php namespace App\Providers;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Illuminate\Container\Container;
use LaravelDoctrine\ORM\Auth\DoctrineUserProvider;
use LaravelDoctrine\ORM\Configuration\Cache\CacheManager;
use LaravelDoctrine\ORM\Configuration\MetaData\MetaDataManager;
use ScholarshipOwl\Doctrine\DBAL\Types\TimezoneDateTimeType;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\Type as DoctrineType;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Proxy\Autoloader as ProxyAutoloader;
use ScholarshipOwl\Doctrine\ORM\EntityManager;

class DoctrineServiceProvider extends \LaravelDoctrine\ORM\DoctrineServiceProvider
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        // DoctrineType::overrideType('datetime', TimezoneDateTimeType::class);
        // DoctrineType::overrideType('datetimetz', TimezoneDateTimeType::class);

        parent::boot();
    }

    public function register()
    {
        parent::register();
        $this->registerProxyAutoloader();
        $this->registerAnnotations();
        $this->addAnnotationCacheDriver();
    }

    public function registerEntityManager()
    {
        parent::registerEntityManager();

        $this->app->singleton(EntityManager::class, function(Container $app) {
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $app->make('registry')->getManager();

            return new EntityManager($em);
        });
    }

    public function registerProxyAutoloader()
    {
         $this->app->afterResolving('registry', function (ManagerRegistry $registry) {
            /** @var EntityManagerInterface $manager */
            foreach ($registry->getManagers() as $manager) {
                ProxyAutoloader::register(
                    $manager->getConfiguration()->getProxyDir(),
                    $manager->getConfiguration()->getProxyNamespace()
                );
            }
        });
    }

    private function registerAnnotations()
    {
        AnnotationRegistry::registerLoader(function($class) {
            if (strpos($class, 'App\Entity\Annotations') !== 0) {
                return false;
            }

            $file = ltrim(str_replace("\\", DIRECTORY_SEPARATOR, $class));
            $file = app_path(str_replace('App'.DIRECTORY_SEPARATOR, '', $file) .'.php');
            if (file_exists($file)) {
                require $file;
                return true;
            }

            return false;
        });
    }

    private function addAnnotationCacheDriver()
    {
        /** @var MetaDataManager $metaDataDriver */
        $metaDataDriver = $this->app->make(MetaDataManager::class);

        $metaDataDriver->extend('annotations_cached', function($settings) {
            $annotationsClass = new \ReflectionClass(AnnotationDriver::class);
            AnnotationRegistry::registerFile(dirname($annotationsClass->getFileName()) . '/DoctrineAnnotations.php');

            /** @var CacheManager $cacheDriver */
            $cacheDriver = $this->app->make(CacheManager::class);

            return new AnnotationDriver(
                new CachedReader(new AnnotationReader(), $cacheDriver->driver()),
                (array) array_get($settings, 'paths', [])
            );
        });
    }

    /**
     * Extend the auth manager
     */
    protected function extendAuthManager(){
        $this->app->make('auth')->provider('doctrine', function ($app, $config) {
            $entity = $config['model'];

            $em = $app->make('registry')->getManager();

            if (!$em) {
                throw new \InvalidArgumentException("No EntityManager is set-up for {$entity}");
            }

            return new DoctrineUserProvider(
                $app['hash'],
                $em,
                $entity
            );
        });
    }
}
