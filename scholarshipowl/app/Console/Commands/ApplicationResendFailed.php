<?php namespace App\Console\Commands;

use App\Entity\Application;
use App\Entity\ApplicationFailedTries;
use App\Entity\Counter;
use App\Entity\Repository\ApplicationRepository;
use App\Services\ApplicationService;

use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class ApplicationResendFailed extends Command
{
    const RESENT_TRIES_NUMBER = 3;
    const RESENT_PERIOD = 4;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'application:resend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Try to send failed application';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ApplicationRepository
     */
    protected $applicationRepository;

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * ApplicationSend constructor.
     *
     * @param EntityManager      $em
     * @param ApplicationService $applicationService
     */
    public function __construct(
        EntityManager $em,
        ApplicationService $applicationService
    ) {
        $this->em = $em;
        $this->applicationService = $applicationService;
        $this->applicationRepository = $em->getRepository(Application::class);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Applications re submit started: " . date("Y-m-d h:i:s"));
        $triesRepo = $this->em->getRepository(ApplicationFailedTries::class);

        // since application table has compound primary key we can't use offset by id.
        // doing offset like it is makes iterator to shift offset without taking in account updated rows,
        // so iterator moves pointer more then needed exactly for the count of updated rows (ApplicationFailedTries),
        // and there is nothing we can do, but these items will be updated on the next command run
        $iterator
            = new QueryIterator($this->applicationRepository->getFailedApplicationQuery(self::RESENT_PERIOD), 1000);

        /** @var Application $application */
        foreach ($iterator as $applications) {
            foreach ($applications as $application) {
                try {
                    $accountId = $application->getAccount()->getAccountId();
                    $scholarship = $application->getScholarship();
                    $scholarshipId = $scholarship->getScholarshipId();

                    /**
                     * @var ApplicationFailedTries $tries
                     */
                    $tries = $triesRepo->findOneBy([
                        'accountId'     => $accountId,
                        'scholarshipId' => $scholarshipId
                    ]);

                    if ($tries) {
                        $tries->decreaseTriesNumber();
                        $tries->setLastUpdate(Carbon::now());
                    } else {
                        $tries = new ApplicationFailedTries($accountId, $scholarshipId, self::RESENT_TRIES_NUMBER);
                    }

                    if (!$scholarship->isPublished() || !$scholarship->isActive() || $scholarship->isExpired()) {
                        $tries->setTries(0);

                        $this->em->persist($tries);
                        $this->em->flush($tries);
                        $this->em->detach($tries);

                        $this->info(
                            sprintf(
                                'Skip resend of application for scholarship [ %s ] Account: [ %s ]',
                                 $scholarshipId, $accountId
                            )
                        );
                    } else {
                        $this->em->persist($tries);
                        $this->em->flush($tries);
                        $this->em->detach($tries);

                        $this->info(
                            sprintf(
                                'Try to resend online application for scholarship [ %s ] Account: [ %s ]',
                                 $scholarshipId, $accountId
                            )
                        );

                        $this->applicationService->sendApplication($application);
                    }
                } catch (\Exception $e) {
                    $this->info("Application Send Error: " . $e->getMessage());
                    \Log::error($e);
                }
            }
        }

        $this->updateApplicationsCount();
        $this->info("Applications resend Ended: " . date("Y-m-d h:i:s"));
    }

    /**
     * Update applications counter
     */
    protected function updateApplicationsCount()
    {
        // dispatch(new ApplicationCountJob());
        $count = $this->applicationRepository
            ->getSuccessfulApplicationsQuery()
            ->getQuery()
            ->getSingleScalarResult();

        $counter = Counter::findByName("application");
        $counter->setCount($count);

        \Cache::tags(["counter"])->put("application", $count, 60*60);
        $this->em->flush($counter);
    }
}
