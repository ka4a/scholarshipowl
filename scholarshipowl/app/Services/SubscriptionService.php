<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Subscription;
use App\Events\Subscription\CancelledActiveUntilExhausted;
use App\Events\Subscription\FreemiumCreditsRenewal;
use App\Http\Controllers\Admin\WebsiteController;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use ScholarshipOwl\Data\Service\Website\SettingValueNotValidException;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class SubscriptionService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PaymentManager
     */
    protected $pm;

    /**
     * @var Command
     */
    protected $command;

    /**
     * @var SubscriptionRepository $repository
     */
    protected $repository;

    /**
     * SubscriptionService constructor.
     *
     * @param EntityManager  $em
     * @param PaymentManager $pm
     */

    public function __construct(EntityManager $em, PaymentManager $pm)
    {
        $this->em = $em;
        $this->pm = $pm;
        $this->repository = $em->getRepository(Subscription::class);
    }

    /**
     * @param Account     $account
     *
     * @return null|Subscription
     */
    public function getApplicationSubscription(Account $account)
    {
        if ($subscription = $this->repository->getUnlimitedScholarshipsSubscription($account)) {
            return $subscription;
        } else if ($creditSubscription = $this->repository->getLowestPrioritySubscriptionWithCredit($account)) {
            return $creditSubscription;
        }

        return null;
    }

    /**
     * @param null $date
     */
    /**
     * @param null $date
     * @param null|Command $command
     */
    public function maintain($date = null, $command = null, $fset = null){
        $this->command = $command;
        $date = $date ?: new \DateTime();
        $this->maintainFreemeium($date);
        $this->expireSubscriptions($date);
        $this->maintainCancelledActiveUntil($date);
    }

    /**
     * Subscription renewal frequency in days
     *
     * @param Subscription $subscription
     *
     * @return int
     */
    public static function calcFrequencyDays(Subscription $subscription)
    {
        $frequencyDays = 0;

        switch ($subscription->getExpirationPeriodType()) {
            case Package::EXPIRATION_PERIOD_TYPE_DAY:
                $frequencyDays = $subscription->getExpirationPeriodValue();
                break;
            case Package::EXPIRATION_PERIOD_TYPE_WEEK:
                $frequencyDays = $subscription->getExpirationPeriodValue() * 7;
                break;
            case Package::EXPIRATION_PERIOD_TYPE_MONTH:
                $frequencyDays = $subscription->getExpirationPeriodValue() * 30;
                break;
            case Package::EXPIRATION_PERIOD_TYPE_YEAR:
                $frequencyDays = $subscription->getExpirationPeriodValue()
                    * 365;
                break;
        }

        return $frequencyDays;
    }

    /**
     * Maintaining Freemium subscription. Accrue subscription credits per freemium period limit
     * @param \DateTime $date
     */
    protected function maintainFreemeium(\DateTime $date)
    {
        if ($this->command) {
            $this->command->info(sprintf('[%s] Freemium update %s', date('c'), $date->format('m-d-Y')));
        }

        if ($updated = $this->repository->updateFreemiumSubscription($date)) {
            \Event::dispatch(new FreemiumCreditsRenewal($date));
        }

        if ($this->command) {
            $this->command->info(sprintf('[%s] Freemium updated: %s', date('c'), $updated));
        }
    }

    /**
     * Load expired subscriptions and apply closure function
     * @param \DateTime $now
     */
    protected function expireSubscriptions(\DateTime $now)
    {
        if ($this->command) {
            $this->command->info(sprintf('[%s] Expire subscriptions from %s', date('c'), $now->format('m-d-Y')));
        }

        $count = 0;
        $query = $this->repository->queryExpiredSubscriptions($now)->setMaxResults(10000);
        if ($this->command) {
            $this->command->info(sprintf('[%s] Expire subscriptions count %s', date('c'),
                QueryIterator::create($query)->count()
            ));
        }

        while (!empty($expiredSubscriptions = $query->getResult())) {
            if ($this->command) {
                $this->command->info(sprintf(
                    '[%s] Processing chunk of %s subscriptions', date('c'), count($expiredSubscriptions)
                ));
            }

            /** @var Subscription $subscription */
            foreach ($expiredSubscriptions as $subscription) {
                $this->pm->expireSubscription($subscription);
                $count++;
            }

            $this->em->flush();
            $this->em->clear();
        }

        if ($this->command) {
            $this->command->info(sprintf('[%s] Subscriptions expired: %s', date('c'), $count));
        }
    }


    /**
     * @param \DateTime $now
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function maintainCancelledActiveUntil(\DateTime $now)
    {
        if ($this->command) {
            $this->command->info(sprintf('[%s] Maintaining canceled activeUntil subscription from %s', date('c'), $now->format('m-d-Y')));
        }

        $count = 0;
        $query = $this->repository->getCancelledActiveUntilSubscriptions()->setMaxResults(10000);


        if ($this->command) {
            $this->command->info(sprintf('[%s] Count %s', date('c'),
                QueryIterator::create($query)->count()
            ));
        }
        $expiredSubscriptions = $query->getResult();

        /** @var Subscription $subscription */
        foreach ($expiredSubscriptions as $subscription) {
            \Event::dispatch(new CancelledActiveUntilExhausted($subscription));
            $count++;
        }

        if ($this->command) {
            $this->command->info(sprintf('[%s] Canceled activeUntil subscriptions maintained', date('c')));
        }
    }
}
