<?php namespace App\Console\Commands;

use App\Doctrine\QueryIterator;
use App\Entities\ApplicationWinner;
use App\Events\ApplicationWinnerDisqualified;
use App\Repositories\ApplicationWinnersRepository;
use App\Services\MauticService;
use App\Services\ScholarshipManager;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\RestRepository;

class ScholarshipWinnerNotification extends Command
{
    /**
     * Config name of first email notification.
     */
    const FIRST_NOTIFICATION = 'winnerNotification1';

    /**
     * Config name of second email notification.
     */
    const SECOND_NOTIFICATION = 'winnerNotification2';

    /**
     * Config name for winner disqualification email.
     */
    const DISQUALIFICATION = 'winnerDisqualification';

    /**
     * Days to wait for second notification.
     */
    const WAIT_HOURS_FOR_SECOND_EMAIL = 48;

    /**
     * Days to wait for second notification.
     */
    const WAIT_HOURS_FOR_DISQUALIFICATION = 72;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scholarship:winner:notification
        {--ids=all : Comma separated scholarship ids}
        {--date=now : Date to check expire date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send winner notification depends on configuration.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipManager
     */
    protected $sm;

    /**
     * @var MauticService
     */
    protected $mautic;

    /**
     * Create a new command instance.
     *
     * @param EntityManager $em
     * @param ScholarshipManager $sm
     * @param MauticService $mautic
     */
    public function __construct(EntityManager $em, ScholarshipManager $sm, MauticService $mautic)
    {
        $this->em = $em;
        $this->sm = $sm;
        $this->mautic = $mautic;
        parent::__construct();
    }

    /**
     * @return ApplicationWinnersRepository
     */
    public function winners()
    {
        return $this->em->getRepository(ApplicationWinner::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = new \DateTime($this->option('date'));
        $ids = $this->option('ids') !== 'all' ? explode(',', $this->option('ids')) : [];

        $this->winnerDisqualification($now, $ids);
        $this->notifySecondWinnerNotification($now, $ids);
        $this->notifyFirstWinnerNotification($ids);
    }

    /**
     * Send first notification to winner.
     * @param array $ids
     */
    protected function notifyFirstWinnerNotification($ids = [])
    {
        $query = $this->winners()->createQueryBuilder('w')
            ->addSelect('a')
            ->join('w.application', 'a')
            ->where('w.paused = false AND w.disqualifiedAt IS NULL AND w.filled = false')
            ->andWhere('w.notified = 0');

        if (!empty($ids)) {
            $query->andWhere('a.scholarship IN (:ids)')->setParameter('ids', $ids);
        }

        /** @var ApplicationWinner $winner */
        foreach (QueryIterator::create($query->getQuery()) as $winners) {
            foreach ($winners as $winner) {
                $this->mautic->notifyWinner($winner->getApplication(), static::FIRST_NOTIFICATION);
                $this->em->flush($winner->incrementNotified());
            }
        }
    }

    /**
     * Second notification for a scholarship winner.
     * @param \DateTime $now
     * @param array $ids
     */
    protected function notifySecondWinnerNotification(\DateTime $now, $ids = [])
    {
        $query = $this->winners()->createQueryBuilder('w')
            ->addSelect('a')
            ->join('w.application', 'a')
            ->where('w.paused = false AND w.disqualifiedAt IS NULL AND w.filled = false')
            ->andWhere('w.createdAt < :checkDate AND w.notified = 1')
            ->setParameter('checkDate', Carbon::instance($now)->subHour(static::WAIT_HOURS_FOR_SECOND_EMAIL));

        if (!empty($ids)) {
            $query->andWhere('a.scholarship IN (:ids)')->setParameter('ids', $ids);
        }

        /** @var ApplicationWinner $winner */
        foreach (QueryIterator::create($query->getQuery()) as $winners) {
            foreach ($winners as $winner) {
                $this->mautic->notifyWinner($winner->getApplication(), static::SECOND_NOTIFICATION);
                $this->em->flush($winner->incrementNotified());
            }
        }
    }

    /**
     * Disqualify winner after some days.
     * @param \DateTime $now
     * @param array $ids
     */
    protected function winnerDisqualification(\DateTime $now, $ids = [])
    {
        $query = $this->winners()->createQueryBuilder('w')
            ->addSelect(['a', 's'])
            ->join('w.application', 'a')
            ->join('a.scholarship', 's')
            ->where('w.paused = false AND w.disqualifiedAt IS NULL AND w.filled = false')
            ->andWhere('w.createdAt < :checkDate')
            ->setParameter('checkDate', Carbon::instance($now)->subHour(static::WAIT_HOURS_FOR_DISQUALIFICATION));

        if (!empty($ids)) {
            $query->andWhere('s.id IN (:ids)')->setParameter('ids', $ids);
        }

        /** @var ApplicationWinner $winner */
        foreach (QueryIterator::create($query->getQuery()) as $winners) {
            foreach ($winners as $winner) {
                $this->em->flush($winner->setDisqualifiedAt(new \DateTime()));

                ApplicationWinnerDisqualified::dispatch($winner);

                $application = $winner->getApplication();
                $scholarship = $application->getScholarship();

                $this->mautic->notifyWinner($application, static::DISQUALIFICATION);

                if ($this->winners()->countWinners($scholarship) < $scholarship->getAwards()) {
                    $this->sm->chooseWinners($winner->getApplication()->getScholarship(), 1);
                }
            }
        }
    }
}
