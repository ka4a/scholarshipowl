<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\Subscription;
use App\Payment\IRemoteManager;
use App\Payment\Stripe\StripeCustomer;
use Cartalyst\Stripe\Exception\NotFoundException;
use Cartalyst\Stripe\Stripe;

class StripeService implements IRemoteManager
{
    const CURRENCY = 'USD';

    /**
     * @var array
     */
    protected $accounts = [];

    /**
     * @var Stripe
     */
    public $stripe;

    /**
     * @var string
     */
    protected $endpointSecret;

    /**
     * RecurlyService constructor.
     */
    public function __construct()
    {
        $this->stripe = Stripe::make(config('services.stripe.key'));
        $this->endpointSecret = config('services.stripe.endpoint_secret');
    }

    /**
     * @param Account $account
     * @param Package $package
     *
     * @return array
     */
    public function charge(Account $account, Package $package, $stripeToken)
    {
        $stripeAccount = $this->findAccount($account);
        $card = $this->stripe->cards()->create($account->getStripeId(), $stripeToken);
        $result = $this->stripe->charges()->create([
            'customer' => $stripeAccount->getId(),
            'currency' => self::CURRENCY,
            'amount'   => $package->getPriceInCents()/100,
        ]);

        $this->updateBillingAddress($account, ['card' => $card]);

        return $result;
    }

    /**
     * @param Account $account
     * @param Package $package
     *
     * @return array
     */
    public function subscribe(Account $account, Package $package, $stripeToken)
    {
        $stripeCustomer = $this->findAccount($account);
        $subscriptionParameters = [
            'plan'   => $package->getStripePlan(),
            'source' => $stripeToken
        ];

        $token = $this->stripe->tokens()->find($stripeToken);

        //apply discount coupon if it exist in package
        if(!empty($package->getStripeDiscountId())){
            $subscriptionParameters['coupon'] = $package->getStripeDiscountId();
        }

        $result = $this->stripe->subscriptions()->create(
            $stripeCustomer->getId(),
            $subscriptionParameters
        );

        $this->updateBillingAddress($account, $token);
        return $result;
    }

    /**
     * Cancel subscription or refund charge
     *
     * @param Subscription $subscription
     *
     * @return $this
     */
    public function cancelSubscription(Subscription $subscription)
    {
        if($subscription->getExpirationType() != Package::EXPIRATION_TYPE_PERIOD) {
            $this->stripe->subscriptions()->cancel($subscription->getAccount()->getStripeID(), $subscription->getExternalId());
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
     * @param Account $account
     *
     * @return StripeCustomer
     */
    protected function findAccount(Account $account)
    {
        if (!isset($this->accounts[$account->getAccountId()])) {
            try {
                $stripeCustomer = new StripeCustomer($this->stripe->customers()->find($account->getStripeId()));
            } catch (NotFoundException $e) {
                $stripeCustomer = $this->createCustomer($account);
            }

            $this->accounts[$account->getAccountId()] = $stripeCustomer;
        }

        return $this->accounts[$account->getAccountId()];
    }

    /**
     * @param Account $account
     *
     * @return StripeCustomer
     */
    protected function createCustomer(Account $account)
    {
        $stripeCustomer = [
            'email' => $account->getEmail(),
            'metadata' => [
                'username' => $account->getUsername(),
                'first_name' => $account->getProfile()->getFirstName(),
                'last_name' => $account->getProfile()->getLastName()
            ]
        ];
        $customer = new StripeCustomer($this->stripe->customers()->create($stripeCustomer));
        $account->setStripeId($customer->getId());
        \EntityManager::flush();

        return $customer;
    }

    /**
     * @return string
     */
    public function getEndpointSecret()
    {
        return $this->endpointSecret;
    }

    /**
     * @param Account $account
     * @param         $token
     */
    protected function updateBillingAddress(Account $account, $token)
    {
        $billingAddress = $this->getBillingAddressArray($account);
        $cards = $token['card'];
        $cardId = $cards['id'];
        $this->stripe->cards()->update($account->getStripeId(), $cardId, $billingAddress);
    }

    /**
     * Return array of billing address prod.
     *  array['fields']                 array Defines the fields of address.
     *              ['address_line1']   string
     *              ['address_state']   string
     *              ['address_country'] string
     *              ['address_zip']     integer
     *              ['address_city']    string
     * @param Account $account
     *
     * @return array
     */
    protected function getBillingAddressArray(Account $account)
    {
        $profile = $account->getProfile();
        $state = is_null($profile->getState()) ? "" : $profile->getState()->getName();
        $country = is_null($profile->getCountry()) ? "" : $profile->getCountry()->getName();
        $billingAddress = [
            'address_line1' => $profile->getAddress(),
            'address_state' => $state,
            'address_country' => $country,
            'address_zip' => $profile->getZip(),
            'address_city' => $profile->getCity(),
            'name' => $profile->getFullName()
        ];

        return $billingAddress;
    }
}
