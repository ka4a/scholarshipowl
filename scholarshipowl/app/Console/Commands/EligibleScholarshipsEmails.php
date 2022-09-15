<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\AccountEligibleScholarshipsCount;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Services\PubSub\TransactionalEmailService;
use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Console\Command;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;
use ScholarshipOwl\Util\Mailer;

class EligibleScholarshipsEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarships:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email when eligible scholarship count increases';

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
        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = \EntityManager::getRepository(Scholarship::class);

        $query = \EntityManager::createQueryBuilder()
            ->select('a')
            ->from(Account::class, 'a')
            ->join('a.accountEligibleScholarshipsCount', 'eligibleScholarshipsCount')
            ->addSelect('eligibleScholarshipsCount')
            ->getQuery();

        $transactionEmailService = app(TransactionalEmailService::class);

        foreach (QueryIterator::create($query, 10000) as $accounts) {
            /** @var Account $account */
            foreach ($accounts as $account) {
                $scholarshipsCount = $scholarshipRepository->countEligibleScholarships($account);

                try{
                    if($scholarshipsCount > $account->getAccountEligibleScholarshipsCount()->getScholarshipCount()){
                        $transactionEmailService->sendCommonEmail($account, TransactionalEmailService::NEW_ELIGIBLE_SCHOLARSHIPS);
                    }
                    $account->getAccountEligibleScholarshipsCount()->setScholarshipCount($scholarshipsCount);
                }catch (EntityNotFoundException $ex){
                    $accountEligibleScholarshipsCount = new AccountEligibleScholarshipsCount($account, $scholarshipsCount);
                    \EntityManager::persist($accountEligibleScholarshipsCount);
                }
            }

            \EntityManager::flush();
        }
    }
}
