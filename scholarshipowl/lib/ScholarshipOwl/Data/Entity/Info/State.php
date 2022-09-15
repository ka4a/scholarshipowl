<?php

/**
 * State
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


class State extends AbstractEntity {
	private $stateId;
	private $name;
	private $abbreviation;
	
	private $country;
	
	
	public function __construct($stateId = null, $name = "", $abbreviation = "") {
		$this->stateId = $stateId;
		$this->name = $name;
		$this->abbreviation = $abbreviation;
		
		$this->country = new Country();
	}
	
	public function getStateId(){
		return $this->stateId;
	}
	
	public function setStateId($stateId){
		$this->stateId = $stateId;
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
			if($key == "state_id") {
				$this->setStateId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
			else if($key == "abbreviation") {
				$this->setAbbreviation($value);
			}
			else if($key == "country_id") {
				$this->getCountry()->setCountryId($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"state_id" => $this->getStateId(),
			"name" => $this->getName(),
			"abbreviation" => $this->getAbbreviation(),
			"country_id" => $this->getCountry()->getCountryId()
		);
	}
}
