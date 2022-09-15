<?php namespace App\Payment\Braintree;

use App\Entity\BraintreeAccount;
use App\Entity\PaymentMethod;
use App\Payment\AbstractTransactionData;

class BraintreeTransactionData extends AbstractTransactionData
{

    /**
     * @var BraintreeAccount
     */
    protected static $braintreeAccount;

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod
    {
        return PaymentMethod::find(PaymentMethod::BRAINTREE);
    }

    /**
     * @param $braintreeAccount
     *
     * @return $this
     */
    public static function setBraintreeAccount(BraintreeAccount $braintreeAccount)
    {
        static::$braintreeAccount = $braintreeAccount;
    }

    /**
     * @return BraintreeAccount
     */
    public static function getBraintreeAccount()
    {
        if (static::$braintreeAccount === null) {
            throw new \RuntimeException('Braintree not fully configured!');
        }

        return static::$braintreeAccount;
    }
}
