<?php

namespace App\Providers;

use Illuminate\Queue\Failed\NullFailedJobProvider;
use Illuminate\Queue\Failed\DatabaseFailedJobProvider;
use Illuminate\Queue\QueueServiceProvider;

class CanaryQueueServiceProvider extends QueueServiceProvider
{

    protected function registerFailedJobServices()
    {
        $this->app->singleton('queue.failer', function ($app) {

            $config = $app['config']['queue.failed'];
            if(strtolower(config('app.srv')) == 'canary'){
                $config = $app['config']['queue.failed_canary'];
            }

            if (isset($config['table'])) {
                return new DatabaseFailedJobProvider($app['db'], $config['database'], $config['table']);
            } else {
                return new NullFailedJobProvider;
            }
        });
    }
}
