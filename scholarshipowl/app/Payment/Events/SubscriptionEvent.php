<?php namespace App\Payment\Events;

use App\Events\Event;
use App\Entity\Subscription;
use App\Entity\Transaction;

class SubscriptionEvent extends Event
{
    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * SubscriptionAddEvent constructor.
     *
     * @param Subscription     $subscription
     * @param Transaction|null $transaction
     */
    public function __construct(Subscription $subscription, Transaction $transaction = null)
    {
        $this->subscription = $subscription;
        $this->transaction = $transaction;
    }

    /**
     * @return Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @return \App\Entity\Account
     */
    public function getAccount()
    {
        return $this->getSubscription()->getAccount();
    }

    /**
     * @return Transaction|null
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
