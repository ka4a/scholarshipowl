<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ZendeskImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zendesk:updateAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all users on zendesk';

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
        \Zendesk::updateAllUsers();
    }
}
