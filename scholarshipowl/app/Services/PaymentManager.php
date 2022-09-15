<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\PaymentMethod;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\SubscriptionStatus;
use App\Entity\Transaction;
use App\Entity\TransactionStatus;
use App\Payment\Events\PaymentsEvent;
use App\Payment\Events\SubscriptionAddEvent;
use App\Payment\Events\SubscriptionCancelledEvent;
use App\Payment\Events\SubscriptionExpiredEvent;
use App\Payment\Events\SubscriptionPaymentEvent;
use App\Payment\Events\SubscriptionPaymentFailedEvent;
use App\Payment\Events\SubscriptionSuspendedEvent;
use App\Payment\Exception\DuplicateSubscriptionException;
use App\Payment\ITransactionData;
use App\Payment\RemotePaymentManager;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Events\Dispatcher;

class PaymentManager
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @var RemotePaymentManager
     */
    protected $rpm;

    /**
     * PaymentManager constructor.
     *
     * @param EntityManager        $entityManager
     * @param Dispatcher           $dispatcher
     * @param RemotePaymentManager $rpm
     */
    public function __construct(EntityManager $entityManager, Dispatcher $dispatcher, RemotePaymentManager $rpm)
    {
        $this->em = $entityManager;
        $this->events = $dispatcher;
        $this->rpm = $rpm;
    }

    /**
     * @param RemotePaymentManager $manager
     *
     * @return $this
     */
    public function setRemotePaymentManager(RemotePaymentManager $manager)
    {
        $this->rpm = $manager;
        return $this;
    }

    /**
     * @param int|Account           $account
     * @param int|Package           $package
     * @param                       $acquiredType
     * @param ITransactionData|null $transactionData
     * @param null                  $paymentMethod
     * @param string|null           $externalId
     *
     * @return array
     * @throws DuplicateSubscriptionException
     */
    public function applyPackageOnAccount(
                                 $account,
                                 $package,
                                 $acquiredType,
        ITransactionData         $transactionData = null,
                                 $paymentMethod = null,
        string                   $externalId = null
    ) {
        $account = ($account instanceof Account) ? $account : $this->em->find(Account::class, $account);
        $package = ($package instanceof Package) ? $package : $this->em->find(Package::class, $package);

        $paymentMethod = $paymentMethod ?: ($transactionData ? $transactionData->getPaymentMethod() : null);
        $subscription  = $this->createSubscription($account, $package, $paymentMethod, $externalId, $acquiredType);
        $transaction   = $transactionData ? $this->createTransaction($subscription, $transactionData) : null;

        $this->incrementRecurrentNumbers($subscription, $transaction);
        $this->events->dispatch(new SubscriptionAddEvent($subscription, $transaction));

        //trigger event for store correct payment fset for users
        $fset = FeatureSet::config();
        $this->events->dispatch(new PaymentsEvent($account, $fset));

        $this->em->flush();

        return [$subscription, $transaction];
    }

    /**
     * Recurrent subscription payment.
     *
     * @param Subscription     $subscription
     * @param ITransactionData $transactionData
     *
     * @return array [Subscription, Transaction]
     * @throws \Exception
     */
    public function subscriptionPayment(Subscription $subscription, ITransactionData $transactionData)
    {
        $transaction = $this->createTransaction($subscription, $transactionData);

        if ($transaction->isSuccess()) {

            $subscription->setFreeTrial(false);
            $subscription->updateRenewalDate($transaction->getCreatedDate());
            $subscription->setSubscriptionStatus(SubscriptionStatus::ACTIVE);
            $subscription->setRemoteStatus(Subscription::ACTIVE);

            $this->incrementRecurrentNumbers($subscription, $transaction);
        }

        $this->events->dispatch(new SubscriptionPaymentEvent($subscription, $transaction));
        $this->em->flush();

        return [$subscription, $transaction];
    }

    /**
     * TODO: Implement failed payment logic
     * @param Subscription          $subscription
     */
    public function subscriptionPaymentFailed(Subscription $subscription)
    {
        \Event::dispatch(new SubscriptionPaymentFailedEvent($subscription));

        $this->em->flush();
    }

    /**
     * Increments subscription and transaction recurrent counts
     *
     * @param Subscription $subscription
     * @param Transaction  $transaction
     */
    protected function incrementRecurrentNumbers(Subscription $subscription, Transaction $transaction = null)
    {
        if ($transaction && $transaction->isSuccess() && $subscription->isRecurrent()) {
            $subscription->setRecurrentCount($subscription->getRecurrentCount() + 1);
            $transaction->setRecurrentNumber($subscription->getRecurrentCount());
        }
    }

    /**
     * @param Subscription  $subscription
     * @param bool          $flush
     */
    public function expireSubscription(Subscription $subscription, $flush = false)
    {
        if ($subscription->getSubscriptionStatus()->not(SubscriptionStatus::EXPIRED)) {
            $subscription->setSubscriptionStatus(SubscriptionStatus::EXPIRED);
            $subscription->setTerminatedAt(new \DateTime());

            \Event::dispatch(new SubscriptionExpiredEvent($subscription));

            if ($flush) {
                $this->em->flush($subscription);
            }
        }
    }

    /**
    * @param Subscription $subscription
    */
    public function suspendSubscription(Subscription $subscription)
    {
        if ($subscription->getSubscriptionStatus()->not(SubscriptionStatus::SUSPENDED)) {
            $subscription->setSubscriptionStatus(SubscriptionStatus::SUSPENDED);

            \Event::dispatch(new SubscriptionSuspendedEvent($subscription));

            $this->em->flush();
        }
    }

    /**
     * @param Subscription $subscription
     * @param \DateTime|null $activeUntil
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function cancelSubscription(Subscription $subscription, \DateTime $activeUntil = null)
    {
        if ($subscription->getSubscriptionStatus()->not(SubscriptionStatus::CANCELED)) {
            if (!$activeUntil) {
                /** @var Transaction $transaction */
                $transaction = \EntityManager::getRepository(Transaction::class)
                    ->findOneBy(
                        [
                            'subscription' => $subscription->getSubscriptionId(),
                            'transactionStatus' => TransactionStatus::SUCCESS
                        ],
                        ['transactionId' => 'DESC']
                    );

                $actualRenewalDate = $subscription->getRenewalDate();
                $hasActiveRemotePaidSubscription = false;
                if ($transaction) {
                    $transactionDate = $transaction->getCreatedDate();

                    // get next renewal date based on last transaction date and subscription's recurrence settings
                    list($calculatedRenewalDate, $endDate) =  $subscription->countSubscriptionRenewalAndEndDate($transactionDate);
                    $calculatedRenewalDate = Carbon::instance($calculatedRenewalDate)->startOfDay();
                    $actualRenewalDate = Carbon::instance($actualRenewalDate)->startOfDay();

                    $hasActiveRemotePaidSubscription = $calculatedRenewalDate >= $actualRenewalDate;
                }

                $activeUntil = $hasActiveRemotePaidSubscription ? $actualRenewalDate :  (new \DateTime());
            }

            $subscription->setTerminatedAt(new \DateTime());
            $subscription->setActiveUntil($activeUntil);
            $subscription->setSubscriptionStatus(SubscriptionStatus::CANCELED);
            $this->rpm->cancelSubscription($subscription, false);
            $this->em->flush($subscription);
            $this->events->dispatch(new SubscriptionCancelledEvent($subscription));
        }
    }

    /**
     * @param Account                $account
     * @param Package                $package
     * @param PaymentMethod|int|null $paymentMethod
     * @param string|null            $externalId
     * @param int                    $acquiredType
     *
     * @return Subscription
     * @throws DuplicateSubscriptionException
     */
    protected function createSubscription(
        Account       $account,
        Package       $package,
                      $paymentMethod = null,
        string        $externalId = null,
                      $acquiredType = SubscriptionAcquiredType::PURCHASED
    ): Subscription {
        $paymentMethod = $paymentMethod ? PaymentMethod::convert($paymentMethod) : null;
        $acquiredType = SubscriptionAcquiredType::convert($acquiredType);

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = $this->em->getRepository(Subscription::class);
        if ($externalId && $subscriptionRepository->findByExternalId($externalId, $paymentMethod, false)) {
            throw new DuplicateSubscriptionException();
        }

        $subscription = new Subscription(
            $package,
            $acquiredType,
            $paymentMethod,
            $externalId
        );

        $account->addSubscription($subscription);

        $this->em->flush();

        return $subscription;
    }

    /**
     * @param Subscription     $subscription
     * @param ITransactionData $transactionData
     *
     * @return Transaction
     */
    protected function createTransaction(Subscription $subscription, ITransactionData $transactionData): Transaction
    {
        $providedTransactionId = $transactionData->getProvidedTransactionId();
        $bankTransactionId = $transactionData->getBankTransactionId();
        $transactionStatus = $transactionData->getTransactionStatusId();
        $creditCardType = $transactionData->getCreditCardType();
        $paymentMethod = $transactionData->getPaymentMethod();
        $paymentType = $transactionData->getPaymentType();
        $createdDate = $transactionData->getCreatedDate();
        $device = $transactionData->getDevice();
        $amount = $transactionData->getAmount();
        $data = $transactionData->getData();

        $transaction = new Transaction(
            $subscription,
            $paymentMethod,
            $paymentType,
            $amount,
            $data,
            $device,
            $createdDate,
            $transactionStatus
        );

        $transaction->setProviderTransactionId($providedTransactionId);
        $transaction->setBankTransactionId($bankTransactionId);
        $transaction->setCreditCardType($creditCardType);

        $this->em->persist($transaction);
        $this->em->flush($transaction);

        return $transaction;
    }
}
