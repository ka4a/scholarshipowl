<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 28/9/2015
 */

namespace ScholarshipOwl\Data\Service\Account;


use ScholarshipOwl\Data\Entity\Account\ReferralShare;

interface IReferralShareService {
	public function getReferralShare($referralShareId);
	public function addReferralShare(ReferralShare $referralShare);
	public function updateReferralShare(ReferralShare $referralShare);
}