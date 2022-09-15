<?php

/**
 * Citizenship
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


class Citizenship extends AbstractEntity {
	private $citizenshipId;
	private $name;
	
	private $country;
	
	
	public function __construct($citizenshipId = null, $name = "") {
		$this->citizenshipId = $citizenshipId;
		$this->name = $name;
		
		$this->country = new Country();
	}
	
	public function getCitizenshipId(){
		return $this->citizenshipId;
	}
	
	public function setCitizenshipId($citizenshipId){
		$this->citizenshipId = $citizenshipId;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getCountry(){
		return $this->country;
	}
	
	public function setCountry(Country $country){
		$this->country = $country;
	}
	
	public function __toString() {
		return $this->name;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "citizenship_id") {
				$this->setCitizenshipId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
			else if($key == "country_id") {
				$this->getCountry()->setCountryId($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"citizenship_id" => $this->getCitizenshipId(),
			"name" => $this->getName(),
			"country_id" => $this->getCountry()->getCountryId()
		);
	}
}
