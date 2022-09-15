<?php
/**
 * Created by PhpStorm.
 * User: vadimkrutov
 * Date: 23/06/16
 * Time: 17:57
 */

namespace App\Listeners;
use App\Events\Account\AccountEvent;
use App\Events\Account\ChangeEmailEvent;
use App\Events\Account\CreateAccountEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Payment\Events\SubscriptionEvent;
use Illuminate\Auth\Events\Login as LoginEvent;
use App\Payment\Events\SubscriptionAddEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Entity\Account;

class ZendSubscriber implements ShouldQueue
{

    /**
     * Method that call service function to create new user on zendesk
     *
     * @param $event
     */
    public function createZendeskAccount(CreateAccountEvent $event)
    {
        \Zendesk::createUser($this->prepareAccount($event->getAccount()));
    }

    /**
     * Method that call service function to update existing user on zendesk
     *
     * @param $event
     */
    public function updateZendeskAccount(AccountEvent $event)
    {
        \Zendesk::updateUser($this->prepareAccount($event->getAccount()));
    }

    /**
     * Method that call service function to update existing user login history on zendesk
     *
     * @param $event
     */
    public function updateZendeskLoginHistory(LoginEvent $event)
    {
        if($event->user instanceof Account) {
            \Zendesk::updateUser($this->prepareAccount($event->user));
        }
    }

    /**
     * Method that call service to update user subscription information on zendesk
     *
     * @param SubscriptionEvent $subscriptionEvent
     */
    public function updateZendeskSubscription(SubscriptionEvent $subscriptionEvent)
    {
        \Zendesk::updateUser($this->prepareAccount($subscriptionEvent->getSubscription()->getAccount()));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(CreateAccountEvent::class,   static::class . '@createZendeskAccount');
        $events->listen(UpdateAccountEvent::class,   static::class . '@updateZendeskAccount');
        $events->listen(ChangeEmailEvent::class,     static::class . '@updateZendeskAccount');
        $events->listen(SubscriptionAddEvent::class, static::class . '@updateZendeskSubscription');
        $events->listen(LoginEvent::class,           static::class . '@updateZendeskLoginHistory');
    }

    /**
     * @param Account $account
     *
     * @return Account
     */
    private function prepareAccount(Account $account)
    {
        \EntityManager::refresh($account = \EntityManager::findById(Account::class, $account->getAccountId()));

        return $account;
    }
}
