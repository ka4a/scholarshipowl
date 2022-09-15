<?php

/**
 * AffiliateGoal Mapping
 *
 * @package     ScholarshipOwl\Data\Entity\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	30. September 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class AffiliateGoalMapping extends AbstractEntity {
	private $affiliateGoalMappingId;
	private $affiliateGoalId;
	private $urlParameter;
	private $affiliateGoalIdSecondary;
	private $redirectRulesSetId;


	public function __construct() {
		$this->affiliateMappingId = 0;
		$this->affiliateGoalId = 0;
		$this->urlParameter = "";
		$this->affiliateGoalIdSecondary = 0;
		$this->redirectRulesSetId = 0;
	}

	public function getAffiliateGoalMappingId(){
		return $this->affiliateGoalMappingId;
	}

	public function setAffiliateGoalMappingId($affiliateGoalMappingId){
		$this->affiliateGoalMappingId = $affiliateGoalMappingId;
	}

	public function getAffiliateGoalId(){
		return $this->affiliateGoalId;
	}

	public function setAffiliateGoalId($affiliateGoalId){
		$this->affiliateGoalId = $affiliateGoalId;
	}

	public function getUrlParameter(){
		return $this->urlParameter;
	}

	public function setUrlParameter($urlParameter){
		$this->urlParameter = $urlParameter;
	}

	public function getAffiliateGoalIdSecondary(){
		return $this->affiliateGoalIdSecondary;
	}

	public function setAffiliateGoalIdSecondary($affiliateGoalId){
		$this->affiliateGoalIdSecondary = $affiliateGoalId;
	}

	public function getRedirectRulesSetId(){
		return $this->redirectRulesSetId;
	}

	public function setRedirectRulesSetId($redirectRulesSetId){
		$this->redirectRulesSetId = $redirectRulesSetId;
	}


	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "affiliate_goal_mapping_id") {
				$this->setAffiliateGoalMappingId($value);
			}
			else if ($key == "affiliate_goal_id") {
				$this->setAffiliateGoalId($value);
			}
			else if ($key == "url_parameter") {
				$this->setUrlParameter($value);
			}
			else if ($key == "affiliate_goal_id_secondary") {
				$this->setAffiliateGoalIdSecondary($value);
			}
			else if ($key == "redirect_rules_set_id") {
				$this->setRedirectRulesSetId($value);
			}
		}
	}

	public function toArray() {
		return array(
			"affiliate_goal_mapping_id" => $this->getAffiliateGoalMappingId(),
			"affiliate_goal_id" => $this->getAffiliateGoalId(),
			"url_parameter" => $this->getUrlParameter(),
			"affiliate_goal_id_secondary" => $this->getAffiliateGoalIdSecondary(),
			"redirect_rules_set_id" => $this->getRedirectRulesSetId(),
		);
	}
}
