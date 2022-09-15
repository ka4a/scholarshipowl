<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Queue\QueueManager;

class QueueTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var QueueManager
     */
    protected $queue;

    /**
     * Create a new command instance.
     */
    public function __construct(QueueManager $queue)
    {
        parent::__construct();
        $this->queue = $queue;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App\Jobs\QueueTest::dispatch(['test' => 'data', 'test2' => 'afdsljfasldfjasdf']);
    }
}
