<?php

/**
 * Highschool
 *
 * @package     ScholarshipOwl\Data\Entity\Info
 * @version     1.0
 * @author      Frank Castillo <frank.castillo@yahoo.com>
 *
 * @created    	20. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Info;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class highschool extends AbstractEntity {
	private $highschoolId;
	private $name;
	private $address;
	private $city;
	private $state;
	private $zip;
	private $phone;
	private $isVerified;
	
	public function __construct() {
		$this->highschoolId = 0;
		$this->name = "";
		$this->address = "";
		$this->city = "";
		$this->state = "";
		$this->zip = "";
		$this->phone = "";
		$this->isVerified = false;
	}
	
	public function getHighschoolId() {
		return $this->highschoolId;
	}
	
	public function sethighschoolId($value) {
		$this->highschoolId = $value;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($value) {
		$this->name = $value;
	}
	
	public function getAddress() {
		return $this->address;
	}
	
	public function setAddress($value) {
		$this->address = $value;
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function setCity($value) {
		$this->city = $value;
	}
	
	public function getState() {
		return $this->state;
	}
	
	public function setState($value) {
		$this->state = $value;
	}
	
	public function getZip() {
		return $this->zip;
	}
	
	public function setZip($value) {
		$this->zip = $value;
	}
	
	public function getPhone() {
		return $this->phone;
	}
		
	public function setPhone($value) {
		$this->phone = $value;
	}
	
	public function isVerified() {
		return $this->isVerified;
	}
	
	public function setVerified($value) {
		$this->isVerified = $value;
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "highschool_id") {
				$this->setHighschoolId($value);
			}
			else if($key == 'name') {
				$this->setName($value);
			}
			else if($key == 'address') {
				$this->setAddress($value);
			}
			else if($key == 'city') {
				$this->setCity($value);
			}
			else if($key == 'state') {
				$this->setState($value);
			}
			else if($key == 'zip') {
				$this->setZip($value);
			}
			else if($key == 'phone') {
				$this->setPhone($value);
			}
			else if($key == 'is_verified') {
				$this->setVerified($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			'univerisity_id' => $this->getHighschoolId(),
			'name' => $this->getName(),
			'address' => $this->getAddress(),
			'city' => $this->getCity(),
			'state' => $this->getState(),
			'zip' => $this->getZip(),
			'phone' => $this->getPhone(),
			'is_verified' => $this->isVerified()
		);
	}
}