<?php namespace App\Payment\Recurly;

use App\Entity\PaymentMethod;
use App\Payment\AbstractTransactionData;

class TransactionData extends AbstractTransactionData
{
    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod
    {
        return PaymentMethod::find(PaymentMethod::RECURLY);
    }
}
