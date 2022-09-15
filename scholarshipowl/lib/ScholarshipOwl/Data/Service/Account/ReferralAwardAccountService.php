<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 26/6/2015
 */

namespace ScholarshipOwl\Data\Service\Account;


use ScholarshipOwl\Data\Entity\Account\ReferralAwardAccount;
use ScholarshipOwl\Data\Service\AbstractService;

class ReferralAwardAccountService extends AbstractService implements IReferralAwardAccountService {
    public function saveReferralAwardAccount($accountId, $awardId, $type = ReferralAwardAccount::REFERRAL_AWARD){
        $referralAwardAccountData = array(
            "account_id" => $accountId,
            "referral_award_id" => $awardId,
            "awarded_date" => date("Y-m-d H:i:s"),
            "award_type" => $type,
        );
        $this->insert(self::TABLE_REFERRAL_AWARD_ACCOUNT, $referralAwardAccountData);
    }
}