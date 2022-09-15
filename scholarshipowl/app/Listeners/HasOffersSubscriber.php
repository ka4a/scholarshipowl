<?php namespace App\Listeners;

use App\Entity\SubscriptionAcquiredType;
use App\Events\Account\CreateAccountEvent;
use App\Events\Account\MissionCompletedEvent;
use App\Jobs\HasOffersPostbackJob;
use App\Payment\Events\SubscriptionAddEvent;
use App\Payment\Events\SubscriptionEvent;
use App\Payment\Events\SubscriptionPaymentEvent;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Data\Service\Marketing\AccountHasoffersFlagService;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

class HasOffersSubscriber
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(MissionCompletedEvent::class,    static::class.'@missionCompleted');
        $events->listen(CreateAccountEvent::class,       static::class.'@accountRegister');
        $events->listen(SubscriptionAddEvent::class,     static::class.'@trackPayment');
        $events->listen(SubscriptionPaymentEvent::class, static::class.'@trackPayment');
    }

    /**
     * @param CreateAccountEvent $event
     */
    public function accountRegister(CreateAccountEvent $event)
    {
        HasOffersPostbackJob::dispatch($event->getAccountId(), 'register');
    }

    /**
     * @param MissionCompletedEvent $event
     */
    public function missionCompleted(MissionCompletedEvent $event)
    {
        HasOffersPostbackJob::dispatch($event->getAccountId(), 'mission-accomplished');
    }

    /**
     * @param SubscriptionEvent $event
     */
    public function trackPayment(SubscriptionEvent $event)
    {
        $subscription = $event->getSubscription();

        if ($subscription->getSubscriptionAcquiredType()->not(SubscriptionAcquiredType::PURCHASED)) {
            return;
        }

        $this->addFlagOnPayment($subscription->getAccount()->getAccountId(), $subscription->isFreeTrial());

        $url = $event->getSubscription()->isFreeTrial() ? 'free-trial' : 'payment-show-success';

        HasOffersPostbackJob::dispatch($event->getSubscription()->getAccount(), $url);
    }

    /**
     * Add flag for pixel on payment
     *
     * @param $accountId
     * @param $freeTrial
     */
    protected function addFlagOnPayment($accountId, $freeTrial)
    {
        $marketingSystemService = new MarketingSystemService();
        $marketingSystemAccount = $marketingSystemService->getMarketingSystemAccount($accountId);

        if ($marketingSystemAccount->getHasOffersOfferId() == 32) {
            $accountHasoffersFlagService = new AccountHasoffersFlagService();
            $accountHasoffersFlagService->addFlagForAccount($accountId);
            \Session::put('HO_FLAG_FREETRIAL', $freeTrial);
        }
    }
}
