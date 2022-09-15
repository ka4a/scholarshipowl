<?php

/**
 * IZuUsaService
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

interface IZuUsaService {
	public function getZuUsaCampaign($daneMediaCampaignId);
	public function getZuUsaCampaigns();
	public function getActiveZuUsaCampaigns();
	public function getZuUsaCampus($daneMediaCampusId);
	public function getZuUsaCampuses();
	public function getZuUsaProgram($daneMediaProgramId);
	public function getZuUsaPrograms();

	public function getAvailableCampaignsForAccount(Account $account, $withCampuses = true);
	public function getAvailablePrograms(Account $account, $data);
	
	public function updateCampaignCapping($campaignId, $type = "monthly");
}
