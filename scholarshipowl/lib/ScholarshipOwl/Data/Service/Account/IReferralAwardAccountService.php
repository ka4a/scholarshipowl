<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 26/6/2015
 */

namespace ScholarshipOwl\Data\Service\Account;


use ScholarshipOwl\Data\Entity\Account\ReferralAwardAccount;

interface IReferralAwardAccountService {
    public function saveReferralAwardAccount($accountId, $awardId, $type = ReferralAwardAccount::REFERRAL_AWARD);
} 