<?php

/**
 * AffiliateGoal
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


class AffiliateGoal extends AbstractEntity {
	private $affiliateGoalId;
	private $affiliate;
	private $name;
	private $url;
	private $description;
	private $redirectDescription;
	private $redirectTime;
	private $value;
	private $logo;
	
	
	public function __construct() {
		$this->affiliateGoalId = 0;
		$this->affiliate = new Affiliate();
		$this->name = "";
		$this->url = "";
		$this->description = "";
		$this->redirectDescription = "";
        $this->redirectTime = 0;
		$this->value = 0;
		$this->logo = "";
	}
	
	
	public function getAffiliateGoalId(){
		return $this->affiliateGoalId;
	}
	
	public function setAffiliateGoalId($affiliateGoalId){
		$this->affiliateGoalId = $affiliateGoalId;
	}
	
	public function getAffiliate(){
		return $this->affiliate;
	}
	
	public function setAffiliate(Affiliate $affiliate){
		$this->affiliate = $affiliate;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function setUrl($url){
		$this->url = $url;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getRedirectDescription() {
		return $this->redirectDescription;
	}
	
	public function setRedirectDescription($redirectDescription) {
		$this->redirectDescription = $redirectDescription;
	}

    public function getRedirectTime() {
        return $this->redirectTime;
    }

    public function setRedirectTime($redirectTime) {
        $this->redirectTime = $redirectTime;
    }
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;	
	}
	
	public function getLogo() {
		return $this->logo;
	}
	
	public function setLogo($logo) {
		$this->logo = $logo;	
	}
	
	
	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "affiliate_goal_id") {
				$this->setAffiliateGoalId($value);
			}
			else if ($key == "affiliate_id") {
				$this->getAffiliate()->setAffiliateId($value);
			}
			else if ($key == "name") {
				$this->setName($value);
			}
			else if ($key == "url") {
				$this->setUrl($value);
			}
			else if ($key == "description") {
				$this->setDescription($value);
			}
			else if ($key == "redirect_description") {
				$this->setRedirectDescription($value);
			}
            else if ($key == "redirect_time") {
                $this->setRedirectTime($value);
            }
			else if ($key == "value") {
				$this->setValue($value);
			}
			else if ($key == "logo") {
				$this->setLogo($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"affiliate_goal_id" => $this->getAffiliateGoalId(),
			"affiliate_id" => $this->getAffiliate()->getAffiliateId(),
			"name" => $this->getName(),
			"url" => $this->getUrl(),
			"description" => $this->getDescription(),
			"redirect_description" => $this->getRedirectDescription(),
			"redirect_time" => $this->getRedirectTime(),
			"value" => $this->getValue(),
			"logo" => $this->getLogo()
		);
	}
}
