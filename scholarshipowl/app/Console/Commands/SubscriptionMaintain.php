<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class SubscriptionMaintain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:maintain {--date=now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Expires subscriptions. Accrue credits for freemium subscription. 
    Command should run every day on 00:00 at US/Eastern timezone";

    /**
     * @var SubscriptionService
     */
    protected $service;

    /**
     * SubscriptionMaintainExpire constructor.
     *
     * @param SubscriptionService $service
     */
    public function __construct(SubscriptionService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = new \DateTime($this->option('date'));
        $this->info(sprintf('[%s] Started subscriptions maintain', date('c')));
        $this->service->maintain($date, $this);
        $this->info(sprintf('[%s] Finished subscriptions maintain', date('c')));
    }
}

