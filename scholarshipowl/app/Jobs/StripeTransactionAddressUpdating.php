<?php

namespace App\Jobs;

use App\Entity\Account;
use App\Entity\Profile;
use App\Entity\Subscription;

use App\Services\StripeService;

class StripeTransactionAddressUpdating extends Job
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
     * @param StripeService $stripeService
     */
    public function handle(StripeService $stripeService)
    {
        /**
         * @var Account $account
         */
        $account = $this->getSubscription()->getAccount();
        $accountId = $account->getAccountId();
        $stripeId = $account->getStripeId();
        /**
         * @var Profile $profile
         */
        $profile = $account->getProfile();

        $customer = $stripeService->stripe->customers()->find($stripeId);
        $customerCards = $customer['sources']['data'];

        if(!empty($customerCards)) {
            if (count($customerCards ) > 1) {
                \Log::info(
                    sprintf(
                        'Account `%s` has more than one payment method.',
                        $accountId
                    )
                );
            }
            foreach ($customerCards as $card) {
                $cardId = $card['id'];
                try {

                    $state = is_null($profile->getState()) ? "" :  $profile->getState()->getName();
                    $country = is_null($profile->getCountry()) ? "" : $profile->getCountry()->getName();

                    $stripeService->stripe->cards()->update($stripeId, $cardId, [
                        'address_line1' => $profile->getAddress(),
                        'address_state' => $state,
                        'address_country' => $country,
                        'address_zip' => $profile->getZip(),
                        'address_city' => $profile->getCity()
                    ]);
                } catch (\Exception $e) {
                    \Log::error($e);
                }
            }
        }else{
            \Log::error(sprintf(
                "Empty payment method list for account %s",
                $accountId
            ));
        }
    }
}
