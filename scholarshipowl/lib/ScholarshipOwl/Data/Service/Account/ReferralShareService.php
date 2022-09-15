<?php

/**
 * ReferralAwardService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	18. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;

use ScholarshipOwl\Data\Entity\Account\Referral;
use ScholarshipOwl\Data\Entity\Account\ReferralShare;
use ScholarshipOwl\Data\Service\AbstractService;


class ReferralShareService extends AbstractService implements IReferralShareService {
	public function getReferralShare($referralShareId) {
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE referral_share_id = ?", self::TABLE_REFERRAL_SHARE);
		$resultSet = $this->query($sql, array($referralShareId));
		foreach ($resultSet as $row) {
			$result = new ReferralShare();
			$result->populate((array) $row);
		}

		return $result;
	}


	public function addReferralShare(ReferralShare $referralShare) {
		return $this->saveReferralShare($referralShare, true);
	}

	public function updateReferralShare(ReferralShare $referralShare) {
		return $this->saveReferralShare($referralShare, false);
	}

	private function saveReferralShare(ReferralShare $referralShare, $insert = true) {
		$result = 0;

		$referralShareId = $referralShare->getReferralShareId();
		$data = $referralShare->toArray();
		unset($data["referral_share_id"]);

		if($insert == true) {
			$this->insert(self::TABLE_REFERRAL_SHARE, $data);
			$referralShareId = $this->getLastInsertId();

			$result = $referralShareId;
		}
		else {
			$result = $this->update(self::TABLE_REFERRAL_SHARE, $data, array("referral_award_id" => $referralShareId));
		}

		return $result;
	}

	/*
	 * 	Get Share Report - Get the history of shares
	 *
	 * @access public
	 * @return Array
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 *
	 */
	public function getShareReport($limit = ""){
		$result = $dataArray = array();

		//	Count
		$sql = sprintf("SELECT COUNT(DISTINCT account_id) AS count FROM %s", self::TABLE_REFERRAL_SHARE);
		$resultSet = $this->query($sql, array());
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result["count"] = $row["count"];
		}

		//	Data
		if(!empty($limit)) {
			$limit = "LIMIT $limit";
		}

		$sql = sprintf(
			"SELECT a.username, p.first_name, p.last_name, rs.account_id, MIN(rs.referral_date) AS first_date, MAX(rs.referral_date) AS last_date, count(*) AS total
				FROM %s AS rs
				LEFT JOIN %s AS a ON rs.account_id = a.account_id
				LEFT JOIN %s AS p ON rs.account_id = p.account_id
				GROUP BY rs.account_id
				ORDER BY last_date DESC
				".$limit.";", self::TABLE_REFERRAL_SHARE, self::TABLE_ACCOUNT, self::TABLE_PROFILE
		);

		$resultSetTotal = $this->query($sql, array());

		foreach($resultSetTotal as $rowTotal){
			$data = array();
			$data["username"] = $rowTotal->username;
			$data["account_id"] = $rowTotal->account_id;
			$data["first_name"] = $rowTotal->first_name;
			$data["last_name"] = $rowTotal->last_name;
			$data["first_date"] = $rowTotal->first_date;
			$data["last_date"] = $rowTotal->last_date;
			$data["total"] = $rowTotal->total;
			$sql = sprintf(
				"SELECT referral_channel, count(*) AS count
					FROM %s
					WHERE account_id = ?
					GROUP BY referral_channel", self::TABLE_REFERRAL_SHARE
			);

			$resultSet = $this->query($sql, array($rowTotal->account_id));
			foreach($resultSet as $row){
				$data[$row->referral_channel] = $row->count;
			}
			$dataArray[] = $data;
		}

		$result["data"] = $dataArray;

		return $result;
	}
}
