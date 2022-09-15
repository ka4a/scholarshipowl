<?php

/**
 * IAffiliateGoalMappingService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	30. September 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Marketing\AffiliateGoalMapping;


interface IAffiliateGoalMappingService {
	public function getAffiliateGoalMapping($affiliateGoalMappingId);
	public function getAffiliateGoalMappings();

	public function addAffiliateGoalMapping(AffiliateGoalMapping $affiliateGoalMapping);
	public function updateAffiliateGoalMapping(AffiliateGoalMapping $affiliateGoalMapping);
	public function saveAffiliateGoalMapping(AffiliateGoalMapping $affiliateGoalMapping, $insert = true);
	public function deleteAffiliateGoalMapping($affiliateGoalMappingId);

	public function getAffiliateGoalMappingByParam($urlParameter);
}
