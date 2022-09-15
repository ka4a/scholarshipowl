<?php namespace App\Listeners;

use App\Payment\Events\SubscriptionAddEvent;
use App\Entity\SubscriptionAcquiredType;
use Illuminate\Events\Dispatcher;

class FacebookPixels
{

    /**
     * @param SubscriptionEvent $event
     */
    public function membershipAdded(SubscriptionAddEvent $event)
    {
        if($event->getSubscription()->getSubscriptionAcquiredType()->is(SubscriptionAcquiredType::PURCHASED)) {
            #For facebook pixels
            \Session::put('FACEBOOK_ACCOUNT_MEMBERSHIP_PURCHASED', $event->getSubscription()->getPrice());
        }
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(SubscriptionAddEvent::class, static::class.'@membershipAdded');
    }

}
