<?php

/**
 * AccountStatus
 *
 * @package     ScholarshipOwl\Data\Entity\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Account;

use ScholarshipOwl\Data\Entity\AbstractEntity;


/**
 * Class AccountStatus
 * @package ScholarshipOwl\Data\Entity\Account
 * @deprecated
 */
class AccountStatus extends AbstractEntity {
	const REQUESTED = 1;
	const PENDING = 2;
	const ACTIVE = 3;
	const DISABLED = 4;

	private $accountStatusId;
	private $name;
	
	
	public function __construct($accountStatusId = null) {
		$this->accountStatusId = null;
		$this->name = "";
	
		$this->setAccountStatusId($accountStatusId);
	}
	
	public function setAccountStatusId($accountStatusId) {
		$this->accountStatusId = $accountStatusId;
		
		$statuses = self::getAccountStatuses();
		if(array_key_exists($accountStatusId, $statuses)) {
			$this->name = $statuses[$accountStatusId];
		}
	}
	
	public function getAccountStatusId() {
		return $this->accountStatusId;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public static function getAccountStatuses() {
		return array(
			self::REQUESTED => "Requested",
			self::PENDING => "Pending",
			self::ACTIVE => "Active",
			self::DISABLED => "Disabled"
		);
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "account_status_id") {
				$this->setAccountStatusId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"account_status_id" => $this->getAccountStatusId(),
			"name" => $this->getName()
		);
	}
}
