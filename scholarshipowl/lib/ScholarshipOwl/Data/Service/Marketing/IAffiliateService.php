<?php

/**
 * IAffiliateService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	11. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Marketing\Affiliate;


interface IAffiliateService {
	// Getting affiliates functions
	public function getAffiliate($affiliateId, $goals = false);
	public function getAffiliates();
	public function getAffiliatesGoals();
	public function getAffiliatesList();
	public function getAffiliateByApiKey($apiKey, $goals = false);
	
	
	// Getting affiliate goals functions
	public function getAffiliateGoalUrlById($affiliateGoalId);
	
	
	// Saving functions
	public function addAffiliate(Affiliate $affiliate, $goals = array());
	public function updateAffiliate(Affiliate $affiliate, $goals = array());
	
	
	// Account functions
	public function saveResponse($accountId, $goalId, $url, $data = array());
	public function searchResponses($params = array(), $limit = "");
}
