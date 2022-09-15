<?php namespace App\Entity\Repository;

use App\Entity\BraintreeAccount;
use App\Entity\PaymentMethod;
use App\Entity\Subscription;
use App\Providers\PaymentServiceProvider;
use Braintree\Exception\NotFound;

class BraintreeAccountRepository extends EntityRepository
{
    const DEFAULT_ACCOUNT_CACHE_KEY = "payment.braintree.account.%s";
    /**
     * @return BraintreeAccount|null
     */
    public function getDefault()
    {
        $setting = setting(BraintreeAccount::SETTING_DEFAULT_ACCOUNT);

        if($setting){
            if(\Cache::has(sprintf(self::DEFAULT_ACCOUNT_CACHE_KEY, $setting))){
                return \Cache::get(sprintf(self::DEFAULT_ACCOUNT_CACHE_KEY, $setting));
            }

            $account = $this->findById($setting);
            \Cache::forever(sprintf(self::DEFAULT_ACCOUNT_CACHE_KEY, $setting), $account);

            return $account;
        }

        return null;
    }

    /**
     * @param Subscription $subscription
     *
     * @return BraintreeAccount
     */
    public function findBySubscription(Subscription $subscription)
    {
        $found = null;

        if ($subscription->getPaymentMethod()->not(PaymentMethod::BRAINTREE)) {
            throw new \LogicException('Subscription should be braintree.');
        }

        /** @var BraintreeAccount $braintreeAccount */
        foreach ($this->findAll() as $braintreeAccount) {
            PaymentServiceProvider::setBraintreeConfigurations($braintreeAccount);

            if ($this->findBraintreeSubscription($subscription)) {
                $found = $braintreeAccount;
                break;
            }
        }

        PaymentServiceProvider::setBraintreeConfigurations($this->getDefault());

        if ($found) {
            return $found;
        }

        throw new \RuntimeException(
            sprintf('Braintree account not found for subscription: %s', $subscription->getSubscriptionId())
        );
    }

    /**
     * @param Subscription $subscription
     *
     * @return \Braintree\Subscription|null
     */
    private function findBraintreeSubscription(Subscription $subscription)
    {
        try {
            return \Braintree\Subscription::find($subscription->getExternalId());
        } catch (NotFound $e) {
            return null;
        }
    }
}
