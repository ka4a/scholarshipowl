<?php namespace App\Console\Commands;

use App\Channels\OneSignalChannel;
use App\Entity\Account;
use App\Entity\Repository\AccountRepository;
use App\Notifications\LongTimeNotSeeNotification;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Illuminate\Console\Command;
use Carbon\Carbon;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class AccountNotificationDaily extends Command
{
    const NOT_SEE_DAYS = 7;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:notification:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command that\'s sending all daily notifications.';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AccountRepository
     */
    protected $accounts;

    /**
     * @var OneSignalChannel
     */
    protected $onesignal;

    /**
     * Create a new command instance.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->accounts = $em->getRepository(Account::class);
        $this->onesignal = app(OneSignalChannel::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->longTimeNotSeeNotification();
    }

    /**
     * Send long time not see notifications.
     */
    protected function longTimeNotSeeNotification()
    {
        $this->info('Long time not see notifications.');

        $query = $this->accounts->createQueryBuilder('a')
            ->select(['a.accountId'])
            ->where('a.lastActionAt < :date')
            ->setParameter('date', Carbon::now()->subDay(static::NOT_SEE_DAYS))
            ->getQuery();

        $part = 1;
        foreach (QueryIterator::create($query, 10000, 0, Query::HYDRATE_SCALAR) as $accounts) {
            $this->info(sprintf('Part %d started %s', $part, date("Y-m-d h:i:s")));

            $this->onesignal->send(
                array_map('current', $accounts),
                new LongTimeNotSeeNotification()
            );

            $this->em->flush();
            $this->em->clear();

            $this->info(sprintf(
                'Part %d finished %s (memory: %s/%s)',
                $part, date("Y-m-d h:i:s"), format_memory(), format_bytes(memory_get_peak_usage())
            ));
            $part++;
        }

        $this->info('Long time not see notifications finished.');
    }
}
