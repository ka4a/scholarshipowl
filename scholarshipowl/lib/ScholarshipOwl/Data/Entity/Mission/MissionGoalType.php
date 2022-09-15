<?php

/**
 * MissionGoalType
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


class MissionGoalType extends AbstractEntity {
	const AFFILIATE = 1;
	const REFER_A_FRIEND = 2;
	const ADVERTISEMENT = 3;


	private $missionGoalTypeId;
	private $name;


	public function __construct($missionGoalTypeId = null) {
		$this->missionGoalTypeId = 0;
		$this->name = "";

		$this->setMissionGoalTypeId($missionGoalTypeId);
	}


	public function getMissionGoalTypeId(){
		return $this->missionGoalTypeId;
	}

	public function setMissionGoalTypeId($missionGoalTypeId){
		$this->missionGoalTypeId = $missionGoalTypeId;

		$types = self::getMissionGoalTypes();
		if(array_key_exists($missionGoalTypeId, $types)) {
			$this->name = $types[$missionGoalTypeId];
		}
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function __toString() {
		return $this->name;
	}

	public static function getMissionGoalTypes() {
		return array(
			self::AFFILIATE => "Affiliate",
			self::REFER_A_FRIEND => "Refer a Friend",
			self::ADVERTISEMENT => "Advertisement"
		);
	}

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "mission_goal_type_id") {
				$this->setMissionGoalTypeId($value);
			}
			else if ($key == "name") {
				$this->setName($value);
			}
		}
	}

	public function toArray() {
		return array(
			"mission_goal_type_id" => $this->getMissionGoalTypeId(),
			"name" => $this->getName()
		);
	}
}
