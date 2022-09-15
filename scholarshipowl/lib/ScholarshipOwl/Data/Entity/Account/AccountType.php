<?php

/**
 * AccountType
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
 * Class AccountType
 * @package ScholarshipOwl\Data\Entity\Account
 * @deprecated
 */
class AccountType extends AbstractEntity {
	const ADMINISTRATOR = 1;
	const USER = 2;
	
	private $accountTypeId;
	private $name;
	
	
	public function __construct($accountTypeId = null) {
		$this->accountTypeId = null;
		$this->name = "";
		
		$this->setAccountTypeId($accountTypeId);
	}
	
	public function setAccountTypeId($accountTypeId) {
		$this->accountTypeId = $accountTypeId;
		
		$types = self::getAccountTypes();
		if(array_key_exists($accountTypeId, $types)) {
			$this->name = $types[$accountTypeId];
		}
	}
	
	public function getAccountTypeId() {
		return $this->accountTypeId;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public static function getAccountTypes() {
		return array(
			self::ADMINISTRATOR => "Administrator",
			self::USER => "User"
		);
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "account_type_id") {
				$this->setAccountTypeId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"account_type_id" => $this->getAccountTypeId(),
			"name" => $this->getName()
		);
	}
}
