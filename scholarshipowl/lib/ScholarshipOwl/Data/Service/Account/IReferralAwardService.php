<?php

/**
 * IReferralAwardService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	18. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;

use ScholarshipOwl\Data\Entity\Account\ReferralAward;


interface IReferralAwardService {
	// Getting Referral Awards
	public function getReferralAward($referralAwardId);
	public function getReferralAwards($onlyActive = false, $includeShares = false);

	//	Saving Referral Shares Functions
	public function getShareNumber($referralAwardId);
	public function saveShareNumber($referralAwardId, $referralChannel, $shareNumber);

	// Saving Referral Awards Functions
	public function addReferralAward(ReferralAward $referralAward);
	public function updateReferralAward(ReferralAward $referralAward);
	public function activateReferralAward($referralAwardId);
	public function deactivateReferralAward($referralAwardId);
}
