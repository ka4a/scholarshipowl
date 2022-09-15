<?php

/**
 * MissionGoal
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
use ScholarshipOwl\Data\Entity\Account\ReferralAward;
use ScholarshipOwl\Data\Entity\Marketing\AffiliateGoal;


class MissionGoal extends AbstractEntity {
	private $missionGoalId;
	private $missionGoalType;
	private $mission;
	private $name;
	private $points;
	private $active;
	private $ordering;
	private $parameters;
	private $affiliateGoal;
	private $referralAward;


	public function __construct() {
		$this->missionGoalId = 0;
		$this->missionGoalType = new MissionGoalType();
		$this->mission = new Mission();
		$this->name = "";
		$this->points = 0;
		$this->active = false;
		$this->ordering = 0;
		$this->parameters = "";
		$this->affiliateGoal = new AffiliateGoal();
		$this->referralAward = new ReferralAward();
	}


	public function getMissionGoalId(){
		return $this->missionGoalId;
	}

	public function setMissionGoalId($missionGoalId){
		$this->missionGoalId = $missionGoalId;
	}

	public function getMissionGoalType(){
		return $this->missionGoalType;
	}

	public function setMissionGoalType(MissionGoalType $missionGoalType){
		$this->missionGoalType = $missionGoalType;
	}

	public function getMission(){
		return $this->mission;
	}

	public function setMission(Mission $mission){
		$this->mission = $mission;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getPoints(){
		return $this->points;
	}

	public function setPoints($points){
		$this->points = $points;
	}

	public function isActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}

	public function getOrdering(){
		return $this->ordering;
	}

	public function setOrdering($ordering){
		$this->ordering = $ordering;
	}

	public function getParameters(){
		return $this->parameters;
	}

	public function setParameters($parameters){
		$this->parameters = $parameters;
	}

	public function getAffiliateGoal(){
		return $this->affiliateGoal;
	}

	public function setAffiliateGoal(AffiliateGoal $affiliateGoal){
		$this->affiliateGoal = $affiliateGoal;
	}

	public function getReferralAward(){
		return $this->referralAward;
	}

	public function setReferralAward(ReferralAward $referralAward){
		$this->referralAward = $referralAward;
	}


	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "mission_goal_id") {
				$this->setMissionGoalId($value);
			}
			else if ($key == "mission_goal_type_id") {
				$this->getMissionGoalType()->setMissionGoalTypeId($value);
			}
			else if ($key == "mission_id") {
				$this->getMission()->setMissionId($value);
			}
			else if ($key == "name") {
				$this->setName($value);
			}
			else if ($key == "points") {
				$this->setPoints($value);
			}
			else if ($key == "is_active") {
				$this->setActive($value);
			}
			else if ($key == "affiliate_goal_id") {
				$this->getAffiliateGoal()->setAffiliateGoalId($value);
			}
			else if ($key == "referral_award_id") {
				$this->getReferralAward()->setReferralAwardId($value);
			}
			else if ($key == "ordering") {
				$this->setOrdering($value);
			}
			else if ($key == "parameters") {
				$this->setParameters($value);
			}
		}
	}

	public function toArray() {
		return array(
			"mission_goal_id" => $this->getMissionGoalId(),
			"mission_goal_type_id" => $this->getMissionGoalType()->getMissionGoalTypeId(),
			"mission_id" => $this->getMission()->getMissionId(),
			"name" => $this->getName(),
			"points" => $this->getPoints(),
			"is_active" => $this->isActive(),
			"affiliate_goal_id" => $this->getAffiliateGoal()->getAffiliateGoalId(),
			"referral_award_id" => $this->getReferralAward()->getReferralAwardId(),
			"ordering" => $this->getOrdering(),
			"parameters" => $this->getParameters(),
		);
	}
}
