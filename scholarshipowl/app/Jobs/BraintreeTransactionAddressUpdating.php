<?php

namespace App\Jobs;

use App\Entity\Account;
use App\Entity\BraintreeAccount;
use App\Entity\Profile;
use App\Entity\Repository\BraintreeAccountRepository;
use App\Entity\Subscription;
use App\Providers\PaymentServiceProvider;
use Braintree;
use Doctrine\ORM\EntityManager;

class BraintreeTransactionAddressUpdating extends Job
{

    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @param $subscription
     *
     * @return mixed
     */
    public static function dispatch($subscription)
    {
        return dispatch(new static($subscription));
    }


    /**
     * BraintreeTransactionAddressUpdating constructor.
     *
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * @var Account $account
         */
        $account = $this->getSubscription()->getAccount();
        $accountId = $account->getAccountId();
        /**
         * @var Profile $profile
         */
        $profile = $account->getProfile();

        /**
         * @var Braintree\Customer $braintreeCustomer
         */
        $braintreeCustomer = Braintree\Customer::find($accountId);

        $customerPaymentMethods = $braintreeCustomer->paymentMethods;

        if(!empty($customerPaymentMethods)) {
            if (count($customerPaymentMethods) > 1) {
                \Log::info(
                    sprintf(
                        'Account `%s` has more than one payment method.',
                        $accountId
                    )
                );
            }
            foreach ($customerPaymentMethods as $method) {
                $token = $method->token;
                try {
                    $result = $this->updatePaymentMethod($profile, $token);
                }
                catch (Braintree\Exception\NotFound $e) {
                    $subscriptionBraintreeAccount = $this->getRepository()->findBySubscription($this->getSubscription());

                    PaymentServiceProvider::setBraintreeConfigurations($subscriptionBraintreeAccount);
                    $result = $this->updatePaymentMethod($profile, $token);
                    PaymentServiceProvider::setBraintreeConfigurations($this->getRepository()->getDefault());
                }
                catch (Braintree\Exception $e) {
                    \Log::error($e);
                }

                if (!($result instanceof Braintree\Result\Successful)) {
                    \Log::error(
                        sprintf(
                            "Can't update address for %s. Result: %s",
                            $accountId, var_export($result, true)
                        )
                    );
                }
            }
        }else{
            \Log::error(sprintf(
                "Empty payment method list for account %s",
                $accountId
            ));
        }
    }

    /**
     * @return BraintreeAccountRepository
     */
    protected function getRepository()
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        return $em->getRepository(BraintreeAccount::class);
    }

    /**
     * @param Profile $profile
     * @param $token
     * @return mixed
     */
    public function updatePaymentMethod(Profile $profile, $token)
    {
        $state = is_null($profile->getState()) ? "" : $profile->getState()->getName();
        $country = is_null($profile->getCountry()) ? "" : $profile->getCountry()->getAbbreviation();

        $result = Braintree\PaymentMethod::update(
            $token,
            [
                'billingAddress' => [
                    'streetAddress' => $profile->getAddress(),
                    "locality" => $profile->getCity(),
                    "postalCode" => $profile->getZip(),
                    "region" => $state,
                    "countryCodeAlpha2" => $country,
                    'options' => [
                        'updateExisting' => true
                    ]
                ]
            ]
        );
        return $result;
    }
}
