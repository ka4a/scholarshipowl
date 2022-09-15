<?php

/**
 * StatisticService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	17. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;

use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\AbstractService;


class StatisticService extends AbstractService implements IStatisticService {
	const CACHE_KEY_SCHOLARSHIP_LATEST = "SCHOLARSHIP_LATEST";
	const CACHE_KEY_SCHOLARSHIP_ACTIVE_COUNT = "SCHOLARSHIP_ACTIVE_COUNT";

	/**
	 * Returns Applications Count By AccountIds
	 *
	 * @param $accountIds array|int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplicationsCountByAccountIds($accountIds) {
		$result = array();

		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}

		if (!empty($accountIds)) {
			$sql = sprintf("
				SELECT account_id, COUNT(scholarship_id) AS count
				FROM %s
				WHERE account_id IN (%s)
				GROUP BY account_id
			", self::TABLE_APPLICATION, implode(",", $accountIds));

			$resultSet = $this->query($sql);
			foreach($resultSet as $row) {
				$row = (array) $row;
				$result[$row["account_id"]] = $row["count"];
			}
		}

		return $result;
	}


	/**
	 * Returns Applications Count By AccountIds And Status
	 *
	 * @param $accountIds array|int
	 * @param $status int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplicationsCountByAccountIdsAndStatus($accountIds, $status) {
		$result = array();

		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}

		if (!empty($accountIds)) {
			$sql = sprintf("
				SELECT account_id, COUNT(scholarship_id) AS count
				FROM %s
				WHERE account_id IN (%s)
				AND application_status_id = %d
				GROUP BY account_id
			", self::TABLE_APPLICATION, implode(",", $accountIds), $status);

			$resultSet = $this->query($sql);
			foreach($resultSet as $row) {
				$result[$row->account_id] = $row->count;
			}
		}

		return $result;
	}

	/**
	 * Returns Applications Count By ScholarshipIds
	 *
	 * @param $scholarshipIds array|int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplicationsCountByScholarshipIds($scholarshipIds) {
		$result = array();

		if (!is_array($scholarshipIds)) {
			$scholarshipIds = array($scholarshipIds);
		}

		$sql = sprintf("
			SELECT scholarship_id, COUNT(*) AS count
			FROM %s
			WHERE scholarship_id IN(" . implode(array_fill(0, count($scholarshipIds), "?"), ",") . ")
			GROUP BY scholarship_id
		", self::TABLE_APPLICATION);

		$resultSet = $this->query($sql, $scholarshipIds);
		foreach ($resultSet as $row) {
			$result[$row->scholarship_id] = $row->count;
		}

		return $result;
	}
}
