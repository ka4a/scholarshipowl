<?php namespace App\Payment\Braintree;

use App\Entity\BraintreeAccount;
use App\Entity\Repository\BraintreeAccountRepository;
use App\Entity\Subscription;
use App\Payment\IRemoteManager;
use App\Providers\PaymentServiceProvider;
use Braintree\Exception\NotFound;
use Doctrine\ORM\EntityManager;

class BraintreeManager implements IRemoteManager
{
    /**
     * @param Subscription $subscription
     *
     * @return $this
     */
    public function cancelSubscription(Subscription $subscription)
    {
        try {
            \Braintree\Subscription::cancel($subscription->getExternalId());
        } catch (NotFound $e) {

            $subscriptionBraintreeAccount = $this->getRepository()->findBySubscription($subscription);

            PaymentServiceProvider::setBraintreeConfigurations($subscriptionBraintreeAccount);
            \Braintree\Subscription::cancel($subscription->getExternalId());
            PaymentServiceProvider::setBraintreeConfigurations($this->getRepository()->getDefault());
        }

        return $this;
    }

    /**
     * @param Subscription $subscription
     *
     * @return $this
     */
    public function terminateSubscription(Subscription $subscription)
    {
        return $this->cancelSubscription($subscription);
    }

    /**
     * @return BraintreeAccountRepository
     */
    private function getRepository()
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        return $em->getRepository(BraintreeAccount::class);
    }
}
