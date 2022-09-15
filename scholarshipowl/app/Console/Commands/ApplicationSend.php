<?php namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationStatus;
use App\Entity\Counter;
use App\Entity\Repository\ApplicationRepository;
use App\Entity\Scholarship;
use App\Jobs\ApplicationCountJob;
use App\Services\ApplicationService;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class ApplicationSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'application:send {account?} {scholarship?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send applications';

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
    public function __construct(EntityManager $em, ApplicationService $applicationService)
    {
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
        $this->info("ApplicationsSend Started: " . date("Y-m-d h:i:s"));

        $account = $this->argument('account');
        $scholarship = $this->argument('scholarship');

        $iterator = new QueryIterator(
            $this->applicationRepository->getApplicationForSendingQuery(
                $account ? \EntityManager::findById(Account::class, $account) : null,
                $scholarship ?
                    \EntityManager::getRepository(Scholarship::class)->findOneBy(
                        ['scholarshipId' => $scholarship]
                    ) : null
            )
        );

        /** @var Application $application */
        foreach ($iterator as $applications) {
            foreach ($applications as $application) {
                try {
                    $this->info(sprintf(
                        'Sending online scholarship application: %s (Account: %s)',
                        $application->getScholarship()->getScholarshipId(),
                        $application->getAccount()->getAccountId()
                    ));

                    $this->applicationService->sendApplication($application);
                    $this->info('Application sent');

                } catch (\Exception $e) {
                    $this->info("Application Send Error: " . $e->getMessage());
                    \Log::error($e);
                }
            }
        }

        $this->updateApplicationsCount();
        $this->info("ApplicationsSend Ended: " . date("Y-m-d h:i:s"));
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
