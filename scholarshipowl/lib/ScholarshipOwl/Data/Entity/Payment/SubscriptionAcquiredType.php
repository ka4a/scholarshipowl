<?php

/**
 * SubscriptionAcquiredType
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	14. July 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class SubscriptionAcquiredType extends AbstractEntity {
	const PURCHASED = 1;
	const WELCOME = 2;
	const REFERRAL = 3;
	const REFERRED = 4;
	const MISSION = 5;
	const FREEBIE = 6;
	
	private $subscriptionAcquiredTypeId;
	private $name;
	
	
	public function __construct($subscriptionAcquiredTypeId = null) {
		$this->subscriptionAcquiredTypeId = null;
		$this->name = "";
	
		$this->setSubscriptionAcquiredTypeId($subscriptionAcquiredTypeId);
	}
	
	public function setSubscriptionAcquiredTypeId($subscriptionAcquiredTypeId) {
		$this->subscriptionAcquiredTypeId = $subscriptionAcquiredTypeId;
		
		$types = self::getSubscriptionAcquiredTypes();
		if (array_key_exists($subscriptionAcquiredTypeId, $types)) {
			$this->name = $types[$subscriptionAcquiredTypeId];
		}
	}
	
	public function getSubscriptionAcquiredTypeId() {
		return $this->subscriptionAcquiredTypeId;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public static function getSubscriptionAcquiredTypes() {
		return array(
			self::PURCHASED => "Purchased",
			self::WELCOME => "Welcome",
			self::REFERRAL => "Referral",
			self::REFERRED => "Referred",
			self::MISSION => "Mission",
			self::FREEBIE => "Freebie"
		);
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "subscription_acquired_type_id") {
				$this->setSubscriptionAcquiredTypeId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"subscription_acquired_type_id" => $this->getSubscriptionAcquiredTypeId(),
			"name" => $this->getName()
		);
	}
}
