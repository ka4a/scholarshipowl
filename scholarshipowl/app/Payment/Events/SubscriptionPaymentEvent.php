<?php namespace App\Payment\Events;

use App\Entity\Subscription;
use App\Entity\Transaction;

class SubscriptionPaymentEvent extends SubscriptionEvent
{
    /**
     * SubscriptionPaymentEvent constructor.
     *
     * @param Subscription $subscription
     * @param Transaction  $transaction
     */
    public function __construct(Subscription $subscription, Transaction $transaction)
    {
        parent::__construct($subscription, $transaction);
    }

    /**
     * Is this payment was activated subscription.
     *
     * @return bool
     */
    public function isActivationPayment()
    {
        return $this->getTransaction()->isSuccess() && $this->subscription->getRecurrentCount() === 1;
    }
}
