<?php

/**
 * AccountHasoffersFlagService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created    	14. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Marketing\AccountHasoffersFlag;
use ScholarshipOwl\Data\Service\AbstractService;

class AccountHasoffersFlagService extends AbstractService implements IAccountHasoffersFlagService{
	const CACHE_KEY_ACCOUNT_HASOFFERS_FLAG = "ACCOUNT_HASOFFERS_FLAG";

	public function getFlagForAccount($accountId){
		$sql = sprintf("
			SELECT * FROM %s
			WHERE account_id = ?
		", self::TABLE_ACCOUNT_HASOFFERS_FLAG);

		$resultSet = $this->query($sql, array($accountId));

		$result = false;

		foreach ($resultSet as $row) {
			$result = new AccountHasoffersFlag();
			$result->populate($row);
		}

		return $result;
	}

	public function addFlagForAccount($accountId){
		$sql = sprintf("
			INSERT INTO %s
				(`account_id`, `created_date`, `is_sent`)
				VALUES
				(?, now(), 	0)
				ON DUPLICATE KEY UPDATE is_sent = is_sent;
		", self::TABLE_ACCOUNT_HASOFFERS_FLAG);

		$this->execute($sql, array($accountId));
	}

	// Updates is_sent
	public function setSent($accountId){
		$sql = sprintf("
			UPDATE %s
				SET
				`is_sent` = 1
				WHERE `account_id` = ?;
		", self::TABLE_ACCOUNT_HASOFFERS_FLAG);

		$resultSet = $this->execute($sql, array($accountId));
	}
}
