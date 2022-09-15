<?php

/**
 * Affiliate
 *
 * @package     ScholarshipOwl\Data\Entity\Marketing
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	11. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class Affiliate extends AbstractEntity {
	private $affiliateId;
	private $name;
	private $apiKey;
	private $email;
	private $phone;
	private $website;
	private $description;
	private $active;
	
	private $affiliateGoals;
	
	
	public function __construct() {
		$this->affiliateId = 0;
		$this->name = "";
		$this->apiKey = "";
		$this->email = "";
		$this->phone = "";
		$this->website = "";
		$this->description = "";
		$this->active = false;
		
		$this->affiliateGoals = array();
	}
	
	
	public function getAffiliateId(){
		return $this->affiliateId;
	}
	
	public function setAffiliateId($affiliateId){
		$this->affiliateId = $affiliateId;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getApiKey(){
		return $this->apiKey;
	}
	
	public function setApiKey($apiKey){
		$this->apiKey = $apiKey;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function getPhone(){
		return $this->phone;
	}
	
	public function setPhone($phone){
		$this->phone = $phone;
	}
	
	public function getWebsite(){
		return $this->website;
	}
	
	public function setWebsite($website){
		$this->website = $website;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setDescription($description){
		$this->description = $description;
	}
	
	public function isActive(){
		return $this->active;
	}
	
	public function setActive($active){
		$this->active = $active;
	}
	
	
	public function addAffiliateGoal(AffiliateGoal $affiliateGoal) {
		$this->affiliateGoals[$affiliateGoal->getAffiliateGoalId()] = $affiliateGoal;	
	}
	
	public function getAffiliateGoals() {
		return $this->affiliateGoals;
	}
	
	public function setAffiliateGoals($affiliateGoals) {
		foreach ($affiliateGoals as $affiliateGoal) {
			$this->addAffiliateGoal($affiliateGoal);
		}	
	}
	
	
	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "affiliate_id") {
				$this->setAffiliateId($value);
			}
			else if ($key == "name") {
				$this->setName($value);
			}
			else if ($key == "api_key") {
				$this->setApiKey($value);
			}
			else if ($key == "email") {
				$this->setEmail($value);
			}
			else if ($key == "phone") {
				$this->setPhone($value);
			}
			else if ($key == "website") {
				$this->setWebsite($value);
			}
			else if ($key == "description") {
				$this->setDescription($value);
			}
			else if ($key == "is_active") {
				$this->setActive($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"affiliate_id" => $this->getAffiliateId(),
			"name" => $this->getName(),
			"api_key" => $this->getApiKey(),
			"email" => $this->getEmail(),
			"phone" => $this->getPhone(),
			"website" => $this->getWebsite(),
			"description" => $this->getDescription(),
			"is_active" => $this->isActive()
		);
	}
}
