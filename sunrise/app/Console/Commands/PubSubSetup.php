<?php

namespace App\Console\Commands;

use App\PubSub\Queue\PubSubQueue;
use App\Services\PubSubService;
use Illuminate\Console\Command;
use Illuminate\Queue\QueueManager;

class PubSubSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubsub:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create pubsub topics for specific environment.';

    /**
     * @var QueueManager
     */
    protected $queue;

    /**
     * @var PubSubService
     */
    protected $service;

    /**
     * List of queue connections that use pubsub.
     *
     * @var array
     */
    protected $queues = ['pubsub'];

    /**
     * Create a new command instance.
     *
     * @param QueueManager $queue
     * @param PubSubService $service
     */
    public function __construct(QueueManager $queue, PubSubService $service)
    {
        $this->queue = $queue;
        $this->service = $service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->service->setup();

        foreach ($this->queues as $connection) {
            $queue = $this->queue->connection($connection);
            if ($queue instanceof PubSubQueue) {
                $queue->setup();
            }
        }

        $this->info('Pub\Sub topics and subscriptions configured successfully.');
    }
}
