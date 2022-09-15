<?php

/**
 * CareerGoal
 *
 * @package     ScholarshipOwl\Data\Entity\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	12. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Info;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class CareerGoal extends AbstractEntity {
	private $careerGoalId;
	private $name;

	const OTHER = 10;
	
	
	public function __construct($careerGoalId = null, $name = "") {
		$this->careerGoalId = $careerGoalId;
		$this->name = $name;
	}
	
	public function getCareerGoalId(){
		return $this->careerGoalId;
	}
	
	public function setCareerGoalId($careerGoalId){
		$this->careerGoalId = $careerGoalId;
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
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "career_goal_id") {
				$this->setCareerGoalId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"career_goal_id" => $this->getCareerGoalId(),
			"name" => $this->getName()
		);
	}
}
