<?php

/**
 * MilitaryAffiliation
 *
 * @package     ScholarshipOwl\Data\Entity\Info
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created    	21. March 2016.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Info;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class MilitaryAffiliation extends AbstractEntity {
	private $militaryAffiliationId;
	private $name;


	public function __construct($militaryAffiliationId = null, $name = "") {
		$this->militaryAffiliationId = $militaryAffiliationId;
		$this->name = $name;
	}

	public function getMilitaryAffiliationId(){
		return $this->militaryAffiliationId;
	}

	public function setMilitaryAffiliationId($militaryAffiliationId){
		$this->militaryAffiliationId = $militaryAffiliationId;
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
			if($key == "military_affiliation_id") {
				$this->setMilitaryAffiliationId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}

	public function toArray() {
		return array(
			"military_affiliation_id" => $this->getMilitaryAffiliationId(),
			"name" => $this->getName(),
		);
	}
}
