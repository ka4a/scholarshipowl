<?php namespace App\Listeners;

use App\Entity\Account;
use App\Entity\Subscription;
use App\Events\Account\AccountEvent;
use App\Events\Account\ChangeEmailEvent;
use App\Events\Account\CreateAccountEvent;
use App\Events\Account\DeleteAccountEvent;
use App\Events\Account\DeleteTestAccountEvent;
use App\Events\Account\ElbCacheAccountEvent;
use App\Events\Account\ElbCachePurgedOnAccountUpdate;
use App\Events\Account\ElbCachePurgedOnAccountUpdateEvent;
use App\Events\Account\ElbCacheUpdatedOnAccountUpdate;
use App\Events\Account\ElbCacheUpdatedOnAccountUpdateEvent;
use App\Events\Account\MailboxReadEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Events\Email\NewEmailEvent;
use App\Events\Subscription\CancelledActiveUntilExhausted;
use App\Payment\Events\SubscriptionAddEvent;
use App\Payment\Events\SubscriptionCancelledEvent;
use App\Payment\Events\SubscriptionExpiredEvent;
use App\Payment\Events\SubscriptionPaymentEvent;
use App\Services\PubSub\AccountService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class AccountPubSubPublisher implements ShouldQueue
{

    /**
     * @var AccountService
     */
    protected $service;

    /**
     * @return AccountService
     */
    public function getService()
    {
        if ($this->service === null) {
            $this->service = app(AccountService::class);
        }

        return $this->service;
    }

    /**
     * @param AccountService $service
     *
     * @return $this
     */
    public function setService(AccountService $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(CreateAccountEvent::class, static::class.'@onCreateAccount');
        $events->listen(ElbCacheUpdatedOnAccountUpdateEvent::class, static::class.'@onUpdateAccount');
        $events->listen(ElbCachePurgedOnAccountUpdateEvent::class, static::class.'@onUpdateAccount');
        $events->listen(DeleteAccountEvent::class, static::class.'@onDeleteAccount');
        $events->listen(DeleteTestAccountEvent::class, static::class.'@onDeleteTestAccount');
        $events->listen(ChangeEmailEvent::class, static::class.'@onChangeEmail');

        $events->listen(SubscriptionAddEvent::class, static::class.'@onSubscriptionAddEvent');
        $events->listen(SubscriptionExpiredEvent::class, static::class.'@onSubscriptionExpire');
        $events->listen(SubscriptionCancelledEvent::class, static::class.'@onSubscriptionCancelled');
        $events->listen(CancelledActiveUntilExhausted::class, static::class.'@onCancelledActiveUntilExhausted');

        $events->listen(MailboxReadEvent::class, static::class . '@onMailboxRead');
        $events->listen(NewEmailEvent::class, static::class . '@onNewEmail');
    }

    /**
     * @param AccountEvent $event
     */
    public function onCreateAccount(AccountEvent $event)
    {
        $account = $event->getAccount();

        if ($account->getDomain()->getId() === 1) {
            $this->getService()->addOrUpdateAccount($account, false);
        }
    }

    /**
     * @param AccountEvent $event
     */
    public function onUpdateAccount(ElbCacheAccountEvent $event)
    {
        $accountEvent = $event->getAccountEvent();
        $account = $accountEvent->getAccount();
        if ($account->getDomain()->getId() === 1) {
            $this->getService()->addOrUpdateAccount($account);
        }
    }

    /**
     * @param AccountEvent $event
     */
    public function onDeleteAccount(AccountEvent $event)
    {
        /**
         * @var Account $account
         */
        $account = $event->getAccount();
        $this->getService()->deleteAccount($account->getAccountId());
    }

    /**
     * @param AccountEvent $event
     */
    public function onDeleteTestAccount(DeleteTestAccountEvent $event)
    {
        $this->getService()->deleteAccount($event->getAccountId());
    }

    /**
     * @param ChangeEmailEvent $event
     */
    public function onChangeEmail(ChangeEmailEvent $event)
    {
        $account = $event->getAccount();
        $prevEmail = $event->getPrevEmail();

        if ($account->getDomain()->getId() === 1) {
             $this->getService()->updateAccount($account, [AccountService::FIELD_EMAIL]);
        }
    }

    /**
     * @param SubscriptionAddEvent $event
     */
    public function onSubscriptionAddEvent(SubscriptionAddEvent $event)
    {
        $this->updateSubscriptionRelatedMergeTags($event);
    }

    /**
     * @param SubscriptionExpiredEvent $event
     */
    public function onSubscriptionCancelled(SubscriptionCancelledEvent $event)
    {
        $this->updateSubscriptionRelatedMergeTags($event);
    }

    /**
     * @param CancelledActiveUntilExhausted $event
     */
    public function onCancelledActiveUntilExhausted(CancelledActiveUntilExhausted $event)
    {
        $this->updateSubscriptionRelatedMergeTags($event);
    }

    /**
     * @param SubscriptionExpiredEvent $event
     */
    public function onSubscriptionExpire(SubscriptionExpiredEvent $event)
    {
        $this->updateSubscriptionRelatedMergeTags($event);
    }

    /**
     * @param  SubscriptionAddEvent|SubscriptionExpiredEvent|SubscriptionCancelledEvent|CancelledActiveUntilExhausted $event
     */
    protected function updateSubscriptionRelatedMergeTags($event)
    {
        /**
         * @var Subscription $subscription
         */
        $subscription = $event->getSubscription();

        /**
         * @var Account $account
         */
        $account = $event->getAccount();
        // we need to fetch account because after unserilize (was serialized when queued) it has no profile linked
        $account = \EntityManager::getRepository(\App\Entity\Account::class)->find($account->getAccountId());

        $targetFields = [
            AccountService::FIELD_SUBSCRIPTION_IS_PAID,
            AccountService::FIELD_PACKAGE_RENEWAL_DATE,
            AccountService::FIELD_PACKAGE,
            AccountService::FIELD_MEMBERSHIP_STATUS,
        ];

        if ($account->getDomain()->getId() === 1) {
             $this->getService()->updateAccount($account, $targetFields);
        }
    }

    /**
     * @param MailboxReadEvent $event
     */
    public function onMailboxRead(MailboxReadEvent $event)
    {
        /**
         * @var Account $account
         */
        $account = $event->getAccount();

        $targetFields = [
            AccountService::FIELD_UNREAD_MESSAGES_COUNT,
        ];

        if ($account->getDomain()->getId() === 1) {
             $this->getService()->updateAccount($account, $targetFields);
        }
    }

    /**
     * @param NewEmailEvent $event
     */
    public function onNewEmail(NewEmailEvent $event)
    {
        /**
         * @var Account $account
         */
        $account = $event->getAccount();

        $targetFields = [
            AccountService::FIELD_UNREAD_MESSAGES_COUNT,
        ];

        if ($account->getDomain()->getId() === 1) {
             $this->getService()->updateAccount($account, $targetFields);
        }
    }
}
