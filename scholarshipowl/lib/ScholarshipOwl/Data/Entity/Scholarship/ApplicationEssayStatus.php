<?php

/**
 * ApplicationEssayStatus
 *
 * @package     ScholarshipOwl\Data\Entity\Scholarship
 * @version     1.0
 * @author      Frank Castillo <frank.castillo@yahoo.com>
 *
 * @created    	21. April 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Scholarship;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class ApplicationEssayStatus extends AbstractEntity {
	const NOT_NEEDED = 0;
	const NOT_STARTED = 1;
	const IN_PROGRESS = 2;
	const DONE = 3;
	
	private $applicationEssayStatusId;
	private $name;
	
	
	public function __construct($applicationEssayStatusId = null) {
		$this->applicationEssayStatusId = null;
		$this->name = "";
	
		$this->setApplicationEssayStatusId($applicationEssayStatusId);
	}
	
	public function setApplicationEssayStatusId($applicationEssayStatusId) {
		$this->applicationEssayStatusId = $applicationEssayStatusId;
		
		$statuses = self::getApplicationEssayStatuses();
		if(array_key_exists($applicationEssayStatusId, $statuses)) {
			$this->name = $statuses[$applicationEssayStatusId];
		}
	}
	
	public function getApplicationEssayStatusId() {
		return $this->applicationEssayStatusId;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public static function getApplicationEssayStatuses() {
		return array(
			self::NOT_NEEDED => "No Essay Needed",
			self::NOT_STARTED => "Not Started",
			self::IN_PROGRESS => "In Progress",
			self::DONE => "Done"
		);
	}
	
	public function isNotNeeded() {
		return $this->applicationEssayStatusId == self::NOT_NEEDED;
	}

	public function isNotStarted() {
		return $this->applicationEssayStatusId == self::NOT_STARTED;
	}
	
	public function isInProgress() {
		return $this->applicationEssayStatusId == self::IN_PROGRESS;
	}
	
	public function isDone() {
		return $this->applicationEssayStatusId == self::DONE;
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "application_essay_status_id") {
				$this->setApplicationEssayStatusId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"application_essay_status_id" => $this->getApplicationEssayStatusId(),
			"name" => $this->getName()
		);
	}
}
