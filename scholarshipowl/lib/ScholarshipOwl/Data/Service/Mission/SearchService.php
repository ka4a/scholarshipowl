<?php

/**
 * SearchService
 *
 * @package     ScholarshipOwl\Data\Service\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	02. July 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Mission;

use ScholarshipOwl\Data\Entity\Mission\MissionAccount;
use ScholarshipOwl\Data\Service\AbstractService;


class SearchService extends AbstractService implements ISearchService {
	public function searchMissionAccount($params = array(), $limit = "") {
		$result = array(
			"count" => 0,
			"data" => array()
		);
		
		$tables = sprintf("
			%s AS ma
			JOIN %s AS mga ON mga.mission_account_id = ma.mission_account_id
			JOIN %s AS m ON m.mission_id = ma.mission_id
			JOIN %s AS mg ON mg.mission_id = m.mission_id AND mg.mission_goal_id = mga.mission_goal_id
			JOIN %s AS p ON p.account_id = ma.account_id
			JOIN %s AS acc ON p.account_id = acc.account_id
		", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION, self::TABLE_MISSION_GOAL, self::TABLE_PROFILE, self::TABLE_ACCOUNT);
		
		$columns = "
			ma.mission_account_id, ma.status AS mission_status, ma.date_started AS mission_date_started, ma.date_ended AS mission_date_ended,
			m.name AS mission_name, mg.name AS mission_goal_name, 
			mga.is_started AS mission_goal_is_started, mga.is_accomplished AS mission_goal_is_accomplished, 
			mga.date_started AS mission_goal_date_started, mga.date_accomplished AS mission_goal_date_accomplished,
			p.account_id, p.first_name, p.last_name, acc.created_date, acc.email
		";
		$order = "ORDER BY ma.mission_account_id DESC";
		$where = "";
		$conditions = array();
		$bind = array();
		
		
		if (!empty($params["mission_id"])) {
			$conditions[] = "ma.mission_id IN(" . implode(array_fill(0, count($params["mission_id"]), "?"), ",") . ")";
			foreach ($params["mission_id"] as $param) {
				$bind[] = $param;
			}
		}
		if (!empty($params["mission_status"])) {
			$conditions[] = "ma.status IN(" . implode(array_fill(0, count($params["mission_status"]), "?"), ",") . ")";
			foreach ($params["mission_status"] as $param) {
				$bind[] = $param;
			}
		}
		if (!empty($params["affiliate_goal_id"])) {
			$conditions[] = "mg.affiliate_goal_id IN(" . implode(array_fill(0, count($params["affiliate_goal_id"]), "?"), ",") . ")";
			foreach ($params["affiliate_goal_id"] as $param) {
				$bind[] = $param;
			}
		}
		if (!empty($params["affiliate_goal_status"])) {
			$status = $params["affiliate_goal_status"];
			if ($status == "pending") {
				$conditions[] = "mga.is_started = 0";
				$conditions[] = "mga.is_accomplished = 0";
			}
			else if ($status == "started") {
				$conditions[] = "mga.is_started = 1";
				$conditions[] = "mga.is_accomplished = 0";
			}
			else if ($status == "accomplished") {
				//$conditions[] = "mga.is_started = 1";
				$conditions[] = "mga.is_accomplished = 1";
			}
		}
		if (!empty($params["mission_started_from"])) {
			$conditions[] = "ma.date_started > ?";
			$bind[] = $params["mission_started_from"];
		}
		if (!empty($params["mission_started_to"])) {
			$conditions[] = "ma.date_started <= ?";
			$bind[] = $params["mission_started_to"];
		}
		if (!empty($params["mission_ended_from"])) {
			$conditions[] = "ma.date_ended > ?";
			$bind[] = $params["mission_ended_from"];
		}
		if (!empty($params["mission_ended_to"])) {
			$conditions[] = "ma.date_ended <= ?";
			$bind[] = $params["mission_ended_to"];
		}
		if (!empty($params["affiliate_goal_started_from"])) {
			$conditions[] = "mga.date_started > ?";
			$bind[] = $params["affiliate_goal_started_from"];
		}
		if (!empty($params["affiliate_goal_started_to"])) {
			$conditions[] = "mga.date_started <= ?";
			$bind[] = $params["affiliate_goal_started_to"];
		}
		if (!empty($params["affiliate_goal_accomplished_from"])) {
			$conditions[] = "mga.date_ended > ?";
			$bind[] = $params["affiliate_goal_accomplished_from"];
		}
		if (!empty($params["affiliate_goal_accomplished_to"])) {
			$conditions[] = "mga.date_ended <= ?";
			$bind[] = $params["affiliate_goal_accomplished_to"];
		}
		if (!empty($params["first_name"])) {
			$conditions[] = "p.first_name LIKE ?";
			$bind[] = "%" . $params["first_name"] . "%";
		}
		if (!empty($params["last_name"])) {
			$conditions[] = "p.last_name LIKE ?";
			$bind[] = "%" . $params["last_name"] . "%";
		}
		
		if (!empty($conditions)) {
			$where = "WHERE " . implode(" AND ", $conditions);
		}
		
		if (!empty($limit)) {
			$limit = "LIMIT $limit";
		}
		
		
		// Count
		$sql = sprintf("SELECT COUNT(ma.mission_account_id) AS count FROM %s %s", $tables, $where);
		$resultSet = $this->query($sql, $bind);
		foreach ($resultSet as $row) {
			$row = (array) $row;
			$result["count"] = $row["count"];
		}
		
		
		// Data
		$sql = sprintf("SELECT %s FROM %s %s %s %s", $columns, $tables, $where, $order, $limit);
		$resultSet = $this->query($sql, $bind);
		foreach ($resultSet as $row) {
			$result["data"][] = $row;
		}
		
		return $result;
	}
}
