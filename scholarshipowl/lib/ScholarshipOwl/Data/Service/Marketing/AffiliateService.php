<?php

/**
 * AffiliateService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	11. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Marketing\Affiliate;
use ScholarshipOwl\Data\Entity\Marketing\AffiliateGoal;
use ScholarshipOwl\Data\Service\AbstractService;


class AffiliateService extends AbstractService implements IAffiliateService {
	public function getAffiliate($affiliateId, $goals = false) {
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE affiliate_id = ?", self::TABLE_AFFILIATE);
		$resultSet = $this->query($sql, array($affiliateId));
		foreach ($resultSet as $row) {
			$result = new Affiliate();
			$result->populate((array) $row);
		}

		if (isset($result) && $goals == true) {
			$sql = sprintf("SELECT * FROM %s WHERE affiliate_id = ?", self::TABLE_AFFILIATE_GOAL);
			$resultSet = $this->query($sql, array($result->getAffiliateId()));
			foreach ($resultSet as $row) {
				$entity = new AffiliateGoal();
				$entity->populate((array) $row);

				$result->addAffiliateGoal($entity);
			}
		}

		return $result;
	}

	public function getAffiliates() {
		$result = array();

		$sql = sprintf("SELECT * FROM %s ORDER BY affiliate_id DESC", self::TABLE_AFFILIATE);
		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$entity = new Affiliate();
			$entity->populate((array) $row);

			$result[$entity->getAffiliateId()] = $entity;
		}

		$affiliateIds = array_keys($result);
		if (!empty($affiliateIds)) {
			$sql = sprintf("SELECT * FROM %s WHERE affiliate_id IN(%s)", self::TABLE_AFFILIATE_GOAL, implode(",", $affiliateIds));
			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$entity = new AffiliateGoal();
				$entity->populate((array) $row);

				$result[$entity->getAffiliate()->getAffiliateId()]->addAffiliateGoal($entity);
			}
		}

		return $result;
	}

	public function getAffiliatesGoals() {
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_AFFILIATE_GOAL);
		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$entity = new AffiliateGoal();
			$entity->populate((array) $row);

			$result[$entity->getAffiliateGoalId()] = $entity;
		}

		return $result;
	}

	public function getAffiliatesList() {
		$result = array();

		$sql = sprintf("
			SELECT a.name AS affiliate_name, ag.affiliate_goal_id, ag.name AS affiliate_goal_name
			FROM %s AS a
			JOIN %s AS ag ON ag.affiliate_id = a.affiliate_id
		", self::TABLE_AFFILIATE, self::TABLE_AFFILIATE_GOAL);

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$result[$row->affiliate_goal_id] = sprintf("%s (%s)", $row->affiliate_name, $row->affiliate_goal_name);
		}

		return $result;
	}

	public function getAffiliateByApiKey($apiKey, $goals = false) {
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE api_key = ?", self::TABLE_AFFILIATE);
		$resultSet = $this->query($sql, array($apiKey));
		foreach ($resultSet as $row) {
			$result = new Affiliate();
			$result->populate((array) $row);
		}

		if (isset($result) && $goals == true) {
			$sql = sprintf("SELECT * FROM %s WHERE affiliate_id = ?", self::TABLE_AFFILIATE_GOAL);
			$resultSet = $this->query($sql, array($result->getAffiliateId()));
			foreach ($resultSet as $row) {
				$entity = new AffiliateGoal();
				$entity->populate((array) $row);

				$result->addAffiliateGoal($entity);
			}
		}

		return $result;
	}


	public function getAffiliateGoalUrlById($affiliateGoalId) {
		$result = "";

		$sql = sprintf("SELECT url FROM %s WHERE affiliate_goal_id = ?", self::TABLE_AFFILIATE_GOAL);
		$resultSet = $this->query($sql, array($affiliateGoalId));

		foreach ($resultSet as $row) {
			$result = $row->url;
		}

		return $result;
	}


	public function addAffiliate(Affiliate $affiliate, $goals = array()) {
		return $this->saveAffiliate($affiliate, $goals, true);
	}

	public function updateAffiliate(Affiliate $affiliate, $goals = array()) {
		return $this->saveAffiliate($affiliate, $goals, false);
	}



	public function saveResponse($accountId, $goalId, $url, $data = array()) {
		try {
			$this->beginTransaction();

			$insertData = array(
				"affiliate_goal_id" => $goalId,
				"account_id" => $accountId,
				"url" => $url,
				"response_date" => date("Y-m-d H:i:s")
			);

			$this->insert(self::TABLE_AFFILIATE_GOAL_RESPONSE, $insertData);
			$affiliateGoalResponseId = $this->getLastInsertId();


			if (!empty($data)) {
				$columns = array("affiliate_goal_response_id", "name", "value");
				$insertData = array();

				foreach ($data as $name => $value) {
					$insertData[] = array($affiliateGoalResponseId, $name, $value);
				}

				$this->insertBulk(self::TABLE_AFFILIATE_GOAL_RESPONSE_DATA, $columns, $insertData);
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}
	}

	public function searchResponses($params = array(), $limit = "") {
		$result = array(
			"data" => array(),
			"count" => 0,
		);

        $tables = sprintf("
        	%s AS agr
            JOIN %s AS ag ON ag.affiliate_goal_id = agr.affiliate_goal_id
            JOIN %s AS a ON a.affiliate_id = ag.affiliate_id
			JOIN %s AS p ON p.account_id = agr.account_id
        	JOIN %s AS acc ON acc.account_id = p.account_id
        ", self::TABLE_AFFILIATE_GOAL_RESPONSE, self::TABLE_AFFILIATE_GOAL, self::TABLE_AFFILIATE, self::TABLE_PROFILE, self::TABLE_ACCOUNT);

        $columns = "
        	agr.affiliate_goal_response_id, agr.url, agr.response_date,
			ag.name AS goal_name, ag.url AS goal_url,
			a.name AS affiliate_name, a.affiliate_id,
			p.account_id, p.first_name, p.last_name, acc.created_date
        ";

        $where = "";
        $conditions = array();
        $order = "ORDER BY agr.affiliate_goal_response_id DESC";
        $bind = array();

        if(!empty($params["first_name"])) {
            $conditions[] = "p.first_name LIKE ?";
            $bind[] = "%" . $params["first_name"] . "%";
        }
        if(!empty($params["last_name"])) {
            $conditions[] = "p.last_name LIKE ?";
            $bind[] = "%" . $params["last_name"] . "%";
        }
        if(!empty($params["affiliate_name"])) {
            $conditions[] = "a.name LIKE ?";
            $bind[] = "%" . $params["affiliate_name"] . "%";
        }
        if(!empty($params["response_date_from"])) {
            $conditions[] = "agr.response_date > ?";
            $bind[] = $params["response_date_from"];
        }
        if(!empty($params["response_date_to"])) {
            $conditions[] = "agr.response_date <= ?";
            $bind[] = $params["response_date_to"];
        }


        // Where
        if(!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }


        // Limit
        if(!empty($limit)) {
            $limit = "LIMIT $limit";
        }


        // Count
        $sql = sprintf("SELECT COUNT(agr.affiliate_goal_response_id) AS count FROM %s %s", $tables, $where);
        $resultSet = $this->query($sql, $bind);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $result["count"] = $row["count"];
        }


        // Data
        $sql = sprintf("SELECT %s FROM %s %s %s %s", $columns, $tables, $where, $order, $limit);
        $resultSet = $this->query($sql, $bind);
		foreach ($resultSet as $row) {
			$result["data"][$row->affiliate_goal_response_id] = (array) $row;
			$result["data"][$row->affiliate_goal_response_id]["params"] = array();
		}


		// Get Params
		if (!empty($result["data"])) {
			$affiliateGoalResponseIds = array_keys($result["data"]);

			$sql = sprintf("
				SELECT affiliate_goal_response_id, name, value
				FROM %s
				WHERE affiliate_goal_response_id IN(" . implode(",", $affiliateGoalResponseIds) . ")
			", self::TABLE_AFFILIATE_GOAL_RESPONSE_DATA);

			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$result["data"][$row->affiliate_goal_response_id]["params"][] = array("name" => $row->name, "value" => $row->value);
			}
		}

		return $result;
	}


	private function saveAffiliate(Affiliate $affiliate, $goals = array(), $insert = true) {
		$result = 0;

		$affiliateId = $affiliate->getAffiliateId();
		$data = $affiliate->toArray();
		unset($data["affiliate_id"]);


		try {
			$this->beginTransaction();

			if ($insert == true) {
				$data["api_key"] = substr(base_convert(md5($data["name"] . time()), 16, 32), 0, 8);

				$this->insert(self::TABLE_AFFILIATE, $data);
				$affiliateId = $this->getLastInsertId();

				$result = $affiliateId;
			}
			else {
				unset($data["api_key"]);
				$result = $this->update(self::TABLE_AFFILIATE, $data, array("affiliate_id" => $affiliateId));
			}

			if (!empty($goals)) {
				$goal = $goals[0];

				$affiliateGoalId = $goal->getAffiliateGoalId();
				$data = $goal->toArray();

				if (empty($data["logo"])) {
					unset($data["logo"]);
				}

				$data["affiliate_id"] = $affiliateId;
				unset($data["affiliate_goal_id"]);

				if ($insert == true) {
					$this->insert(self::TABLE_AFFILIATE_GOAL, $data);
				}
				else {
					$this->update(self::TABLE_AFFILIATE_GOAL, $data, array("affiliate_goal_id" => $affiliateGoalId));
				}
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

}
