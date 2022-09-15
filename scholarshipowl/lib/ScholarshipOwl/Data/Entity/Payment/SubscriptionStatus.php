<?php

/**
 * SubscriptionStatus
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	08. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class SubscriptionStatus extends AbstractEntity {
	const ACTIVE = 1;
	const EXPIRED = 2;
	const CANCELED = 3;

	private $subscriptionStatusId;
	private $name;
	
	
	public function __construct($subscriptionStatusId = null) {
		$this->subscriptionStatusId = null;
		$this->name = "";
	
		$this->setSubscriptionStatusId($subscriptionStatusId);
	}
	
	public function setSubscriptionStatusId($subscriptionStatusId) {
		$this->subscriptionStatusId = $subscriptionStatusId;
		
		$statuses = self::getSubscriptionStatuses();
		if(array_key_exists($subscriptionStatusId, $statuses)) {
			$this->name = $statuses[$subscriptionStatusId];
		}
	}
	
	public function getSubscriptionStatusId() {
		return $this->subscriptionStatusId;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public static function getSubscriptionStatuses() {
		return array(
			self::ACTIVE => "Active",
			self::EXPIRED => "Expired",
			self::CANCELED => "Canceled"
		);
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "subscription_status_id") {
				$this->setSubscriptionStatusId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"subscription_status_id" => $this->getSubscriptionStatusId(),
			"name" => $this->getName()
		);
	}
}
