<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Services\PubSub\AccountService;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class PubSubAccountsUpdate extends Command
{
    const CHUNK_SIZE = 1000;

    const DELIMITER = ",";

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $fullAccountFields;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature
        = "pubsub:accounts-update
                        {--query= : Query for fetch account for updating. Example: SELECT a.account_id FROM account a INNER JOIN transaction t on a.account_id = t.account_id where a.account_id = 1 }
                        {--fields= : List of fields for update. Should be separated be , .Example: email, account_id }
                        {--chunkSize=1000 : Chunk size. Default 1000 items }
                        {--test : Test mode. Show only the number of accounts to be updated}";
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update account on PubSub side by filled query and fields';

    /**
     * PubSubAccountsUpdate constructor.
     *
     * @param AccountService $accountService
     * @param EntityManager  $em
     *
     * @throws \ReflectionException
     */
    public function __construct(
        AccountService $accountService,
        EntityManager $em
    ) {
        parent::__construct();

        $this->accountService = $accountService;
        $this->em = $em;
        $this->fullAccountFields = array_values(AccountService::fields());
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(sprintf('[%s] Started preparing for updating account on PubSub', date('c')));
        $accountFetchQuery = $this->option('query');

        if (!$accountFetchQuery) {
            $this->error('--query option is required');
            return;
        }

        //modify query for use LIMIT in processing
        if (strpos($accountFetchQuery, 'account_id') === false) {
            $this->error('Query string should contains account_id');

            return;
        }

        $this->info("Query for loading users:");
        $this->comment($accountFetchQuery);

        $totalCount = $this->calcCount($accountFetchQuery);

        $fieldList = $this->setFieldsList();
        if (empty($fieldList)) {
            return;
        }

        $this->info("Field list for updating:");
        $this->comment(implode($fieldList,', '));

        $isTest = $this->option('test');

        if ($isTest) {
            $this->alert('The command was launched in TEST mode');
        } else {
            $this->alert('The command was launched in LIVE mode');
            if ($this->choice("Start updating accounts in PubSub?", ["yes", "no"], 0) == "no") {
                return;
            }
        }

        $start = 0;
        $limit = self::CHUNK_SIZE;

        if(!is_null($this->option('chunkSize'))) {
            $limit = $this->option('chunkSize');
        }

        $chunkIndex = 0;

        if (strpos($accountFetchQuery, 'limit') === false) {
            $accountFetchQuery .= " LIMIT ?, ?";
        }

        $this->info(sprintf('[%s] Chunk size: %s ', date('c'), $limit));
        if (!$isTest) {
            while ($accounts = $this->fetchAccountsList($accountFetchQuery, $start, $limit)) {
                $this->info(sprintf('[%s] Started updating %s chunk', date('c'), $chunkIndex));
                foreach ($accounts as $account) {
                    try {
                        $this->accountService->updateAccount($account, $fieldList);
                    } catch (\Exception $e) {
                        $this->error($e);
                    }
                }

                $start += $limit;
                $chunkIndex++;
            }
        }

        $this->info(sprintf('[%s] Total number of chunks: %s ', date('c'), ceil($totalCount/$limit)));
        $this->info(sprintf('[%s] Total number of accounts for updating: %s', date('c'), $totalCount));
    }

    /**
     * Load entities list of accounts for update on PubSide side
     *
     * @param $fetchQuery
     * @param $start
     * @param $limit
     *
     * @return Account[]|[]
     */
    protected function fetchAccountsList($fetchQuery, $start, $limit)
    {
        $result = \DB::select($fetchQuery, [$start, $limit]);
        if (empty($result)) {
            return [];
        }

        return $this->getEntitiesByAccountIds(array_map('current', $result));
    }

    /**
     * @param $accountIds
     *
     * @return array
     */
    protected function getEntitiesByAccountIds($accountIds): array
    {
        $qb = $this->em->getRepository(Account::class)
            ->createQueryBuilder('a', 'a.accountId');
        $accounts = $qb->where(
            $qb->expr()->in('a.accountId', $accountIds)
        )
            ->getQuery()
            ->getResult();

        return $accounts;
    }

    /**
     * Prepare and check if filled fields exist in Account
     *
     * @return array
     */
    protected function setFieldsList(): array
    {
        $fieldsExist = true;
        $fieldList = $this->fullAccountFields;
        if (!is_null($this->option('fields'))) {
            $fieldList = explode(self::DELIMITER,
                str_replace(' ', '', $this->option('fields')));
        }

        return $fieldsExist ? $fieldList : [];
    }

    /**
     * Return total count
     *
     * @param $fetchQuery
     *
     * @return mixed
     */
    protected function calcCount($fetchQuery){
        $accountFetchQuery = preg_replace("/\s+[a-zA-Z]?.?account_id/", ' COUNT(*) as total_count', $fetchQuery, 1);

        if(stripos($fetchQuery, 'distinct') !== false) {
            $lowerQuery = strtolower($fetchQuery);
            $accountFetchQuery = preg_replace('/\s+(distinct)?.?[a-zA-Z]?.?account_id/', ' COUNT(distinct(s.account_id)) as total_count', $lowerQuery, 1);
        }

        $this->info('Query for count:');
        $this->comment($accountFetchQuery);
        $this->line('');

        $rs = \DB::select($accountFetchQuery);
        return $rs[0]->total_count;
    }

}
