<?php

/**
 * Application
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	24. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;


class Application extends AbstractEntity {
	private $account;
	private $scholarship;
	private $applicationStatus;
	private $subscription;
	private $submitedData;
	private $comment;
	private $dateApplied;
	
	
	public function __construct() {
		$this->scholarship = new Scholarship();
		$this->applicationStatus = new ApplicationStatus();
		$this->subscription = new Subscription();
		$this->submitedData = "";
		$this->comment = "";
		$this->dateApplied = "";
	}
	
	public function getAccount(){
		return $this->account;
	}
	
	public function setAccount(Account $account){
		$this->account = $account;
	}
	
	public function getScholarship(){
		return $this->scholarship;
	}
	
	public function setScholarship(Scholarship $scholarship){
		$this->scholarship = $scholarship;
	}
	
	public function getApplicationStatus(){
		return $this->applicationStatus;
	}
	
	public function setApplicationStatus(ApplicationStatus $applicationStatus){
		$this->applicationStatus = $applicationStatus;
	}
	
	public function getSubscription(){
		return $this->subscription;
	}
	
	public function setSubscription(Subscription $subscription){
		$this->subscription = $subscription;
	}
	
	public function getSubmitedData(){
		return $this->submitedData;
	}
	
	public function setSubmitedData($submitedData){
		$this->submitedData = $submitedData;
	}
	
	public function getComment(){
		return $this->comment;
	}
	
	public function setComment($comment){
		$this->comment = $comment;
	}
	
	public function getDateApplied(){
		return $this->dateApplied;
	}
	
	public function setDateApplied($dateApplied){
		$this->dateApplied = $dateApplied;
	}
	
	public function populate($row) {
		$this->getAccount()->populate($row);
		$this->getScholarship()->populate($row);
		$this->getApplicationStatus()->populate($row);
		$this->getSubscription()->populate($row);
		
		foreach($row as $key => $value) {
			if($key == "submited_data") {
				$this->setSubmitedData($value);
			}
			else if($key == "comment") {
				$this->setComment($value);
			}
			else if($key == "date_applied") {
				$this->setDateApplied($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"account_id" => $this->getAccount()->getAccountId(),
			"scholarship_id" => $this->getScholarship()->getScholarshipId(),
			"application_status_id" => $this->getApplicationStatus()->getApplicationStatusId(),
			"subscription_id" => $this->getSubscription()->getSubscriptionId(),
			"submited_data" => $this->getSubmitedData(),
			"comment" => $this->getComment(),
			"date_applied" => $this->getDateApplied()
		);
	}
}
