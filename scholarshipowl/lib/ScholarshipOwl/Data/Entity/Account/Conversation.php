<?php

/**
 * Conversation
 *
 * @package     ScholarshipOwl\Data\Entity\Account
 * @version     1.0
 * @author      Branislav Jovanovic <branej@gmail.com>
 *
 * @created     24. November 2014.
 * @copyright   Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Account;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class Conversation extends AbstractEntity {
	const STATUS_CALLED = "called";
	const STATUS_NO_ANSWER = "no_answer";
	const STATUS_WRONG_NUMBER = "wrong_number";

	const POTENTIAL_INCOMPLETE = "incomplete";
	const POTENTIAL_NOT_INTERESTED = "not_interested";
	const POTENTIAL_FREE = "free";
	const POTENTIAL_PAID = "paid";
	const POTENTIAL_VIP  = "vip";
	
	
	private $conversationId;
	private $accountId;
	private $status;
	private $potential;
	private $comment;
	private $lastConversationDate;

	
	public function __construct($conversationId = null) {
		$this->conversationId = null;
		$this->accountId = "";
		$this->status = null;
		$this->potential = null;
		$this->comment = null;
		$this->lastConversationDate = null;
	}

	public function getConversationId() {
		return $this->conversationId;
	}

	public function setConversationId($conversationId) {
		$this->conversationId = $conversationId;
	}

	public function getAccountId() {
		return $this->accountId;
	}

	public function setAccountId($accountId) {
		$this->accountId = $accountId;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getPotential() {
		return $this->potential;
	}

	public function setPotential($potential) {
		$this->potential = $potential;
	}

	public function getComment() {
		return $this->comment;
	}

	public function setComment($comment) {
		$this->comment = $comment;
	}

	public function getLastConversationDate() {
		return $this->lastConversationDate;
	}

	public function setLastConversationDate($lastConversationDate) {
		$this->lastConversationDate = $lastConversationDate;
	}

	public static function getStatuses() {
		return array(
			self::STATUS_CALLED => "Called",
			self::STATUS_NO_ANSWER => "No Answer",
			self::STATUS_WRONG_NUMBER => "Wrong Number",
		);
	}

	public static function getPotentials() {
		return array(
			self::POTENTIAL_INCOMPLETE => "Incomplete",
			self::POTENTIAL_NOT_INTERESTED => "Not Interested",
			self::POTENTIAL_FREE => "Free",
			self::POTENTIAL_PAID => "Paid",
			self::POTENTIAL_VIP  => "VIP"
		);
	}

	public function toArray() {
		return array(
			"account_id" => $this->getAccountId(),
			"status" => $this->getStatus(),
			"potential" => $this->getPotential(),
			"comment" => $this->getComment(),
			"last_conversation_date" => $this->getLastConversationDate(),
		);
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if ($key == "conversation_id") {
				$this->setConversationId($value);
			}
			if($key == "account_id") {
				$this->setAccountId($value);
			}
			else if($key == "status") {
				$this->setStatus($value);
			}
			else if($key == "potential") {
				$this->setPotential($value);
			}
			else if($key == "comment") {
				$this->setComment($value);
			}
			else if($key == "last_conversation_date") {
				$this->setLastConversationDate($value);
			}
		}
	}
}
