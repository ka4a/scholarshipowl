<?php namespace App\Listeners;

use App\Entity\Profile;
use App\Events\Subscription\CancelledActiveUntilExhausted;
use App\Facades\EntityManager;
use App\Payment\Events\SubscriptionAddEvent;
use App\Entity\SubscriptionAcquiredType;
use App\Payment\Events\SubscriptionExpiredEvent;
use Illuminate\Events\Dispatcher;

class ProfileSubscribe
{

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(SubscriptionAddEvent::class, static::class.'@onSubscriptionAddEvent');
        $events->listen(SubscriptionExpiredEvent::class, static::class.'@onSubscriptionExpiredEvent');
        $events->listen(CancelledActiveUntilExhausted::class, static::class.'@onCancelledActiveUntilExhausted');
    }

    /**
     * @param SubscriptionAddEvent $event
     * @throws \Doctrine\DBAL\DBALException
     */
    public function onSubscriptionAddEvent(SubscriptionAddEvent $event)
    {
        /**
         * @var Profile $profile
         */
        $profile = $event->getAccount()->getProfile();
        if($profile->getRecurringApplication() === Profile::RECURRENT_APPLY_DISABLED) {
            $profile->setRecurringApplication(Profile::RECURRENT_APPLY_ON_DEADLINE);
            EntityManager::flush();
        }
    }

    /**
     * @param SubscriptionExpiredEvent $event
     * @throws \Doctrine\DBAL\DBALException
     */
    public function onSubscriptionExpiredEvent(SubscriptionExpiredEvent $event)
    {
        /**
         * @var Profile $profile
         */
        $profile = $event->getAccount()->getProfile();
        if($profile->getRecurringApplication() !== Profile::RECURRENT_APPLY_DISABLED) {
            $profile->setRecurringApplication(Profile::RECURRENT_APPLY_DISABLED);
            EntityManager::flush();
        }
    }

    /**
     * @param CancelledActiveUntilExhausted $event
     * @throws \Doctrine\DBAL\DBALException
     */
    public function onCancelledActiveUntilExhausted(CancelledActiveUntilExhausted $event)
    {
        /**
         * @var Profile $profile
         */
        $profile = $event->getSubscription()->getAccount()->getProfile();
        if($profile->getRecurringApplication() !== Profile::RECURRENT_APPLY_DISABLED) {
            $profile->setRecurringApplication(Profile::RECURRENT_APPLY_DISABLED);
            EntityManager::flush();
        }
    }

}
