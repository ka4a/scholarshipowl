<?php

/**
 * Country
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


class Country extends AbstractEntity {
	const USA = 1;


	private $countryId;
	private $name;
	private $abbreviation;
	
	
	public function __construct($countryId = null, $name = "", $abbreviation = "") {
		$this->countryId = $countryId;
		$this->name = $name;
		$this->abbreviation = $abbreviation;
	}

	public function getCountryId(){
		return $this->countryId;
	}

	public function setCountryId($countryId){
		$this->countryId = $countryId;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getAbbreviation(){
		return $this->abbreviation;
	}

	public function setAbbreviation($abbreviation){
		$this->abbreviation = $abbreviation;
	}

	public function __toString() {
		return $this->name;
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "country_id") {
				$this->setCountryId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
			else if($key == "abbreviation") {
				$this->setAbbreviation($value);
			}
		}
	}

	public function toArray() {
		return array(
			"country_id" => $this->getCountryId(),
			"name" => $this->getName(),
			"abbreviation" => $this->getAbbreviation()
		);
	}
}
