<?php

namespace App\Console\Commands;

use App\Entity\Repository\EntityRepository;
use App\Services\Account\AccountService;
use Illuminate\Console\Command;


class AccountHardDelete extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'account:hard-delete 
        {--softDeletedDaysAgo=7 : Number of days since account was soft-deleted}
        {--accountId= : Provide this option to remove only this account any conditions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hard-deletes previously soft-deleted accounts. Cleans up all relations too.';

    /**
     * @var AccountService
     */
    protected $service;


    public function __construct(AccountService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function handle()
    {
        $daysCnt = $this->option('softDeletedDaysAgo');
        $accountId = $this->option('accountId');

        if ($accountId) {
            $result = $this->service->hardDeleteAccount($accountId);
        } else {
            $result = $this->service->hardDeleteAccounts($daysCnt);
        }

        $command = $this->arguments()['command'];
        $this->info(
            sprintf('[ %s ] [ %s ] Accounts delete total: [ %s ]', date('c'), $command, $result['cnt'])
        );
        $deletedAccountsString = str_replace('=', ':', urldecode(http_build_query($result['accounts'], null, "\n")));
        if ($deletedAccountsString) {
            $this->info($deletedAccountsString);
        }
    }
}
