<?php

namespace App\Console\Commands;

use App\Entity\Repository\EntityRepository;
use App\Services\Account\AccountService;
use Illuminate\Console\Command;


class AccountTestDelete extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'account:test-delete 
        {--createdDaysAgo=7 : Number of days since account creation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes test accounts';

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
        $daysCnt = $this->option('createdDaysAgo');

        $result = $this->service->hardDeleteTestAccounts($daysCnt);
        $command = $this->arguments()['command'];
        $this->info(
            sprintf('[ %s ] [ %s ] Accounts deleted total: [ %s ]', date('c'), $command, $result['cnt'])
        );
        $deletedAccountsString = str_replace('=', ':', urldecode(http_build_query($result['accounts'], null, "\n")));
        if ($deletedAccountsString) {
            $this->info($deletedAccountsString);
        }
    }
}
