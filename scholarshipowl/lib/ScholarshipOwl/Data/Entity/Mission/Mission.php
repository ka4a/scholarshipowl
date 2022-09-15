<?php

/**
 * Mission
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
use ScholarshipOwl\Data\Entity\Payment\Package;


class Mission extends AbstractEntity {
	private $missionId;
	private $package;
	private $name;
	private $startDate;
	private $endDate;
	private $description;
	private $message;
	private $successMessage;
	private $rewardMessage;
	private $active;
	private $visible;

	private $missionGoals;


	public function __construct() {
		$this->missionId = 0;
		$this->package = new Package();
		$this->name = "";
		$this->description = "";
		$this->message = "";
		$this->successMessage = "";
		$this->rewardMessage = "";
		$this->active = false;
		$this->visible = false;

		$this->missionGoals = array();
	}


	public function getMissionId(){
		return $this->missionId;
	}

	public function setMissionId($missionId){
		$this->missionId = $missionId;
	}

	public function getPackage(){
		return $this->package;
	}

	public function setPackage(Package $package){
		$this->package = $package;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}

	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($message){
		$this->message = $message;
	}

	public function getSuccessMessage(){
		return $this->successMessage;
	}

	public function setSuccessMessage($successMessage){
		$this->successMessage = $successMessage;
	}

	public function getRewardMessage(){
		return $this->rewardMessage;
	}

	public function setRewardMessage($rewardMessage){
		$this->rewardMessage = $rewardMessage;
	}

	public function isActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}

	public function isVisible(){
		return $this->visible;
	}

	public function setVisible($visible){
		$this->visible = $visible;
	}

	public function addMissionGoal(MissionGoal $missionGoal) {
		$this->missionGoals[$missionGoal->getMissionGoalId()] = $missionGoal;
	}

	public function getMissionGoals() {
		return $this->missionGoals;
	}

	public function setMissionGoals($missionGoals) {
		foreach ($missionGoals as $missionGoal) {
			$this->addMissionGoal($missionGoal);
		}
	}


	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "mission_id") {
				$this->setMissionId($value);
			}
			else if ($key == "package_id") {
				$this->getPackage()->setPackageId($value);
			}
			else if ($key == "name") {
				$this->setName($value);
			}
			else if ($key == "start_date") {
				$this->setStartDate($value);
			}
			else if ($key == "end_date") {
				$this->setEndDate($value);
			}
			else if ($key == "description") {
				$this->setDescription($value);
			}
			else if ($key == "message") {
				$this->setMessage($value);
			}
			else if ($key == "success_message") {
				$this->setSuccessMessage($value);
			}
			else if ($key == "reward_message") {
				$this->setRewardMessage($value);
			}
			else if ($key == "is_active") {
				$this->setActive($value);
			}
			else if ($key == "is_visible") {
				$this->setVisible($value);
			}
		}
	}

	public function toArray() {
		return array(
			"mission_id" => $this->getMissionId(),
			"package_id" => $this->getPackage()->getPackageId(),
			"name" => $this->getName(),
			"start_date" => $this->getStartDate(),
			"end_date" => $this->getEndDate(),
			"description" => $this->getDescription(),
			"message" => $this->getMessage(),
			"success_message" => $this->getSuccessMessage(),
			"reward_message" => $this->getRewardMessage(),
			"is_active" => $this->isActive(),
			"is_visible" => $this->isVisible(),
		);
	}
}
