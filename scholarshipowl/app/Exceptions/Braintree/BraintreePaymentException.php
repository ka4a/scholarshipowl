<?php namespace App\Exceptions\Braintree;

class BraintreePaymentException extends \Exception {

    /**
     * @var \Braintree\Result\Error
     */
    protected $gateMessage;

    /**
     * @var int
     */
    protected $accountId;

    protected $userMessage = "Error on creating single payment";


    public function __construct($gateMessage, int $accountId = null)
    {
        parent::__construct($gateMessage, 0, null);

        $this->gateMessage = $gateMessage;
        $this->accountId = $accountId;
    }

    public function getGateMessage()
    {
        return $this->gateMessage;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function __toString()
    {
        return sprintf("BraintreeException. account_id: %s. \n %s. \n %s",$this->getAccountId(), $this->gateMessage->message, $this->gateMessage);
    }

}