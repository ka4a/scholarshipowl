<?php namespace App\Payment;

use App\Entity\Subscription;

interface IRemoteManager
{
    /**
     * Cancel subscription but not cancel current cycle
     *
     * @param Subscription $subscription
     *
     * @return $this
     */
    public function cancelSubscription(Subscription $subscription);

    /**
     * Cancel subscription imidiately.
     *
     * @param Subscription $subscription
     *
     * @return $this
     */
    public function terminateSubscription(Subscription $subscription);
}
