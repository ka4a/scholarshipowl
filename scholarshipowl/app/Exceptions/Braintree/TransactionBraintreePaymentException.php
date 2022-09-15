<?php namespace App\Exceptions\Braintree;


class TransactionBraintreePaymentException extends BraintreePaymentException {

    protected $userMessage = "Error on creating single payment";

}