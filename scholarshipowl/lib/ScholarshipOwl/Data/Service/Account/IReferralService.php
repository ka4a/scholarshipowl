<?php
/**
 * Author: Ivan Krkotic (clone@mail2joe.com)
 * Date: 12/6/2015
 */

namespace ScholarshipOwl\Data\Service\Account;


interface IReferralService {
    public function saveReferral($referralAccountId, $referredAccountId, $referralChannel);
    public function searchReferrals($params = array(), $limit = "");
    public function getAccountReferrals($accountId);
    
    public function getReferredAccountsByReferralIds($accountIds);
    public function getReferralCountsByReferredIds($accountIds);
}
