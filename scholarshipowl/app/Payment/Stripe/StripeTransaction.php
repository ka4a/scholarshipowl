<?php namespace App\Payment\Stripe;

use App\Entity\PaymentMethod;
use App\Payment\AbstractTransactionData;

class StripeTransaction extends AbstractTransactionData
{
    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod
    {
        return PaymentMethod::find(PaymentMethod::STRIPE);
    }
}
