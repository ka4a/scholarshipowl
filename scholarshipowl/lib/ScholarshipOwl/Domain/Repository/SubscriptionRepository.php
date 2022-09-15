<?php

namespace ScholarshipOwl\Domain\Repository;

use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Domain\Subscription;

class SubscriptionRepository
{

    /**
     * @param int $subscriptionId
     * @return null|Subscription
     */
    public function findById($subscriptionId)
    {
        $subscription = null;

        if ($subscriptionId) {
            $rawData = \DB::table(IDDL::TABLE_SUBSCRIPTION)
                ->where('subscription_id', '=', $subscriptionId)
                ->first();

            $subscription = !empty($rawData) ? new Subscription((array) $rawData) : null;
        }

        return $subscription;
    }

    /**
     * @param $accountId
     * @return Subscription[]
     */
    public function findByAccountId($accountId)
    {
        $subscriptions = array();
        $rawSubscriptions = \DB::table(IDDL::TABLE_SUBSCRIPTION)->where('account_id', $accountId)->get();

        foreach ($rawSubscriptions as $rawSubscription) {
            $subscription = new Subscription((array) $rawSubscription);
            $subscriptions[$subscription->getSubscriptionId()] = $subscription;
        }

        return $subscriptions;
    }

    /**
     * @param string $externalId
     * @param int $paymentMethodId
     * @return null|Subscription
     */
    public function findByExternalId($externalId, $paymentMethodId)
    {
        $subscription = null;

        if ($externalId && $paymentMethodId) {
            $rawData = \DB::table(IDDL::TABLE_SUBSCRIPTION)
                ->where('external_id', '=', $externalId)
                ->where('payment_method_id', '=', $paymentMethodId)
                ->first();

            $subscription = !empty($rawData) ? new Subscription((array) $rawData) : null;
        }

        return $subscription;
    }

}
