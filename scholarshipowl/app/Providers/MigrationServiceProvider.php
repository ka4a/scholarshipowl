<?php

namespace App\Providers;

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Migrations\Migrator;

class MigrationServiceProvider extends \Illuminate\Database\MigrationServiceProvider
{

    /**
     * MigrationServiceProvider constructor.
     * We need for rewrite db config for migration when write  and reading separation is used.
     * Еhe problem arises because of the deployment to the server and its Q server and delay of updating
     *
     * @param $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            /**
             * @var Resolver $db
             */
            $db = $app['db'];
            $db->setDefaultConnection('migration_db');

            return new Migrator($repository, $db, $app['files']);
        });
    }

}
