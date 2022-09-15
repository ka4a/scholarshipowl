<?php namespace App\Payment;

use App\Entity\PaymentMethod;
use App\Payment\Braintree\BraintreeManager;
use App\Payment\Exception\RemoteManagerNotAvailable;
use App\Services\RecurlyService;

class PaymentFactory
{
    /**
     * @param int|PaymentMethod $paymentMethod
     *
     * @return IRemoteManager
     */
    public function buildRemoteManager($paymentMethod): IRemoteManager
    {

    }
}
