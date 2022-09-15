<?php

/**
 * ApplicationStatus
 *
 * @package     ScholarshipOwl\Data\Entity\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	24. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Scholarship;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class ApplicationStatus extends AbstractEntity {
	const PENDING = 1;
	const IN_PROGRESS = 2;
	const SUCCESS = 3;
	const ERROR = 4;
	const NEED_MORE_INFO = 5;
	
	private $applicationStatusId;
	private $name;
	
	
	public function __construct($applicationStatusId = null) {
		$this->applicationStatusId = null;
		$this->name = "";
	
		$this->setApplicationStatusId($applicationStatusId);
	}
	
	public function setApplicationStatusId($applicationStatusId) {
		$this->applicationStatusId = $applicationStatusId;
		
		$statuses = self::getApplicationStatuses();
		if(array_key_exists($applicationStatusId, $statuses)) {
			$this->name = $statuses[$applicationStatusId];
		}
	}
	
	public function getApplicationStatusId() {
		return $this->applicationStatusId;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public static function getApplicationStatuses() {
		return array(
			self::PENDING => "Pending",
			self::IN_PROGRESS => "In Progress",
			self::SUCCESS => "Success",
			self::ERROR => "Error",
			self::NEED_MORE_INFO => "Need More Info"
		);
	}
	
	public function isPending() {
		return $this->applicationStatusId == self::PENDING;
	}
	
	public function isInProgress() {
		return $this->applicationStatusId == self::IN_PROGRESS;
	}
	
	public function isSuccess() {
		return $this->applicationStatusId == self::SUCCESS;
	}
	
	public function isError() {
		return $this->applicationStatusId == self::ERROR;
	}
	
	public function isNeedMoreInfo() {
		return $this->applicationStatusId == self::NEED_MORE_INFO;
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "application_status_id") {
				$this->setApplicationStatusId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"application_status_id" => $this->getApplicationStatusId(),
			"name" => $this->getName()
		);
	}
}
