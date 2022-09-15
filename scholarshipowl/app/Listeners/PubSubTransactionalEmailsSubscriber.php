<?php namespace App\Listeners;

use App\Entity\Package;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Events\Application\ApplicationSentSuccessEvent;
use App\Payment\Events\SubscriptionAddEvent;
use App\Payment\Events\SubscriptionExpiredEvent;
use App\Payment\Events\SubscriptionPaymentEvent;
use App\Events\Email\NewEmailEvent;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Subscription;
use App\Events\Account\CreateAccountEvent;
use App\Events\Subscription\SubscriptionCreditExhausted;
use App\Events\Subscription\FreemiumCreditsRenewal;
use App\Services\EligibilityCacheService;
use App\Services\PubSub\AccountService;
use App\Services\PubSub\TransactionalEmailService;
use App\Services\SubscriptionService;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class PubSubTransactionalEmailsSubscriber
{
    /**
     * @var EligibilityCacheService
     */
    public $elbCacheService;

    /**
     * @var TransactionalEmailService
     */
    protected $transactionEmailPubSunService;

    public function __construct(TransactionalEmailService $tes, EligibilityCacheService $elbCacheService)
    {
        $this->transactionEmailPubSunService = $tes;
        $this->elbCacheService = $elbCacheService;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(SubscriptionAddEvent::class,        static::class.'@onSubscriptionAdded');
        $events->listen(SubscriptionPaymentEvent::class,    static::class.'@onSubscriptionPayment');
        $events->listen(SubscriptionExpiredEvent::class,    static::class.'@onSubscriptionExpired');
        $events->listen(NewEmailEvent::class,               static::class.'@onNewEmail');
        $events->listen(CreateAccountEvent::class,          static::class . '@welcomeEmail');
        $events->listen(SubscriptionCreditExhausted::class, static::class . '@creditExhaustedEmail');
        $events->listen(FreemiumCreditsRenewal::class,      static::class . '@freemiumCreditsRenewalEmail');

        $events->listen(ApplicationSentSuccessEvent::class,      static::class . '@onApplicationSentSuccessEvent');
    }

    /**
     * @param SubscriptionAddEvent $event
     *
     * @throws \Exception
     */
    public function onSubscriptionAdded(SubscriptionAddEvent $event)
    {
        $subscription = $event->getSubscription();
        $accountId = $subscription->getAccount()->getAccountId();

        $params = array(
            "package_name" => $subscription->getName(),
            "expiry_date" => $subscription->getEndDate() ?
                $subscription->getEndDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : '',
        );

        $template = TransactionalEmailService::INITIAL_SUCCESSFUL_DEPOSIT ;

        if ($subscription->isFreeTrial()) {
            $template = TransactionalEmailService::FREETRIAL_ACTIVATED;
        }

        $this->transactionEmailPubSunService->sendCommonEmail($event->getAccount(), $template, $params);

        if (($transaction = $event->getTransaction()) && $event->getTransaction()->isSuccess()) {
            $template = $subscription->isRecurrent() ?
                    TransactionalEmailService::INITIAL_SUCCESSFUL_DEPOSIT :
                    TransactionalEmailService::SINGLE_SUCCESSFUL_DEPOSIT;

            $this->transactionEmailPubSunService->sendCommonEmail($event->getAccount(), $template,[
                "package_name" => $subscription->getName(),
                "billing_amount" => $transaction->getAmount(),
                "expiry_date" => $subscription->getEndDate() ?
                    $subscription->getEndDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : '',
                "next_billing_date" => $subscription->getRenewalDate() ?
                    $subscription->getRenewalDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : '',
                "billing_cycle" => $subscription->getExpirationPeriodValue() . " " .
                    $subscription->getExpirationPeriodType() .
                    ($subscription->getExpirationPeriodValue() == 1 ? "" : "s"),
                "eligible_scholarship_count" => $this->elbCacheService->getAccountEligibleCount($accountId),
            ]);
        }
    }

    /**
     * @param SubscriptionPaymentEvent $event
     *
     * @throws \Exception
     */
    public function onSubscriptionPayment(SubscriptionPaymentEvent $event)
    {
        $subscription = $event->getSubscription();
        $accountId = $subscription->getAccount()->getAccountId();

        $params = array(
            "package_name" => $subscription->getName(),
            "expiry_date" => $subscription->getEndDate() ?
                $subscription->getEndDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : '',
        );

        //TODO: This event should be removed?
        $this->transactionEmailPubSunService->sendCommonEmail($event->getAccount(), TransactionalEmailService::RECURRENT_SUBSCRIPTION_CREATED, $params);

        $template = TransactionalEmailService::INITIAL_SUCCESSFUL_DEPOSIT;

        if (($transaction = $event->getTransaction()) && $event->getTransaction()->isSuccess()) {
            $params = [
                "package_name" => $subscription->getName(),
                "billing_amount" => $transaction->getAmount(),
                "expiry_date" => $subscription->getEndDate() ?
                    $subscription->getEndDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : '',
                "next_billing_date" => $subscription->getRenewalDate() ?
                    $subscription->getRenewalDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : '',
                "billing_cycle" => $subscription->getExpirationPeriodValue() . " " .
                    $subscription->getExpirationPeriodType() .
                    ($subscription->getExpirationPeriodValue() == 1 ? "" : "s"),
                "eligible_scholarship_count" => $this->elbCacheService->getAccountEligibleCount($accountId)
            ];

            if ($subscription->getPackage()->isFreeTrial()) {
                $template = TransactionalEmailService::FREETRIAL_FIRST_CHARGE;
            } else {
                $state = $subscription->getAccount()->getProfile()->getState();
                $state = !is_null($state)? $state->getAbbreviation() : null;

                if ($subscription->isRecurrent()
                    && in_array(
                        $state, config('scholarshipowl.mail.states_upcoming_payment_notifications')
                    )
                ) {
                    $template = TransactionalEmailService::SUBSCRIPTION_ACTIVATED;
                    $frequencyDays =  SubscriptionService::calcFrequencyDays($subscription);
                    $params = array_merge(
                        $params,
                        [
                            AccountService::FIELD_PACKAGE => $subscription->getName(),
                            AccountService::FIELD_PACKAGE_PRICE => $subscription->getPrice(),
                            AccountService::FIELD_PACKAGE_RENEWAL_FREQUENCY => $frequencyDays,
                        ]
                    );
                }
            }

            $this->transactionEmailPubSunService->sendCommonEmail($event->getAccount(), $template, $params);
        }
    }

    /**
     * @param SubscriptionExpiredEvent $event
     *
     * @throws \Exception
     */
    public function onSubscriptionExpired(SubscriptionExpiredEvent $event)
    {
        $subscription = $event->getSubscription();

        $params = array(
            "package_name" => $subscription->getName(),
            "expiry_date" => $subscription->getEndDate() ?
                $subscription->getEndDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : ''
        );

        $template = TransactionalEmailService::MEMBERSHIP_EXPIRED;
        $this->transactionEmailPubSunService->sendCommonEmail($event->getAccount(), $template, $params);
    }

    /**
     * @param NewEmailEvent $event
     *
     * @throws \Exception
     */
    public function onNewEmail(NewEmailEvent $event)
    {
        $this->transactionEmailPubSunService->sendCommonEmail($event->getAccount(), TransactionalEmailService::NEW_EMAIL);
    }

    /**
     * @return ScholarshipRepository
     */
    protected function scholarships()
    {
        return \EntityManager::getRepository(Scholarship::class);
    }

    /**
     * @param CreateAccountEvent $event
     *
     * @throws \Exception
     */
    public function welcomeEmail(CreateAccountEvent $event)
    {
        $this->transactionEmailPubSunService->sendCommonEmail($event->getAccount(), TransactionalEmailService::ACCOUNT_WELCOME);
    }

    /**
     * @param SubscriptionCreditExhausted $event
     *
     * @throws \Exception
     */
    public function creditExhaustedEmail(SubscriptionCreditExhausted $event)
    {
        $this->transactionEmailPubSunService->sendCommonEmail($event->getSubscription()->getAccount(), TransactionalEmailService::SUBSCRIPTION_CREDIT_EXHAUSTED);
    }

    /**
     * @param FreemiumCreditsRenewal $event
     *
     * @throws \Exception
     */
    public function freemiumCreditsRenewalEmail(FreemiumCreditsRenewal $event)
    {
        /** @var SubscriptionRepository $subscriptionRepo */
        $subscriptionRepo = \EntityManager::getRepository(Subscription::class);
        $query = $subscriptionRepo->queryAccountIdsByFreemiumRenewalDate($event->getRenewalDate());
        foreach (QueryIterator::create($query, 1000) as $accounts) {
            $this->transactionEmailPubSunService->sendBulkCommonEmail(array_map('current', $accounts), TransactionalEmailService::SUBSCRIPTION_CREDIT_INCREASES);
        }
    }

    /**
     * @param ApplicationSentSuccessEvent $event
     *
     * @throws \Exception
     */
    public function onApplicationSentSuccessEvent(ApplicationSentSuccessEvent $event)
    {
        $application = $event->getApplication();
        $scholarship = $application->getScholarship();

        $params = array(
            "scholarship_name" => $scholarship->getTitle(),
            "scholarship_deadline" => $scholarship->getExpirationDate()->format(DateHelper::DEFAULT_DATE_FORMAT),
            "scholarship_amount" => number_format($scholarship->getAmount())
        );

        $this->transactionEmailPubSunService->sendCommonEmail($event->getApplication()->getAccount(), TransactionalEmailService::APPLICATION_SENT, $params);
    }

}
