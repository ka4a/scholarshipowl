<?php

namespace App\Events\Subscription;

use App\Entity\Subscription;
use App\Events\Event;

class CancelledActiveUntilExhausted extends Event
{

    /**
     * @var Subscription
     */
    protected $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
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
        return $this->subscription->getAccount();
    }
}
