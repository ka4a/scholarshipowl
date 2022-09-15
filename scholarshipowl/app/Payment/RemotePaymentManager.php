<?php namespace App\Payment;

use App\Entity\PaymentMethod;
use App\Entity\Subscription;
use App\Payment\Braintree\BraintreeManager;
use App\Payment\Exception\RemoteManagerNotAvailable;
use App\Services\PaymentManager;
use App\Services\PubSub\TransactionalEmailService;
use App\Services\RecurlyService;
use App\Services\StripeService;
use App\Services\Zendesk\ZendeskService;
use Doctrine\ORM\EntityManager;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Util\Mailer;

class RemotePaymentManager
{
    /**
     * @var PaymentFactory
     */
    protected $factory;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * RemotePaymentManager constructor.
     *
     * @param EntityManager  $entityManager
     * @param PaymentFactory $factory
     */
    public function __construct(EntityManager $entityManager, PaymentFactory $factory)
    {
        $this->em = $entityManager;
        $this->factory = $factory;
    }

    /**
     * @param Subscription $subscription
     * @param bool $flush
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function cancelSubscription(Subscription $subscription, $flush = true)
    {
        if (in_array($subscription->getRemoteStatus(), [Subscription::CANCELLED, Subscription::CANCELLED_TRIAL])) {
            return $this;
        }

        $subscription->setRemoteStatus(Subscription::CANCELLED);

        if ($manager = $this->getRemoteManager($subscription->getPaymentMethod())) {

            if ($subscription->isFreeTrial()) {
                $subscription->setRemoteStatus(Subscription::CANCELLED_TRIAL);
                $subscription->setRemoteStatusUpdatedAt(new \DateTime());
                $manager->terminateSubscription($subscription);

                $transactionEmailService = app(TransactionalEmailService::class);
                $transactionEmailService->sendCommonEmail($subscription->getAccount(), TransactionalEmailService::FREETRIAL_CANCELLED);
            } else {
                $manager->cancelSubscription($subscription);
            }

        }

        if ($flush) {
            $this->em->flush($subscription);
        }

        return $this;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return IRemoteManager|null
     */
    protected function getRemoteManager(PaymentMethod $paymentMethod = null)
    {
        switch($paymentMethod !== null ? $paymentMethod->getId() : null) {
            case PaymentMethod::BRAINTREE:
                return new BraintreeManager();
                break;
            case PaymentMethod::RECURLY:
                return app(RecurlyService::class);
                break;
            case PaymentMethod::STRIPE:
                return app(StripeService::class);
                break;
            default:
                return null;
                break;
        }
    }
}
