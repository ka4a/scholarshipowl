<?php

/**
 * TransactionStatus
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


class TransactionStatus extends AbstractEntity {
	const SUCCESS = 1;
	const FAILED = 2;
	const VOID = 3;
	const REFUND = 4;
	const CHARGEBACK = 5;
	const OTHER = 6;
	
	
	private $transactionStatusId;
	private $name;
	
	
	public function __construct($transactionStatusId = null) {
		$this->transactionStatusId = null;
		$this->name = "";
	
		$this->setTransactionStatusId($transactionStatusId);
	}
	
	public function setTransactionStatusId($transactionStatusId) {
		$this->transactionStatusId = $transactionStatusId;
		
		$statuses = self::getTransactionStatuses();
		if(array_key_exists($transactionStatusId, $statuses)) {
			$this->name = $statuses[$transactionStatusId];
		}
	}
	
	public function getTransactionStatusId() {
		return $this->transactionStatusId;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public static function getTransactionStatuses() {
		return array(
			self::SUCCESS => "Success",
			self::FAILED => "Failed",
			self::VOID => "Void",
			self::REFUND => "Refund",
			self::CHARGEBACK => "Chargeback",
			self::OTHER => "Other",
		);
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "transaction_status_id") {
				$this->setTransactionStatusId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"transaction_status_id" => $this->getTransactionStatusId(),
			"name" => $this->getName()
		);
	}
}
