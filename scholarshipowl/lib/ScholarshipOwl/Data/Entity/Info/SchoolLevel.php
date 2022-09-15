<?php

/**
 * SchoolLevel
 *
 * @package     ScholarshipOwl\Data\Entity\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Info;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class SchoolLevel extends AbstractEntity {
	private $schoolLevelId;
	private $name;
	
	
	public function __construct($schoolLevelId = null, $name = "") {
		$this->schoolLevelId = $schoolLevelId;
		$this->name = $name;
	}
	
	public function getSchoolLevelId(){
		return $this->schoolLevelId;
	}
	
	public function setSchoolLevelId($schoolLevelId){
		$this->schoolLevelId = $schoolLevelId;
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
			if($key == "school_level_id") {
				$this->setSchoolLevelId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"school_level_id" => $this->getSchoolLevelId(),
			"name" => $this->getName()
		);
	}
}
