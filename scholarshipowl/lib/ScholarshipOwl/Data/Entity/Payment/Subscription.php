<?php

/**
 * Subscription
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	05. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;
use ScholarshipOwl\Data\Service\Payment\TransactionService;


class Subscription extends AbstractEntity {
    const EXPIRATION_TYPE_NO_EXPIRY = "no_expiry";
    const EXPIRATION_TYPE_DATE = "date";
    const EXPIRATION_TYPE_PERIOD = "period";
    const EXPIRATION_TYPE_RECURRENT = "recurrent";

    const EXPIRATION_PERIOD_TYPE_DAY = "day";
    const EXPIRATION_PERIOD_TYPE_WEEK = "week";
    const EXPIRATION_PERIOD_TYPE_MONTH = "month";
    const EXPIRATION_PERIOD_TYPE_YEAR = "year";

    const RECURRENCE_PERIOD_UNLIMITED = 9999;

    /**
     * Subscription was created from remote API call
     */
    const SOURCE_REMOTE = 'Remote';

    /**
     * Subscription was created on website action.
     */
    const SOURCE_WEBSITE = 'Frontend';

    /**
     * Subscription was created from Admin panel
     */
    const SOURCE_ADMIN = 'Admin';

    /**
     * Subscription was created automatically
     */
    const SOURCE_AUTO = 'Auto';

    /**
    *   Subscription is active
    */
    const ACTIVE = 'active';

    /**
    *   Subscription is expired
    */
    const EXPIRED = 'expired';

    /**
    *   Subscription is canceled
    */
    const CANCELED = 'canceled';

    /**
    *   Subscription is suspended
    */
    const SUSPENDED = 'suspended';

	private $subscriptionId;
	private $subscriptionStatus;
	private $subscriptionAcquiredType;
	private $account;
	private $package;
	private $name;
	private $price;
	private $scholarshipsCount;
	private $scholarshipsUnlimited;
	private $credit;
	private $startDate;
	private $endDate;
	private $terminatedAt;
	private $activeUntil;
	private $renewalDate;
    private $expirationType;
    private $expirationPeriodType;
    private $expirationPeriodValue;
    private $priority;
    private $source;
    private $remote_status;
    private $remote_status_updated_at;

    protected $freeTrial = false;
    protected $freeTrialEndDate;

    protected $external_id = null;
    protected $payment_method_id = null;
    protected $recurrent_count;

	public function __construct(array $row = null) {
		$this->subscriptionId = 0;
		$this->subscriptionStatus = new SubscriptionStatus();
		$this->subscriptionAcquiredType = new SubscriptionAcquiredType();
		$this->package= new Package();
		$this->name = "";
		$this->price = 0;
		$this->scholarshipsCount = 0;
		$this->scholarshipsUnlimited = false;
		$this->credit = 0;
		$this->startDate = "0000-00-00 00:00:00";
		$this->endDate = "0000-00-00 00:00:00";
		$this->renewalDate = "0000-00-00 00:00:00";
		$this->terminatedAt = null;
		$this->activeUntil = null;
        $this->expirationType = null;
        $this->expirationPeriodType = null;
        $this->expirationPeriodValue = null;
        $this->priority = 1;
        $this ->remote_status = 'active';

        if (isset($row['free_trial'])) {
            $this->package->setFreeTrial((bool) $row['free_trial']);
        }

        parent::__construct($row);
	}

	public function getSubscriptionId(){
		return $this->subscriptionId;
	}

	public function setSubscriptionId($subscriptionId){
		$this->subscriptionId = $subscriptionId;
	}

	public function getSubscriptionStatus(){
		return $this->subscriptionStatus;
	}

	public function setSubscriptionStatus(SubscriptionStatus $subscriptionStatus){
		$this->subscriptionStatus = $subscriptionStatus;
	}

	public function getSubscriptionAcquiredType(){
		return $this->subscriptionAcquiredType;
	}

	public function setSubscriptionAcquiredType(SubscriptionAcquiredType $subscriptionAcquiredType){
		$this->subscriptionAcquiredType = $subscriptionAcquiredType;
	}

	public function getAccount(){
		return $this->account;
	}

	public function setAccount(Account $account){
		$this->account = $account;
	}

	public function getPackage(){
        $package = $this->package;
        if ((empty($package) || !$package->getPackageId()) && $this->getRawData('package_id')) {
            $packageService = new PackageService();
            if ($package = $packageService->getPackage($this->getRawData('package_id'))) {
                $this->setPackage($package);
            }
        }

		return $this->package;
	}

	public function setPackage(Package $package){
		$this->package = $package;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getPrice(){
		return $this->price;
	}

	public function setPrice($price){
		$this->price = $price;
	}

	public function getScholarshipsCount(){
		return $this->scholarshipsCount;
	}

	public function setScholarshipsCount($scholarshipsCount){
		$this->scholarshipsCount = $scholarshipsCount;
	}

	public function isScholarshipsUnlimited(){
		return $this->scholarshipsUnlimited;
	}

	public function setScholarshipsUnlimited($scholarshipsUnlimited){
		$this->scholarshipsUnlimited = $scholarshipsUnlimited;
	}

	public function getCredit(){
		return $this->credit;
	}

	public function setCredit($credit){
		$this->credit = $credit;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}

	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}

    public function getRenewalDate(){
        return $this->renewalDate;
    }

    public function setRenewalDate($renewalDate){
        $this->renewalDate = $renewalDate;
    }

    public function getTerminatedAt(){
        return $this->terminatedAt;
    }

    public function setTerminatedAt($value){
        $this->terminatedAt = $value;
    }

    public function getActiveUntil(){
        return $this->activeUntil;
    }

    public function setActiveUntil($value){
        $this->activeUntil = $value;
    }

    public function getExpirationType(){
        return $this->expirationType;
    }

    public function setExpirationType($expirationType){
        $this->expirationType = $expirationType;
    }

    public function getExpirationPeriodType(){
        return $this->expirationPeriodType;
    }

    public function setExpirationPeriodType($expirationPeriodType){
        $this->expirationPeriodType = $expirationPeriodType;
    }

    public function getExpirationPeriodValue(){
        return $this->expirationPeriodValue;
    }

    public function setExpirationPeriodValue($expirationPeriodValue){
        $this->expirationPeriodValue = $expirationPeriodValue;
    }

    public function getPriority(){
        return $this->priority;
    }

    public function setPriority($priority){
        $this->priority = $priority;
    }

    public function isFreeTrial()
    {
        return $this->freeTrial;
    }

    public function setFreeTrial($freeTrial)
    {
        $this->freeTrial = $freeTrial;
    }

    public function getFreeTrialEndDate()
    {
        return $this->freeTrialEndDate;
    }

    public function setFreeTrialEndDate($date)
    {
        $this->freeTrialEndDate = $date;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getExternalId()
    {
        return $this->external_id;
    }

    public function setExternalId($externalId)
    {
        $this->external_id = $externalId;
    }

    public function getPaymentMethodId()
    {
        return $this->payment_method_id;
    }

    public function setPaymentMethodId($paymentMethodId)
    {
        $this->payment_method_id = $paymentMethodId;
    }

    public function getRecurrentCount()
    {
        return $this->recurrent_count;
    }

    public function setRecurrentCount($count)
    {
        $this->recurrent_count = $count;
    }

	public function isEmpty() {
		return !$this->subscriptionId > 0;
	}

	public function isPaid() {
        $service = new TransactionService();
        return $service->countSubscriptionTransactions($this) > 0;
	}

	public function isPaidAcquiredType() {
		$paidAcquiredIds = array(SubscriptionAcquiredType::PURCHASED, SubscriptionAcquiredType::MISSION);
		return in_array($this->getSubscriptionAcquiredType()->getSubscriptionAcquiredTypeId(), $paidAcquiredIds);
	}

	public function isActive() {
		return $this->getSubscriptionStatus()->getSubscriptionStatusId() == SubscriptionStatus::ACTIVE;
	}

	public function getRemoteStatus()
    {
        return $this->remote_status;
    }

    public function setRemoteStatus($remote_status)
    {
        $this->remote_status = $remote_status;
    }

    public function getRemoteStatusUpdatedAt()
    {
        return $this->remote_status_updated_at;
    }

    public function setRemoteStatusUpdatedAt($date)
    {
        $this->remote_status_updated_at = $date;
    }

    public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "subscription_id") {
				$this->setSubscriptionId($value);
			}
			else if($key == "subscription_status_id") {
				$this->getSubscriptionStatus()->setSubscriptionStatusId($value);
			}
			else if($key == "subscription_acquired_type_id") {
				$this->getSubscriptionAcquiredType()->setSubscriptionAcquiredTypeId($value);
			}
			else if($key == "account_id" && !is_null($this->getAccount())) {
				$this->getAccount()->setAccountId($value);
			}
			else if($key == "package_id") {
				$this->getPackage()->setPackageId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
			else if($key == "price") {
				$this->setPrice($value);
			}
			else if($key == "scholarships_count") {
				$this->setScholarshipsCount($value);
			}
			else if($key == "is_scholarships_unlimited") {
				$this->setScholarshipsUnlimited($value);
			}
			else if($key == "credit") {
				$this->setCredit($value);
			}
			else if($key == "start_date") {
				$this->setStartDate($value);
			}
			else if($key == "end_date") {
				$this->setEndDate($value);
			}
            else if($key == "renewal_date") {
                $this->setRenewalDate($value);
            }
            else if($key == "terminated_at") {
                $this->setTerminatedAt($value);
            }
            else if($key == "active_until") {
                $this->setActiveUntil($value);
            }
            else if($key == "expiration_type") {
                $this->setExpirationType($value);
            }
            else if($key == "expiration_period_type") {
                $this->setExpirationPeriodType($value);
            }
            else if($key == "expiration_period_value") {
                $this->setExpirationPeriodValue($value);
            }
            else if($key == "priority") {
                $this->setPriority($value);
            }
            else if($key == "source") {
                $this->setSource($value);
            }
            else if($key == "payment_method_id") {
                $this->setPaymentMethodId($value);
            }
            else if($key == "external_id") {
                $this->setExternalId($value);
            }
            else if($key == "recurrent_count") {
                $this->setRecurrentCount($value);
            }
            else if($key == "remote_status") {
                $this->setRemoteStatus($value);
            }
            else if($key == "remote_status_updated_at") {
                $this->setRemoteStatusUpdatedAt($value);
            }
            else if($key == 'free_trial')
            {
                $this->setFreeTrial($value);
            }
            else if($key == 'free_trial_end_date')
            {
                $this->setFreeTrialEndDate($value);
            }
        }
    }

    public function toArray() {
        return array(
            "subscription_id" => $this->getSubscriptionId(),
            "subscription_status_id" => $this->getSubscriptionStatus()->getSubscriptionStatusId(),
            "subscription_acquired_type_id" => $this->getSubscriptionAcquiredType()->getSubscriptionAcquiredTypeId(),
            "account_id" => $this->getAccount()->getAccountId(),
            "package_id" => $this->getPackage()->getPackageId(),
            "name" => $this->getName(),
            "price" => $this->getPrice(),
            "scholarships_count" => $this->getScholarshipsCount(),
            "is_scholarships_unlimited" => $this->isScholarshipsUnlimited(),
            "credit" => $this->getCredit(),
            "start_date" => $this->getStartDate(),
            "end_date" => $this->getEndDate(),
            "renewal_date" => $this->getRenewalDate(),
            "active_until" => $this->getActiveUntil(),
            "terminated_at" => $this->getTerminatedAt(),
            "expiration_type" => $this->getExpirationType(),
            "expiration_period_type" => $this->getExpirationPeriodType(),
            "expiration_period_value" => $this->getExpirationPeriodValue(),
            "priority" => $this->getPriority(),
            "source" => $this->getSource(),
            "payment_method_id" => $this->getPaymentMethodId(),
            "external_id" => empty($this->getExternalId()) ? null : $this->getExternalId(),
            "recurrent_count" => $this->getRecurrentCount(),
            "remote_status" => $this->getRemoteStatus(),
            "remote_status_updated_at" => $this->getRemoteStatusUpdatedAt(),
            'free_trial' => $this->isFreeTrial(),
            'free_trial_end_date' => $this->getFreeTrialEndDate(),
        );
    }
}
