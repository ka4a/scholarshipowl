<?php

/**
 * AccountHasoffersFlag
 *
 * @package     ScholarshipOwl\Data\Entity\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created    	14. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class AccountHasoffersFlag extends AbstractEntity {
	private $accountId;
	private $createdDate;
	private $sent;


	public function __construct() {
		$this->accountId = 0;
		$this->createdDate = "0000-00-00 00:00:00";
		$this->sent = 0;
	}


	public function getAccountId(){
		return $this->accountId;
	}

	public function setAccountId($accountId){
		$this->accountId = $accountId;
	}

	public function getCreatedDate(){
		return $this->createdDate;
	}

	public function setCreatedDate($createdDate){
		$this->createdDate = $createdDate;
	}

	public function isSent(){
		return $this->sent;
	}

	public function setSent($sent){
		$this->sent = $sent;
	}

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "account_id") {
				$this->setAccountId($value);
			}
			else if ($key == "created_date") {
				$this->setCreatedDate($value);
			}
			else if ($key == "is_sent") {
				$this->setSent($value);
			}
		}
	}

	public function toArray() {
		return array(
			"accounte_id" => $this->getAccountId(),
			"created_date" => $this->getCreatedDate(),
			"is_sent" => $this->isSent(),
		);
	}
}
