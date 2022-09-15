<?php namespace App\Exceptions\Braintree;


class SubscriptionBraintreePaymentException extends BraintreePaymentException {

    protected $userMessage = "Error on creating subscription";

}