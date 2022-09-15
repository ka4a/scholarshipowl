<?php

/**
 * MarketingService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Igor Savin <zbuntt@gmail.com>
 *
 * @created    	13. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Marketing\MarketingSystem;
use ScholarshipOwl\Data\Entity\Marketing\MarketingSystemAccount;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Data\Service\Marketing\IMarketingSystemService;


class MarketingSystemService extends AbstractService implements IMarketingSystemService {
	public function setMarketingSystemAccount(MarketingSystemAccount $marketingSystemAccount) {
		$result = 0;

		try {
			$isSaved = 0;

			// Check If Saved
			$sql = sprintf("SELECT COUNT(account_id) AS count FROM %s WHERE account_id = ?", self::TABLE_MARKETING_SYSTEM_ACCOUNT);
			$resultSet = $this->query($sql, array($marketingSystemAccount->getAccountId()));
			foreach ($resultSet as $row) {
				$isSaved = $row->count;
			}

			if ($isSaved == 0) {
				$this->beginTransaction();

				$accountId = $marketingSystemAccount->getAccountId();
				$marketingSystemId = $marketingSystemAccount->getMarketingSystem()->getMarketingSystemId();

				// Save Marketing System Account
				$data = array(
					"account_id" => $accountId,
					"marketing_system_id" => $marketingSystemId,
					"conversion_date" => date("Y-m-d H:i:s"),
				);

				$this->insert(self::TABLE_MARKETING_SYSTEM_ACCOUNT, $data);

				// Save Marketing System Account Data
				$this->setMarketingSystemAccountData($accountId, $marketingSystemAccount);

				$this->commit();
			}
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

    /**
     * @param      $accountId
     * @param bool $data
     *
     * @return MarketingSystemAccount
     */
	public function getMarketingSystemAccount($accountId, $data = true) {
		$result = new MarketingSystemAccount();

		$sql = sprintf("SELECT account_id, marketing_system_id, conversion_date FROM %s WHERE account_id = ?", self::TABLE_MARKETING_SYSTEM_ACCOUNT);
		$resultSet = $this->query($sql, array($accountId));

		foreach ($resultSet as $row) {
			$row = (array) $row;
			$result->populate($row);
		}

		if ($data == true) {
			$sql = sprintf("SELECT name, value FROM %s WHERE account_id = ?", self::TABLE_MARKETING_SYSTEM_ACCOUNT_DATA);
			$resultSet = $this->query($sql, array($accountId));

			foreach ($resultSet as $row) {
				$result->addData($row->name, $row->value);
			}
		}
		return $result;
	}

	public function setMarketingSystemAccountData($accountId, MarketingSystemAccount $marketingSystemAccount) {
		try {
			$data = $marketingSystemAccount->getData();
			if(!empty($data)) {
				$columns = array("account_id", "name", "value");
				$bulkData = array();

				// Last Minute Change - Lack Of Documentation
				$nonTracked = array("agree_promotions", "agree_terms", "email", "first_name", "last_name", "phone", "_return", "_token");
				$savedData = $this->getMarketingSystemAccount($accountId)->getData();

				foreach($data as $name => $value) {
					if(!in_array($name, $nonTracked) && !isset($savedData[$name])) {
						$bulkData[] = array($accountId, $name, $value);
					}
				}

				if(!empty($bulkData)) {
					$this->insertBulk(self::TABLE_MARKETING_SYSTEM_ACCOUNT_DATA, $columns, $bulkData);
					$this->commit();
				}
			}
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}
	}

	public function search($params = array(), $limit = "") {
		$result = array(
			"data" => array(),
			"count" => 0
		);

		$tables = sprintf("
			%s AS msa 
			JOIN %s AS p ON msa.account_id = p.account_id
		", self::TABLE_MARKETING_SYSTEM_ACCOUNT, self::TABLE_PROFILE);

		$columns = "msa.account_id, msa.marketing_system_id, msa.conversion_date, p.first_name, p.last_name";
		$order = "ORDER BY msa.account_id DESC";

		$where = "";
		$conditions = array();
		$bind = array();


		if(!empty($params["marketing_system_id"])) {
			$conditions[] = "msa.marketing_system_id IN(" . implode(array_fill(0, count($params["marketing_system_id"]), "?"), ",") . ")";
			foreach($params["marketing_system_id"] as $param) {
				$bind[] = $param;
			}
		}

		if(!empty($params["conversion_date_from"])) {
            $conditions[] = "msa.conversion_date > ?";
            $bind[] = $params["conversion_date_from"];
        }

        if(!empty($params["conversion_date_to"])) {
            $conditions[] = "msa.conversion_date <= ?";
            $bind[] = $params["conversion_date_to"];
        }

        if(!empty($params["transaction_id"]) || !empty($params["offer_id"]) || !empty($params["affiliate_id"])) {
        	$subQuery = "msa.account_id IN (SELECT account_id FROM " . self::TABLE_MARKETING_SYSTEM_ACCOUNT_DATA . " AS msad";
        	$subQueryConditions = array();

        	if(!empty($params["transaction_id"])) {
        		$subQueryConditions[] = sprintf("(msad.name = '%s' AND msad.value = ?)", MarketingSystemAccount::HAS_OFFERS_URL_PARAM_TRANSACTION_ID);
        		$bind[] = $params["transaction_id"];
        	}

        	if(!empty($params["offer_id"])) {
        		$subQueryConditions[] = sprintf("(msad.name = '%s' AND msad.value = ?)", MarketingSystemAccount::HAS_OFFERS_URL_PARAM_OFFER_ID);
        		$bind[] = $params["offer_id"];
        	}

        	if(!empty($params["affiliate_id"])) {
        		$subQueryConditions[] = sprintf("(msad.name = '%s' AND msad.value = ?)", MarketingSystemAccount::HAS_OFFERS_URL_PARAM_AFFILIATE_ID);
        		$bind[] = $params["affiliate_id"];
        	}

        	if(!empty($subQueryConditions)) {
        		$subQuery .= " WHERE " . implode(" AND ", $subQueryConditions);
        	}

        	$subQuery .= ")";
        	$conditions[] = $subQuery;
        }


		if(!empty($conditions)) {
			$where = "WHERE " . implode(" AND ", $conditions);
		}

		if(!empty($limit)) {
			$limit = "LIMIT $limit";
		}

		// Count
		$sql = sprintf("SELECT COUNT(msa.account_id) AS count FROM %s %s", $tables, $where);
		$resultSet = $this->query($sql, $bind);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result["count"] = $row["count"];
		}

		// Data
		$sql = sprintf("SELECT %s FROM %s %s %s %s", $columns, $tables, $where, $order, $limit);
		$resultSet = $this->query($sql, $bind);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$account = new MarketingSystemAccount();
			$account->populate($row);

			$result["data"][$row["account_id"]] = $account;
		}

		// Parameters
		$accountIds = array_keys($result["data"]);
		if (!empty($accountIds)) {
			$sql = sprintf("SELECT account_id, name, value FROM %s WHERE account_id IN(%s)", self::TABLE_MARKETING_SYSTEM_ACCOUNT_DATA, implode(",", $accountIds));
			$resultSet = $this->query($sql);

			foreach($resultSet as $row) {
				$row = (array) $row;
				$result["data"][$row["account_id"]]->addData($row["name"], $row["value"]);
			}
		}

		return $result;
	}

	public function getMarketingSystemParametersByAccountIds($accountIds) {
		$result = array();

		$sql = sprintf("
			SELECT account_id, name, value FROM %s WHERE account_id IN(%s)
		", self::TABLE_MARKETING_SYSTEM_ACCOUNT_DATA, implode(",", $accountIds));

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			if (!array_key_exists($row->account_id, $result)) {
				$result[$row->account_id] = array();
			}

			$result[$row->account_id][$row->name] = $row->value;
		}

		return $result;
	}
}
