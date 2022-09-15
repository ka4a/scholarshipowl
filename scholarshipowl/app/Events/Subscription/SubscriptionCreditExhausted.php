<?php

namespace App\Events\Subscription;

use App\Entity\Subscription;
use App\Events\Event;

class SubscriptionCreditExhausted extends Event
{
    /**
     * Subscription
     * @var $subscription
     */
    public $subscription;

    /**
     * SubscriptionCreditExhausted constructor
     *
     * @param Subscription $subscription
     */
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
}
