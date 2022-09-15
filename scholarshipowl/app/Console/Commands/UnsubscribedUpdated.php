<?php

namespace App\Console\Commands;

use App\Services\UnsubscribeEmailService;
use Illuminate\Console\Command;

class UnsubscribedUpdated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unsubscribed:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and upload to cloud new unsubscribed list.';

    /**
     * @var UnsubscribeEmailService
     */
    protected $service;

    /**
     * Create a new command instance.
     */
    public function __construct(UnsubscribeEmailService $service)
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
        $this->info('Generating and uploading to cloud.');
        $this->info(sprintf('Uploaded: %s', $this->service->updateCsvList()));
    }
}
