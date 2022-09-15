<?php
/**
 * Author: Ivan Krkotic (clone@mail2joe.com)
 * Date: 12/6/2015
 */

namespace ScholarshipOwl\Data\Service\Account;


use App\Facades\EntityManager;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Account\Referral;
use ScholarshipOwl\Data\Service\AbstractService;


class ReferralService extends AbstractService implements IReferralService {
    public function saveReferral($referralAccountId, $referredAccountId, $referralChannel) {
        switch($referralChannel){
            case "fb":
                $channel = \ScholarshipOwl\Data\Entity\Account\Referral::CHANNEL_FACEBOOK;
                break;
            case "tw":
                $channel = \ScholarshipOwl\Data\Entity\Account\Referral::CHANNEL_TWITTER;
                break;
            case "pi":
                $channel = \ScholarshipOwl\Data\Entity\Account\Referral::CHANNEL_PINTEREST;
                break;
            case "wa":
                $channel = \ScholarshipOwl\Data\Entity\Account\Referral::CHANNEL_WHATSAPP;
                break;
            case "sms":
                $channel = \ScholarshipOwl\Data\Entity\Account\Referral::CHANNEL_SMS;
                break;
            case "em":
                $channel = \ScholarshipOwl\Data\Entity\Account\Referral::CHANNEL_EMAIL;
                break;
            default:
                $channel = \ScholarshipOwl\Data\Entity\Account\Referral::CHANNEL_LINK;
        }
        $data = array(
            "referral_account_id" => $referralAccountId,
            "referred_account_id" => $referredAccountId,
            "referral_channel" => $channel,
        );

        $result = $this->insert(self::TABLE_REFERRAL, $data);
        return $result;
    }

    public function searchReferrals($params = array(), $limit = "") {
        $result = array(
            "count" => 0,
            "data" => array()
        );

        $tables = sprintf("
    		%s AS r 
    		JOIN %s AS ral_a ON ral_a.account_id = r.referral_account_id
    		JOIN %s AS ral_p ON ral_p.account_id = r.referral_account_id
    	", self::TABLE_REFERRAL, self::TABLE_ACCOUNT, self::TABLE_PROFILE);

        $columns = "r.*, ral_a.*, ral_p.*";
        $where = "";
        $conditions = array();
        $order = "ORDER BY ral_a.created_date DESC";
        $bind = array();

        $joinReferredAccount = false;
        $joinReferredProfile = false;


        // Conditions
        if (!empty($params["referral_first_name"])) {
            $conditions[] = "ral_p.first_name LIKE ?";
            $bind[] = "%" . $params["referral_first_name"] . "%";
        }
        if (!empty($params["referral_last_name"])) {
            $conditions[] = "ral_p.last_name LIKE ?";
            $bind[] = "%" . $params["referral_last_name"] . "%";
        }
        if (!empty($params["referral_email"])) {
            $conditions[] = "ral_a.email LIKE ?";
            $bind[] = "%" . $params["referral_email"] . "%";
        }
        if (!empty($params["referral_created_date_from"])) {
            $conditions[] = "ral_a.created_date > ?";
            $bind[] = $params["referral_created_date_from"];
        }
        if (!empty($params["referral_created_date_to"])) {
            $conditions[] = "ral_a.created_date <= ?";
            $bind[] = $params["referral_created_date_to"];
        }
        if (!empty($params["referral_channel"])) {
            $conditions[] = "r.referral_channel = ?";
            $bind[] = $params["referral_channel"];
        }
        if (!empty($params["referred_first_name"])) {
            $joinReferredProfile = true;

            $conditions[] = "red_p.first_name LIKE ?";
            $bind[] = "%" . $params["referred_first_name"] . "%";
        }
        if (!empty($params["referred_last_name"])) {
            $joinReferredProfile = true;

            $conditions[] = "red_p.last_name LIKE ?";
            $bind[] = "%" . $params["referred_last_name"] . "%";
        }
        if (!empty($params["referred_email"])) {
            $joinReferredAccount = true;

            $conditions[] = "red_a.email LIKE ?";
            $bind[] = "%" . $params["referred_email"] . "%";
        }
        if (!empty($params["referred_created_date_from"])) {
            $joinReferredAccount = true;

            $conditions[] = "red_a.created_date > ?";
            $bind[] = $params["referred_created_date_from"];
        }
        if (!empty($params["referred_created_date_to"])) {
            $joinReferredAccount = true;

            $conditions[] = "red_a.created_date <= ?";
            $bind[] = $params["referred_created_date_to"];
        }


        // Where
        if (!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }


        // Limit
        if (!empty($limit)) {
            $limit = "LIMIT $limit";
        }


        // Joins
        if ($joinReferredAccount) {
            $tables .= sprintf(" JOIN %s AS red_a ON red_a.account_id = r.referred_account_id ", self::TABLE_ACCOUNT);
        }
        if ($joinReferredProfile) {
            $tables .= sprintf(" JOIN %s AS red_p ON red_p.account_id = r.referred_account_id ", self::TABLE_PROFILE);
        }


        // Count
        $sql = sprintf("SELECT COUNT(*) AS count FROM %s %s", $tables, $where);
        $resultSet = $this->query($sql, $bind);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $result["count"] = $row["count"];
        }


        // Data
        $sql = sprintf("SELECT %s FROM %s %s %s %s", $columns, $tables, $where, $order, $limit);
        $resultSet = $this->query($sql, $bind);

        $res2 = EntityManager::getRepository(\App\Entity\Referral::class)->findAll();
        $result["data"] = $res2;

        // Get Referred Account
        $referredIds = array();
        $referredAccounts = array();

        if (!empty($result["data"])) {
            foreach ($result["data"] as $referral) {
                $referredIds[$referral->getReferredAccount()->getAccountId()] = true;
            }

            $referredIds = array_keys($referredIds);
            if (!empty($referredIds)) {
                $accs = EntityManager::getRepository(\App\Entity\Account::class)->findBy(['accountId' => $referredIds]);
                foreach ($accs as $acc) {
                    $referredAccounts[$acc->getAccountId()] = $acc;
                }

                foreach ($result["data"] as $referral) {
                    $referredId = $referral->getReferredAccount()->getAccountId();
                    if (array_key_exists($referredId, $referredAccounts)) {
                        $referral->setReferredAccount($referredAccounts[$referredId]);
                    }
                }
            }
        }

        return $result;
    }
    
    public function getAccountReferrals($accountId) {
    	$result = array();
    	
    	$sql = sprintf("
    		SELECT p.account_id, p.first_name, p.last_name, a.created_date
    		FROM %s as p
    		JOIN %s as a ON a.account_id = p.account_id
    		JOIN %s as r ON r.referral_account_id = p.account_id
    		WHERE r.referred_account_id = ?
    	", self::TABLE_PROFILE, self::TABLE_ACCOUNT, self::TABLE_REFERRAL);
    	
    	$resultSet = $this->query($sql, array($accountId));
    	foreach ($resultSet as $row) {
    		$result[$row->account_id] = $row;
    	}
    	
    	return $result;
    }

    /**
     * @param $accountIds
     *
     * @return array|Account[]
     */
    public function getReferredAccountsByReferralIds($accountIds) {
		$result = array();
		if(!empty($accountIds)){
            $referrals = EntityManager::getRepository(\App\Entity\Referral::class)->findBy(['referralAccount' => $accountIds]);
            $result = [];
            foreach ($referrals as $referral) {
                $result[$referral->getReferralAccount()->getAccountId()][] = [
                    'account_id'  => $referral->getReferredAccount()->getAccountId(),
                    'first_name'  => $referral->getReferredAccount()->getProfile()->getFirstName(),
                    'last_name'  => $referral->getReferredAccount()->getProfile()->getLastName(),
                    'referral_account_id'  => $referral->getReferralAccount()->getAccountId()
                ];
            }
		}
    	return $result;
    }
    
    public function getReferralCountsByReferredIds($accountIds) {
		$result = array();
		if(!empty($accountIds)) {
			$sql = sprintf("
    		SELECT referred_account_id, COUNT(*) AS count
    		FROM %s AS r
    		WHERE referred_account_id IN(%s)
    		GROUP BY referred_account_id
    	", self::TABLE_REFERRAL, implode(",", $accountIds));

			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$result[$row->referred_account_id] = $row->count;
			}
		}
    	return $result;
    }
}
