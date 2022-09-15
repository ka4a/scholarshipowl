<?php

namespace App\Console\Commands;

use App\Entity\Profile;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use App\Services\ApplicationService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Illuminate\Console\Command;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class ScholarshipsRecurrenceApply extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarships:recurrence-apply
       {--accountId= : Particular profile to submit applications for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply users to recurrent scholarships.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
     * @var ApplicationService
     */
    protected $service;

    /**
     * Create a new command instance.
     *
     * @param EntityManager $entityManager
     * @param ApplicationService $service
     *
     * @return void
     */
    public function __construct(EntityManager $entityManager, ApplicationService $service)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->service = $service;
        $this->repository = $entityManager->getRepository(Scholarship::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("scholarships:recurrence-apply started: " . date("Y-m-d h:i:s"));

        $accountId = $this->option('accountId');
        $query = $this->em->createQueryBuilder()
            ->select(['p'])
            ->from(Profile::class, 'p')
            ->innerJoin(Subscription::class, 's', Join::WITH, 's.account = p.account')
            ->where('p.recurringApplication = :setting')
            ->andWhere('s.subscriptionStatus = :activeStatus OR s.activeUntil > :now')
            ->setParameter('activeStatus', SubscriptionStatus::ACTIVE)
            ->setParameter('now', Carbon::instance(new \DateTime())->addMinutes(1));


        if ($accountId) {
            $query->andWhere('p.account = :accountId');
            $query ->setParameter('accountId', $accountId);
        }
        $query->setParameter('setting', Profile::RECURRENT_APPLY_ON_DEADLINE);
        $query = $query->getQuery();
        $appliedCnt = 0;

        foreach (QueryIterator::create($query, 10000) as $profiles) {
            /** @var Profile $profile */
            foreach ($profiles as $profile) {
                $scholarships = $this->repository->findExpiringRecurringScholarships($profile->getAccount(), 1, true);

                $appliedCnt += count($scholarships);
                /** @var Scholarship $scholarship */
                foreach ($scholarships as $scholarship) {
                    try {
                        if ($scholarship->getApplicationType() === $scholarship::APPLICATION_TYPE_SUNRISE) {
                            $this->service->applySunriseRecurrentScholarship($profile->getAccount(), $scholarship);
                        } else {
                            $this->service->applyScholarship($profile->getAccount(), $scholarship);
                        }
                    }
                    catch(ApplicationService\Exception\ApplicationException $exception) {
                        $appliedCnt--;
                        \Log::error($exception);
                    }
                }
            }

            $this->em->flush();
            $this->em->clear();
        }

        $this->info("Applied total: $appliedCnt");
        $this->info("scholarships:recurrence-apply ended: " . date("Y-m-d h:i:s"));
    }
}
