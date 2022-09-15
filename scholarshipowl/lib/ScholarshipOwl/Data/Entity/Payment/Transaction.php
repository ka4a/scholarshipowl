<?php

/**
 * Transaction
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	08. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use App\Entity\TransactionPaymentType;
use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;

use ScholarshipOwl\Domain\Repository\SubscriptionRepository;
use ScholarshipOwl\Domain\Subscription as DomainSubscription;

class Transaction extends AbstractEntity {
	const DEVICE_DESKTOP = "desktop";
	const DEVICE_MOBILE = "mobile";

	private $transactionId;
	private $transactionStatus;
	private $paymentMethod;
    private $paymentType;
	private $account;
	private $amount;
	private $responseData;
	private $providerTransactionId;
	private $bankTransactionId;
	private $creditCardType;
	private $failedReason;
	private $device;
	private $createdDate;

    protected $subscription;
    protected $recurrent_number;

	public function __construct($row = null) {
		$this->transactionId = 0;
		$this->transactionStatus = new TransactionStatus();
		$this->paymentMethod = new PaymentMethod();
        $this->paymentType = new TransactionPaymentType();
        $this->subscription = new DomainSubscription();
		$this->amount = 0;
		$this->responseData = "";
		$this->providerTransactionId = "";
		$this->bankTransactionId = "";
		$this->creditCardType = "";
		$this->failedReason = "";
		$this->device = self::DEVICE_DESKTOP;
		$this->createdDate = "0000-00-00 00:00:00";

        parent::__construct($row);
	}
	
	public function getTransactionId(){
		return $this->transactionId;
	}
	
	public function setTransactionId($transactionId){
		$this->transactionId = $transactionId;
	}
	
	public function getTransactionStatus(){
		return $this->transactionStatus;
	}
	
	public function setTransactionStatus(TransactionStatus $transactionStatus){
		$this->transactionStatus = $transactionStatus;
	}
	
	public function getPaymentMethod(){
		return $this->paymentMethod;
	}
	
	public function setPaymentMethod(PaymentMethod $paymentMethod){
		$this->paymentMethod = $paymentMethod;
	}

    public function setPaymentType($type)
    {
        $this->paymentType = TransactionPaymentType::find($type);
    }

    public function getPaymentType()
    {
        return $this->paymentType;
    }

	public function getAccount(){
		return $this->account;
	}
	
	public function setAccount($account){
		$this->account = $account;
	}
	
	public function getAmount(){
		return $this->amount;
	}
	
	public function setAmount($amount){
		$this->amount = $amount;
	}
	
	public function getResponseData() {
		return $this->responseData;
	}
	
	public function setResponseData($responseData) {
		$this->responseData = $responseData;
	}
	
	public function getProviderTransactionId() {
		return $this->providerTransactionId;
	}
	
	public function setProviderTransactionId($providerTransactionId) {
		$this->providerTransactionId = $providerTransactionId;
	}
	
	public function getBankTransactionId() {
		return $this->bankTransactionId;
	}
	
	public function setBankTransactionId($bankTransactionId) {
		$this->bankTransactionId = $bankTransactionId;
	}
	
	public function getFailedReason() {
		return $this->failedReason;
	}
	
	public function setFailedReason($failedReason) {
		$this->failedReason = $failedReason;
	}
	
	public function getDevice() {
		return $this->device;
	}
	
	public function setDevice($device) {
		$this->device = $device;
	}
	
	public function getCreditCardType() {
		return $this->creditCardType;
	}
	
	public function setCreditCardType($creditCardType) {
		$this->creditCardType = $creditCardType;
	}
	
	public function getCreatedDate(){
		return $this->createdDate;
	}
	
	public function setCreatedDate($createdDate){
		$this->createdDate = $createdDate;
	}

    public function setRecurrentNumber($number)
    {
        $this->recurrent_number = $number;
    }

    public function getRecurrentNumber()
    {
        return $this->recurrent_number;
    }

    public function isSuccess()
    {
        return $this->getTransactionStatus()->getTransactionStatusId() === TransactionStatus::SUCCESS;
    }

    public static function getDevices() {
		return array(
			self::DEVICE_DESKTOP => "Desktop",
			self::DEVICE_MOBILE => "Mobile"
		);
	}

    /**
     * @param DomainSubscription $subscription
     */
    public function setSubscription(DomainSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return DomainSubscription
     */
    public function getSubscription()
    {
        if ($this->subscription->getSubscriptionId() == 0) {
            $subscriptionRepository = new SubscriptionRepository();
            if ($subscription = $subscriptionRepository->findById($this->getRawData('subscription_id'))) {
                $this->setSubscription($subscription);
            }
        }

        return $this->subscription;
    }

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "transaction_id") {
				$this->setTransactionId($value);
			}
			else if($key == "account_id") {
			    $this->setAccount(\EntityManager::getReference(\App\Entity\Account::class, $value));
			}
			else if($key == "transaction_status_id") {
				$this->getTransactionStatus()->setTransactionStatusId($value);
			}
            else if($key == "subscription_id") {
                $this->getSubscription()->setSubscriptionId($value);
            }
            else if($key == "payment_method_id") {
                $this->getPaymentMethod()->setPaymentMethodId($value);
            }
			else if($key == "payment_type_id") {
				$this->setPaymentType($value);
			}
			else if($key == "amount") {
				$this->setAmount($value);
			}
			else if($key == "response_data") {
				$this->setResponseData($value);
			}
			else if($key == "provider_transaction_id") {
				$this->setProviderTransactionId($value);
			}
			else if($key == "bank_transaction_id") {
				$this->setBankTransactionId($value);
			}
			else if($key == "credit_card_type") {
				$this->setCreditCardType($value);
			}
			else if($key == "failed_reason") {
				$this->setFailedReason($value);
			}
			else if($key == "device") {
				$this->setDevice($value);
			}
			else if($key == "created_date") {
				$this->setCreatedDate($value);
			}
            else if($key == "recurrent_number") {
                $this->setRecurrentNumber($value);
            }
		}
	}
	
	public function toArray() {
		return array(
			"transaction_id" => $this->getTransactionId(),
            "subscription_id" => $this->getSubscription()->getSubscriptionId(),
			"transaction_status_id" => $this->getTransactionStatus()->getTransactionStatusId(),
			"payment_method_id" => $this->getPaymentMethod()->getPaymentMethodId(),
            "payment_type_id" => $this->getPaymentType()->getId(),
			"account_id" => $this->getAccount()->getAccountId(),
			"amount" => $this->getAmount(),
			"response_data" => $this->getResponseData(),
			"provider_transaction_id" => $this->getProviderTransactionId(),
			"bank_transaction_id" => $this->getBankTransactionId(),
			"credit_card_type" => $this->getCreditCardType(),
			"failed_reason" => $this->getFailedReason(),
			"device" => $this->getDevice(),
			"created_date" => $this->getCreatedDate(),
			"recurrent_number" => $this->getRecurrentNumber()
		);
	}
}
