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

use ScholarshipOwl\Data\Entity\Account\ReferralAward;
use ScholarshipOwl\Data\Entity\Account\ReferralAwardType;
use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Data\Service\Payment\PackageService;


class ReferralAwardService extends AbstractService implements IReferralAwardService {
	public function getReferralAward($referralAwardId) {
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE referral_award_id = ?", self::TABLE_REFERRAL_AWARD);
		$resultSet = $this->query($sql, array($referralAwardId));
		foreach ($resultSet as $row) {
			$result = new ReferralAward();
			$result->populate((array) $row);
		}

		return $result;
	}

    /**
     * @param bool $onlyActive
     * @param bool $includeShares
     *
     * @return array|\App\Entity\ReferralAward[]
     */
	public function getReferralAwards($onlyActive = false, $includeShares = false) {
		$result = [];
        $criteria = [];

		if ($onlyActive == true){
            $criteria['isActive'] = 1;
		}

		if ($includeShares == true) {
            $criteria['referralAwardType'] = \App\Entity\ReferralAwardType::NUMBER_OF_SHARES;
		}

		return \EntityManager::getRepository(\App\Entity\ReferralAward::class)->findBy($criteria);
	}

	public function getShareNumber($referralAwardId){
		$result = array();

		$sql = sprintf("SELECT * FROM %s WHERE referral_award_id = ?;", self::TABLE_REFERRAL_AWARD_SHARE);

		$resultSet = $this->query($sql, array($referralAwardId));

		foreach ($resultSet as $row) {
			$result[$row->referral_channel] = isset($row->share_number)?$row->share_number:0;
		}

		return $result;
	}

	public function saveShareNumber($referralAwardId, $referralChannel, $shareNumber){
		$sql = sprintf("INSERT INTO %s (`referral_award_id`, `referral_channel`, `share_number`) VALUES (?, '".$referralChannel."', ?)
  					ON DUPLICATE KEY UPDATE share_number = ?;", self::TABLE_REFERRAL_AWARD_SHARE);

		$this->execute($sql, array($referralAwardId, $shareNumber, $shareNumber));
	}

	public function addReferralAward(ReferralAward $referralAward) {
		return $this->saveReferralAward($referralAward, true);
	}

	public function updateReferralAward(ReferralAward $referralAward) {
		return $this->saveReferralAward($referralAward, false);
	}

	public function activateReferralAward($referralAwardId) {
		return $this->toggleActivation($referralAwardId, 1);
	}

	public function deactivateReferralAward($referralAwardId) {
		return $this->toggleActivation($referralAwardId, 0);
	}

	private function saveReferralAward(ReferralAward $referralAward, $insert = true) {
		$result = 0;

		$referralAwardId = $referralAward->getReferralAwardId();
		$data = $referralAward->toArray();
		unset($data["referral_award_id"]);

		if (empty($data["referral_package_id"])) {
			$data["referral_package_id"] = null;
		}

		if($insert == true) {
			$this->insert(self::TABLE_REFERRAL_AWARD, $data);
			$referralAwardId = $this->getLastInsertId();

			$result = $referralAwardId;
		}
		else {
			$result = $this->update(self::TABLE_REFERRAL_AWARD, $data, array("referral_award_id" => $referralAwardId));
		}

		return $result;
	}

	private function toggleActivation($referralAwardId, $value) {
		$result = 0;

		$sql = sprintf("UPDATE %s SET is_active = $value WHERE referral_award_id = ?", self::TABLE_REFERRAL_AWARD);
		$result = $this->execute($sql, array($referralAwardId));

		return $result;
	}
}
