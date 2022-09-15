<?php

/**
 * IDaneMediaService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	29. March 2016.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;


use ScholarshipOwl\Data\Entity\Account\Account;

interface IDaneMediaService {
	public function getDaneMediaCampaign($daneMediaCampaignId);
	public function getDaneMediaCampaigns();
	public function getActiveDaneMediaCampaigns();
	public function getDaneMediaCampus($daneMediaCampusId);
	public function getDaneMediaCampuses();
	public function getDaneMediaProgram($daneMediaProgramId);
	public function getDaneMediaPrograms();

	public function getAvailableCampaignsForAccount(Account $account, $withCampuses = true);
	public function getAvailablePrograms(Account $account, $data);
	
	public function updateCampaignCapping($campaignId, $type = "monthly");
}
