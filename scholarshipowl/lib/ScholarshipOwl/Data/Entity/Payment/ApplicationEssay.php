<?php

/**
 * ApplicationEssay
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Branislav Jovanovic <branej@gmail.com>
 *
 * @created    	16. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class ApplicationEssay extends AbstractEntity {
	private $accountId;
	private $essayId;
	private $text;

	public function __construct($accountId = null, $essayId = null, $text = '') {
		$this->accountId = $accountId;
		$this->essayId   = $essayId;
		$this->text = $text;
	}

	public function getAccountId() {
		return $this->accountId;
	}

	public function setAccountId($accountId) {
		$this->accountId = $accountId;
	}
	public function getEssayId() {
		return $this->essayId;
	}

	public function setEssayId($essayId) {
		$this->essayId = $essayId;
	}

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function __toString() {
		return $this->text;
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "account_id") {
				$this->setAccountId($value);
			}
			else if($key == "essay_id") {
				$this->setEssayId($value);
			}
			else if($key == "text") {
				$this->setText($value);
			}
		}
	}	

	public function toArray() {
		return array(
			"account_id" => $this->getAccountId(),
			"essay_id" => $this->getEssayId(),
			"text" => $this->getText(),
		);
	}
}
