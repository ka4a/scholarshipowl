<?php namespace App\Exceptions\Braintree;


class WebhookBraintreePaymentException extends BraintreePaymentException {

    protected $userMessage = "Missing data in webhook";

}