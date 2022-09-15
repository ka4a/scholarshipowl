<?php

/**
 * DegreeType
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


class DegreeType extends AbstractEntity {
	const UNDECIDED = 1;
	const CERTIFICATE = 2;
	const ASSOCIATES_DEGREE = 3;
	const BACHELORS_DEGREE = 4;
	const GRADUATE_CERTIFICATE = 5;
	const MASTERS_DEGREE = 6;
	const DOCTORAL_PHD = 7;
	
	private $degreeTypeId;
	private $name;
	
	
	public function __construct($degreeTypeId = null, $name = "") {
		$this->degreeTypeId = $degreeTypeId;
		$this->name = $name;
	}
	
	public function getDegreeTypeId(){
		return $this->degreeTypeId;
	}
	
	public function setDegreeTypeId($degreeTypeId){
		$this->degreeTypeId = $degreeTypeId;
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
			if($key == "degree_type_id") {
				$this->setDegreeTypeId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"degree_type_id" => $this->getDegreeTypeId(),
			"name" => $this->getName()
		);
	}
}
