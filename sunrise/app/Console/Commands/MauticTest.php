<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mautic\Api\Emails;

class MauticTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mautic:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        /** @var Emails $emails */
        $emails = app(Emails::class);

        dd($emails->getList());
    }
}
