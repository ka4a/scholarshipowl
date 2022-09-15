<?php

/**
 * Degree
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


class Degree extends AbstractEntity {
	private $degreeId;
	private $name;


	public function __construct($degreeId = null, $name = "") {
		$this->degreeId = $degreeId;
		$this->name = $name;
	}

	public function getDegreeId(){
		return $this->degreeId;
	}

	public function setDegreeId($degreeId){
		$this->degreeId = $degreeId;
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
			if($key == "degree_id") {
				$this->setDegreeId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}

	public function toArray() {
		return array(
			"degree_id" => $this->getDegreeId(),
			"name" => $this->getName()
		);
	}
}
