<?php

/**
 * MissionAccount
 *
 * @package     ScholarshipOwl\Data\Entity\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	12. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Mission;

use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\Account\Account;


class MissionAccount extends AbstractEntity {
	const STATUS_PENDING = "pending";
	const STATUS_IN_PROGRESS = "in_progress";
	const STATUS_COMPLETED = "completed";
	
	
	private $missionAccountId;
	private $mission;
	private $account;
	private $status;
	private $points;
	private $dateStarted;
	private $dateEnded;
    private $notified;
	
	private $missionGoalAccounts;
	
	
	public function __construct() {
		$this->missionAccountId = 0;
		$this->mission = new Mission();
		$this->status = self::STATUS_PENDING;
		$this->points = 0;
		$this->dateStarted = "";
		$this->dateEnded = "";
		$this->notified = false;
		$this->missionGoalAccounts = array();
	}
	
	
	public function getMissionAccountId(){
		return $this->missionAccountId;
	}
	
	public function setMissionAccountId($missionAccountId){
		$this->missionAccountId = $missionAccountId;
	}
	
	public function getMission(){
		return $this->mission;
	}
	
	public function setMission(Mission $mission){
		$this->mission = $mission;
	}
	
	public function getAccount(){
		return $this->account;
	}
	
	public function setAccount($account){
		$this->account = $account;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}
	
	public function getPoints(){
		return $this->points;
	}
	
	public function setPoints($points){
		$this->points = $points;
	}
	
	public function getDateStarted(){
		return $this->dateStarted;
	}
	
	public function setDateStarted($dateStarted){
		$this->dateStarted = $dateStarted;
	}
	
	public function getDateEnded(){
		return $this->dateEnded;
	}
	
	public function setDateEnded($dateEnded){
		$this->dateEnded = $dateEnded;
	}

	public function isNotified(){
		return $this->notified;
	}

	public function setNotified($notified){
		$this->notified = $notified;
	}
	
	public function addMissionGoalAccount(MissionGoalAccount $missionGoalAccount) {
		$this->missionGoalAccounts[$missionGoalAccount->getMissionGoalAccountId()] = $missionGoalAccount;
	}
	
	public function getMissionGoalAccounts() {
		return $this->missionGoalAccounts;	
	}
	
	public function setMissionGoalAccounts($missionGoalAccounts) {
		foreach ($missionGoalAccounts as $missionGoalAccount) {
			$this->addMissionGoalAccount($missionGoalAccount);
		}
	}
	
	public static function getMissionAccountStatuses() {
		return array(
			self::STATUS_PENDING => "Pending",
			self::STATUS_IN_PROGRESS => "In Progress",
			self::STATUS_COMPLETED => "Completed"
		);
	}
	
	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "mission_account_id") {
				$this->setMissionAccountId($value);
			}
			else if ($key == "mission_id") {
				$this->getMission()->setMissionId($value);
			}
			else if ($key == "account_id") {
				$this->getAccount()->setAccountId($value);
			}
			else if ($key == "status") {
				$this->setStatus($value);
			}
			else if ($key == "points") {
				$this->setPoints($value);
			}
			else if ($key == "date_started") {
				$this->setDateStarted($value);
			}
			else if ($key == "date_ended") {
				$this->setDateEnded($value);
			}
            else if ($key == "is_notified") {
				$this->setNotified($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"mission_account_id" => $this->getMissionAccountId(),
			"mission_id" => $this->getMission()->getMissionId(),
			"account_id" => $this->getAccount()->getAccountId(),
			"status" => $this->getStatus(),
			"points" => $this->getPoints(),
			"date_started" => $this->getDateStarted(),
			"date_ended" => $this->getDateEnded(),
			"is_notified" => $this->isNotified()
		);
	}
}
