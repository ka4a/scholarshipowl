<?php

namespace App\Console\Commands;

use App\Services\Account\AccountLoginTokenService;
use Illuminate\Console\Command;


class LoginTokenCleanup extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'account:login-token-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes old login tokens according to settings';

    /**
     * @var AccountLoginTokenService
     */
    protected $service;


    public function __construct(AccountLoginTokenService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function handle()
    {
        $command = $this->arguments()['command'];
        $itemsDeletedCnt = $this->service->deleteOutdated();

        $this->info(
            sprintf(
                '[ %s ] [ %s ] Tokens deleted total: [ %s ]',
                 date('c'), $command, $itemsDeletedCnt
            )
        );
    }
}
