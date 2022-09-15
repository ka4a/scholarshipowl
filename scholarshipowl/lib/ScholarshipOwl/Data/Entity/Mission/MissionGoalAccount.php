<?php

/**
 * MissionGoalAccount
 *
 * @package     ScholarshipOwl\Data\Entity\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	22. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Mission;

use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\Account\Account;


class MissionGoalAccount extends AbstractEntity {
	private $missionGoalAccountId;
	private $missionAccount;
	private $missionGoal;
	private $started;
	private $accomplished;
	private $dateStarted;
	private $dateAccomplished;
	
	
	public function __construct() {
		$this->missionGoalAccountId = 0;
		$this->missionAccount = new MissionAccount();
		$this->missionGoal = new MissionGoal();
		$this->started = false;
		$this->accomplished = false;
		$this->dateStarted = "";
		$this->dateAccomplished = "";
	}
	
	
	public function getMissionGoalAccountId(){
		return $this->missionGoalAccountId;
	}

	public function setMissionGoalAccountId($missionGoalAccountId){
		$this->missionGoalAccountId = $missionGoalAccountId;
	}

	public function getMissionAccount(){
		return $this->missionAccount;
	}

	public function setMissionAccount(MissionAccount $missionAccount){
		$this->missionAccount = $missionAccount;
	}

	public function getMissionGoal(){
		return $this->missionGoal;
	}

	public function setMissionGoal(MissionGoal $missionGoal){
		$this->missionGoal = $missionGoal;
	}
	
	public function isStarted(){
		return $this->started;
	}
	
	public function setStarted($started){
		$this->started = $started;
	}
	
	public function isAccomplished(){
		return $this->accomplished;
	}

	public function setAccomplished($accomplished){
		$this->accomplished = $accomplished;
	}
	
	public function getDateStarted(){
		return $this->dateStarted;
	}
	
	public function setDateStarted($dateStarted){
		$this->dateStarted = $dateStarted;
	}
	
	public function getDateAccomplished(){
		return $this->dateAccomplished;
	}
	
	public function setDateAccomplished($dateAccomplished){
		$this->dateAccomplished = $dateAccomplished;
	}
	
	
	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "mission_goal_account_id") {
				$this->setMissionGoalAccountId($value);
			}
			else if ($key == "mission_account_id") {
				$this->getMissionAccount()->setMissionAccountId($value);
			}
			else if ($key == "mission_goal_id") {
				$this->getMissionGoal()->setMissionGoalId($value);
			}
			else if ($key == "is_started") {
				$this->setStarted($value);
			}
			else if ($key == "is_accomplished") {
				$this->setAccomplished($value);
			}
			else if ($key == "date_started") {
				$this->setDateStarted($value);
			}
			else if ($key == "date_accomplished") {
				$this->setDateAccomplished($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"mission_goal_account_id" => $this->getMissionGoalAccountId(),
			"mission_account_id" => $this->getMissionAccount()->getMissionAccountId(),
			"mission_goal_id" => $this->getMissionGoal()->getMissionGoalId(),
			"is_started" => $this->isStarted(),
			"is_accomplished" => $this->isAccomplished(),
			"date_started" => $this->getDateStarted(),
			"date_accomplished" => $this->getDateAccomplished()
		);
	}
}
