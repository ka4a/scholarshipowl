<?php namespace App\Entity\Repository;

use App\Entity\Account;
use App\Entity\Country;
use App\Entity\Exception\EntityNotFound;
use App\Entity\Package;
use App\Entity\PaymentFsetHistory;
use App\Entity\PaymentMethod;
use App\Entity\State;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use Carbon\Carbon;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Class SubscriptionRepository
 *
 * @method Subscription findById(int $id)
 */
class SubscriptionRepository extends EntityRepository
{
    /**
     * Apply subscription active on query.
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return QueryBuilder
     */
    public static function activeSubscriptions(QueryBuilder $queryBuilder)
    {
        return $queryBuilder
            ->andWhere('s.subscriptionStatus = :activeStatus OR s.activeUntil > :now')
            ->setParameter('activeStatus', SubscriptionStatus::ACTIVE)
            ->setParameter('now', Carbon::instance(new \DateTime())->addMinutes(1));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param \DateTime    $date
     *
     * @return QueryBuilder
     */
    public static function expiredSubscriptions(QueryBuilder $queryBuilder, \DateTime $date)
    {
        $now = Carbon::instance($date);
        return $queryBuilder
            ->where('s.subscriptionStatus = :activeStatus')
            ->andWhere(
                "(s.endDate <> '0000-00-00 00:00:00' AND DATE(s.endDate) < DATE(:date))
                 OR
                 (s.renewalDate <> '0000-00-00 00:00:00' AND DATE(s.renewalDate) < DATE(:recurrentDate))
                 OR
                 (s.freeTrial = 1 AND DATE(s.freeTrialEndDate) < DATE(:freeTrialExpire))
                 ")
            ->setParameter('recurrentDate', $now->copy()->subDays(Subscription::EXPIRING_PERIOD))
            ->setParameter('freeTrialExpire', $now->copy()->subDays(Subscription::EXPIRING_PERIOD_FREE_TRIAL))
            ->setParameter('date', $now)
            ->setParameter('activeStatus', SubscriptionStatus::ACTIVE);
    }

    /**
     * @param  array $states
     * @param \DateTime $date
     *
     * @return array
     */
    public function upcomingSubscriptionsPerState($states, \DateTime $date)
    {
        $now = Carbon::instance($date);
        $queryList = [];
        $sqlTemplate = "(select '%s' as st, s.* from subscription as s where s.subscription_status_id = 1 and DATEDIFF(DATE(s.renewal_date), date('%s')) = %d ORDER BY subscription_id desc)";

        foreach ($states as $state => $interval){
            $queryList[] = sprintf($sqlTemplate, $state, $now, $interval);
        }

        $sql = implode(' UNION ', $queryList);

        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetchAll();

        $sortedList = array();
        foreach($res as $item)
        {
            $sortedList[$item['st']][] = $item;
        }

        return $sortedList;
    }

    /**
     * @param string             $externalId
     * @param PaymentMethod|int  $paymentMethod
     * @param bool               $throw
     *
     * @return Subscription
     */
    public function findByExternalId($externalId, $paymentMethod, $throw = true)
    {
        $criteria = ['externalId' => $externalId, 'paymentMethod' => $paymentMethod];
        if (null === ($subscription = $this->findOneBy($criteria)) && $throw) {
            throw new EntityNotFound(static::class, $criteria);
        }

        return $subscription;
    }

    /**
     * @param \DateTime|null $now
     *
     * @return array|Subscription[]
     */
    public function findExpiredSubscriptions(\DateTime $now = null)
    {
        return $this->queryExpiredSubscriptions($now ?: new \DateTime())->getResult();
    }

    /**
     * @param \DateTime $now
     *
     * @return \Doctrine\ORM\Query
     */
    public function queryExpiredSubscriptions(\DateTime $now)
    {
        return static::expiredSubscriptions($this->createQueryBuilder('s'), $now)->getQuery();
    }

    /**
     * @param Account $account
     *
     * @return Subscription|null
     */
    public function getTopPrioritySubscription(Account $account)
    {
        $cacheKey = sprintf(Subscription::CACHE_KEY_ACCOUNT_SUBSCRIPTION, $account->getAccountId());
        /** @var Subscription $subscription */
        $subscription = \Cache::get($cacheKey);

        if ($subscription instanceof Subscription
            && $subscription->getActiveUntil() < Carbon::instance(new \DateTime())->subMinutes(1)) {
            $subscription = null;
        }

        if ($subscription === null) {
            $subscription = static::activeSubscriptions($this->createQueryBuilder('s'))
                ->andWhere('s.account = :account')
                ->orderBy('s.priority', 'ASC')
                ->setParameter('account', $account)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            \Cache::put($cacheKey, $subscription ?? false, 60 * 24 * 7);
        }

        return $subscription;
    }

    /**
     * @param Account $account
     *
     * @return Subscription
     */
    public function getUnlimitedScholarshipsSubscription(Account $account)
    {
        return static::activeSubscriptions($this->createQueryBuilder('s'))
            ->andWhere('s.account = :account AND s.isScholarshipsUnlimited = true')
            ->orderBy('s.priority')
            ->setParameter('account', $account)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Account $account
     *
     * @return Subscription
     */
    public function getLowestPrioritySubscriptionWithCredit(Account $account)
    {
        return static::activeSubscriptions($this->createQueryBuilder('s'))
            ->andWhere('s.account = :account AND s.credit > 0')
            ->orderBy('s.priority', 'DESC')
            ->setParameter('account', $account)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Account $account
     *
     * @return array|Subscription[]
     */
    public function findFreemiumActiveSubscriptions(Account $account)
    {
        return static::activeSubscriptions($this->createQueryBuilder('s'))
            ->select('s.isFreemium')
            ->andWhere('s.account = :account')
            ->andWhere('s.isFreemium = 1')
            ->setParameter('account', $account)
            ->orderBy('s.priority', 'ASC')
            ->getQuery()->getResult();
    }

    /**
     * @param array|int[]\Account[] $accounts
     *
     * @return \App\Entity\Subscription[]|array
     */
    public function findActiveSubscriptions(array $accounts)
    {
        /** @var Subscription[] $result */
        $result = [];

        foreach ($accounts as $account) {
            $id = ($account instanceof Account) ? $account->getAccountId() : $account;
            $result[$id] = null;
        };

        $subscriptions = static::activeSubscriptions($this->createQueryBuilder('s'))
            ->andWhere('s.isScholarshipsUnlimited = true OR s.credit > 0')
            ->andWhere('s.account IN (:ids)')
            ->setParameter('ids', $accounts)
            ->getQuery()
            ->getResult();

        return $this->orderByPriority($subscriptions, $result);
    }

    /**
     * @param array|int[] $accounts
     *
     * @return array|Subscription[]
     */
    public function getLastSubscriptions(array $accounts)
    {
        /** @var Subscription[] $result */
        $result = [];

        foreach ($accounts as $account) {
            $id = ($account instanceof Account) ? $account->getAccountId() : $account;
            $result[$id] = null;
        };

        $subscriptions = $this->createQueryBuilder('s')
            ->where('s.account IN (:ids)')
            ->setParameter('ids', $accounts)
            ->getQuery()
            ->getResult();

        return $this->orderByPriority($subscriptions, $result);
    }

    /**
     * @param array|Subscription[]  $subscriptions
     * @param array                 $result
     *
     * @return array|Subscription[]
     */
    protected function orderByPriority(array $subscriptions, array $result = [])
    {
        /** @var Subscription $subscription */
        foreach ($subscriptions as $subscription) {
            $accountId = $subscription->getAccount()->getAccountId();

            if (!isset($result[$accountId])) {
                $result[$accountId] = $subscription;
            } else if ($subscription->getIsScholarshipsUnlimited()) {
                $result[$accountId] = $subscription;
            } else if (
                !$result[$accountId]->getIsScholarshipsUnlimited() &&
                $result[$accountId]->getPriority() <= $subscription->getPriority()
            ) {
                $result[$accountId] = $subscription;
            }
        }

        return $result;
    }

    /**
     * Update subscription credit by freemium credits prop
     *
     * @param \DateTime $date
     *
     * @return int
     */
    public function updateFreemiumSubscription(\DateTime $date)
    {
        return $this->_em->createQueryBuilder()
            ->update(Subscription::class, 's')
            ->set('s.credit', 's.freemiumCredits')
            ->set('s.freemiumCreditsUpdatedDate', ':now')
            ->where('s.isFreemium = 1')
            ->setParameter('now', $date)
            ->andWhere('s.credit != s.freemiumCredits')
            ->andWhere("DATE(s.freemiumCreditsUpdatedDate) < CASE s.freemiumRecurrencePeriod
                WHEN 'day'
                  THEN DATESUB(:now, s.freemiumRecurrenceValue, 'DAY') 
                WHEN 'week'
                  THEN DATESUB(:now, s.freemiumRecurrenceValue, 'WEEK') 
                WHEN 'month'
                  THEN DATESUB(:now, s.freemiumRecurrenceValue, 'MONTH')
                WHEN 'year'
                  THEN DATESUB(:now, s.freemiumRecurrenceValue, 'YEAR') 
                ELSE 1
                END")
            ->andWhere("s.freemiumRecurrenceValue != 'never'")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getCancelledActiveUntilSubscriptions()
    {
        return $this->createQueryBuilder('s')
            ->where('s.subscriptionStatus = :activeStatus')
            ->andWhere('date(s.activeUntil) > date(:yesterday)')
            ->andWhere('date(s.activeUntil) <  date(:now)')
            ->setParameter('activeStatus', SubscriptionStatus::CANCELED)
            ->setParameter('yesterday', Carbon::yesterday())
            ->setParameter('now', Carbon::now())
            ->getQuery();
    }

    /**
     * @param \DateTime $date
     *
     * @return \Doctrine\ORM\Query
     */
    public function queryAccountIdsByFreemiumRenewalDate(\DateTime $date)
    {
        return static::activeSubscriptions($this->createQueryBuilder('s'))
            ->select(['IDENTITY(s.account)'])
            ->andWhere('s.freemiumCreditsUpdatedDate = :date')
            ->setParameter('date', $date)
            ->getQuery();
    }

    /**
     * @param \DateTime $date
     *
     * @return \Doctrine\ORM\Query
     */
    public function queryAccountIdsByFreemiumStartDate(\DateTime $date)
    {
        return static::activeSubscriptions($this->createQueryBuilder('s'))
            ->andWhere('s.isFreemium = 1')
            ->andWhere('DATE(s.startDate) = DATE(:date)')
            ->setParameter('date', $date)
            ->getQuery();
    }

    /**
     * @param $paymentMethod
     * @param $from
     * @param $to
     *
     * @return array
     */
    public function getActiveBuscriptionByPaymentMethod($paymentMethod, $from, $to)
    {
        $subscriptionList = $this->createQueryBuilder('s')
            ->where('s.paymentMethod = :paymentMethod')
            ->andWhere('s.subscriptionStatus = :activeStatus')
            ->andWhere('s.renewalDate BETWEEN :monday AND :sunday')
            ->setParameter('monday', $from)
            ->setParameter('sunday', $to)
            ->setParameter('activeStatus', SubscriptionStatus::ACTIVE)
            ->setParameter('paymentMethod', $paymentMethod)
            ->getQuery()
            ->getResult();

        return $subscriptionList;
    }
}
