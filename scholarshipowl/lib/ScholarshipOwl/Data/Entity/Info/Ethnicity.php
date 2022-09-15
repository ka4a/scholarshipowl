<?php

/**
 * Ethnicity
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


class Ethnicity extends AbstractEntity {
	private $ethnicityId;
	private $name;
	
	
	public function __construct($ethnicityId = null, $name = "") {
		$this->ethnicityId = $ethnicityId;
		$this->name = $name;
	}
	
	public function getEthnicityId(){
		return $this->ethnicityId;
	}
	
	public function setEthnicityId($ethnicityId){
		$this->ethnicityId = $ethnicityId;
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
			if($key == "ethnicity_id") {
				$this->setEthnicityId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"ethnicity_id" => $this->getEthnicityId(),
			"name" => $this->getName()
		);
	}
}
