<?php namespace App\Payment\Braintree;

use App\Entity\Profile;
use App\Exceptions\Braintree\BraintreePaymentException;
use Braintree\Customer;
use Braintree\Exception;
use Braintree\PaymentMethod;

use Braintree\CreditCard;
use Braintree\PayPalAccount;

class PaymentMethodRepository
{

    const BT_REGISTER_BILLING_ADDRESS = 'payment.braintree.register_billing_address';

    /**
     * @param Customer $customer
     * @param Profile  $profile
     * @param string   $paymentMethodNonce
     *
     * @return CreditCard|PayPalAccount
     * @throws Exception
     */
    public static function create(Customer $customer, Profile $profile, string $paymentMethodNonce, $deviceData)
    {
        $params = [
            'customerId' => $customer->id,
            'paymentMethodNonce' => $paymentMethodNonce,
            'deviceData' => $deviceData,
            'options' => [
                'makeDefault' => true
            ]
        ];

        if(empty($customer->addresses)){
            $state = is_null($profile->getState()) ? "" :  $profile->getState()->getName();
            $country = is_null($profile->getCountry()) ? "" : $profile->getCountry()->getAbbreviation();

            $params['billingAddress'] = [
                'streetAddress'     => $profile->getAddress(),
                "locality"          => $profile->getCity(),
                "postalCode"        => $profile->getZip(),
                "region"            => $state,
                "countryCodeAlpha2" => $country
            ];
        }

        $result = PaymentMethod::create($params);
        
        if (!$result->success || !$result->paymentMethod) {
            throw new BraintreePaymentException($result, $customer->id);
        }

        return $result->paymentMethod;
    }

}
