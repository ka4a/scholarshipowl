<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;

class SubscriptionsAward extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "subscriptions:award";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Award recurrent subscriptions.";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("SubscriptionsAward Started: " . date("Y-m-d h:i:s"));

        try {
            $subscriptionService = new SubscriptionService();

            $subscriptionService->renewSubscriptions();
        }catch(\Exception $exc) {
            \Log::error($exc);
        }

        $this->info("SubscriptionsAward Ended: " . date("Y-m-d h:i:s"));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
        );
    }

}
