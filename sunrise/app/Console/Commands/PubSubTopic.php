<?php

namespace App\Console\Commands;

use App\Services\PubSubService;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Console\Command;

class PubSubTopic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubsub:topic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pub\\Sub topics manager.';

    /**
     * @var PubSubService
     */
    protected $pubsub;

    /**
     * @var array
     */
    protected $topics = [
        PubSubService::TOPIC_SCHOLARSHIP,
        PubSubService::TOPIC_APPLICATION,
    ];

    /**
     * Create a new command instance.
     * @param PubSubClient $pubsub
     */
    public function __construct(PubSubClient $pubsub)
    {
        parent::__construct();
        $this->pubsub = $pubsub;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->topics as $topic) {

            if (!$this->pubsub->topic($topic)->exists()) {
                $this->pubsub->createTopic($topic);
            }

            $this->warn(sprintf('Pub\\Sub Topic exists: %s', $topic));
        }
    }
}
