<?php

/**
 * SearchService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	21. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Service\AbstractService;


class SearchService extends AbstractService implements ISearchService {
	public function searchAccounts($params = array(), $limit = "") {
		$result = array(
			"count" => 0,
			"data" => array()
		);

		$tables = sprintf("%s AS a JOIN %s AS p ON a.account_id = p.account_id", self::TABLE_ACCOUNT, self::TABLE_PROFILE);
		//$columns = "a.account_id, a.domain_id, a.zendesk_user_id, a.account_status_id, a.account_type_id, a.username, a.password, a.email, a.created_date, a.last_updated_date, p.*";
		$columns="a.account_id";

		$where = "";
		$conditions = array();
		$order = "ORDER BY a.account_id DESC";
        $group = 'GROUP BY a.account_id';
		$bind = array();

		$joinSubscription = "";
		$joinLoginHistory = false;


		// Conditions
		if(!empty($params["email"])) {
			$conditions[] = "a.email = ?";
			$bind[] = $params["email"];
		}
        if(!empty($params['domain'])) {
            $conditions[] = 'a.domain_id = ?';
            $bind[] = $params['domain'];
        }
		if(!empty($params["username"])) {
			$conditions[] = "a.username LIKE ?";
			$bind[] = "%" . $params["username"] . "%";
		}
		if(!empty($params["account_status_id"])) {
			$conditions[] = "a.account_status_id IN(" . implode(array_fill(0, count($params["account_status_id"]), "?"), ",") . ")";
			foreach($params["account_status_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["account_type_id"])) {
			$conditions[] = "a.account_type_id IN(" . implode(array_fill(0, count($params["account_type_id"]), "?"), ",") . ")";
			foreach($params["account_type_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["account_pro"])) {
			$conditions[] = "p.pro = ?";
			$bind[] = $params["account_pro"];
		}
		if(!empty($params["first_name"])) {
			$conditions[] = "p.first_name LIKE ?";
			$bind[] = "%" . $params["first_name"] . "%";
		}
		if(!empty($params["last_name"])) {
			$conditions[] = "p.last_name LIKE ?";
			$bind[] = "%" . $params["last_name"] . "%";
		}
		if(!empty($params["phone"])) {
			$conditions[] = "p.phone LIKE ?";
			$bind[] = "%" . $params["phone"] . "%";
		}
		if(!empty($params["created_date_from"])) {
			$conditions[] = "a.created_date > ?";
			$bind[] = $params["created_date_from"];
		}
		if(!empty($params["created_date_to"])) {
			$conditions[] = "a.created_date <= ?";
			$bind[] = $params["created_date_to"];
		}
		if(!empty($params["date_of_birth_from"])) {
			$conditions[] = "p.date_of_birth > ?";
			$bind[] = $params["date_of_birth_from"];
		}
		if(!empty($params["date_of_birth_to"])) {
			$conditions[] = "p.date_of_birth <= ?";
			$bind[] = $params["date_of_birth_to"];
		}
		if(!empty($params["school_level_id"])) {
			$conditions[] = "p.school_level_id IN(" . implode(array_fill(0, count($params["school_level_id"]), "?"), ",") . ")";
			foreach($params["school_level_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["degree_id"])) {
			$conditions[] = "p.degree_id IN(" . implode(array_fill(0, count($params["degree_id"]), "?"), ",") . ")";
			foreach($params["degree_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["degree_type_id"])) {
			$conditions[] = "p.degree_type_id IN(" . implode(array_fill(0, count($params["degree_type_id"]), "?"), ",") . ")";
			foreach($params["degree_type_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["gpa"])) {
			$conditions[] = "p.gpa IN(" . implode(array_fill(0, count($params["gpa"]), "?"), ",") . ")";
			foreach($params["gpa"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["enrollment_year"])) {
			$conditions[] = "p.enrollment_year IN(" . implode(array_fill(0, count($params["enrollment_year"]), "?"), ",") . ")";
			foreach($params["enrollment_year"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["enrollment_month"])) {
			$conditions[] = "p.enrollment_month IN(" . implode(array_fill(0, count($params["enrollment_month"]), "?"), ",") . ")";
			foreach($params["enrollment_month"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["highschool"])) {
			$conditions[] = "p.highschool LIKE ?";
			$bind[] = "%" . $params["highschool"] . "%";
		}
		if(!empty($params["university"])) {
			$conditions[] = "p.university LIKE ?";
			$bind[] = "%" . $params["university"] . "%";
		}
		if(!empty($params["graduation_year"])) {
			$conditions[] = "p.graduation_year IN(" . implode(array_fill(0, count($params["graduation_year"]), "?"), ",") . ")";
			foreach($params["graduation_year"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["graduation_month"])) {
			$conditions[] = "p.graduation_month IN(" . implode(array_fill(0, count($params["graduation_month"]), "?"), ",") . ")";
			foreach($params["graduation_month"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["highschool_graduation_year"])) {
			$conditions[] = "p.highschool_graduation_year IN(" . implode(array_fill(0, count($params["highschool_graduation_year"]), "?"), ",") . ")";
			foreach($params["highschool_graduation_year"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["highschool_graduation_month"])) {
			$conditions[] = "p.highschool_graduation_month IN(" . implode(array_fill(0, count($params["highschool_graduation_month"]), "?"), ",") . ")";
			foreach($params["highschool_graduation_month"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["gender"])) {
			$conditions[] = "p.gender = ?";
			$bind[] = $params["gender"];
		}
		if(!empty($params["citizenship_id"])) {
			$conditions[] = "p.citizenship_id IN(" . implode(array_fill(0, count($params["citizenship_id"]), "?"), ",") . ")";
			foreach($params["citizenship_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["ethnicity_id"])) {
			$conditions[] = "p.ethnicity_id IN(" . implode(array_fill(0, count($params["ethnicity_id"]), "?"), ",") . ")";
			foreach($params["ethnicity_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(isset($params["is_subscribed"])) {
			if(in_array($params["is_subscribed"], array("0", "1"))) {
				$conditions[] = "p.is_subscribed = ?";
				$bind[] = $params["is_subscribed"];
			}
		}
		if(!empty($params["country_id"])) {
			$conditions[] = "p.country_id IN(" . implode(array_fill(0, count($params["country_id"]), "?"), ",") . ")";
			foreach($params["country_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["state_id"])) {
			$conditions[] = "p.state_id IN(" . implode(array_fill(0, count($params["state_id"]), "?"), ",") . ")";
			foreach($params["state_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["city"])) {
			$conditions[] = "p.city LIKE ?";
			$bind[] = "%" . $params["city"] . "%";
		}
		if(!empty($params["address"])) {
			$conditions[] = "p.address LIKE ?";
			$bind[] = "%" . $params["address"] . "%";
		}
		if(!empty($params["zip"])) {
			$conditions[] = "p.zip LIKE ?";
			$bind[] = "%" . $params["zip"] . "%";
		}
		if(!empty($params["career_goal_id"])) {
			$conditions[] = "p.career_goal_id IN(" . implode(array_fill(0, count($params["career_goal_id"]), "?"), ",") . ")";
			foreach($params["career_goal_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["study_online"])) {
			$conditions[] = "p.study_online IN(" . implode(array_fill(0, count($params["study_online"]), "?"), ",") . ")";
			foreach($params["study_online"] as $param) {
				$bind[] = $param;
			}
		}
		if(is_numeric($params["military_affiliation_id"])) {
			$conditions[] = "p.military_affiliation_id = ?";
			$bind[] = $params["military_affiliation_id"];
		}
        if (isset($params['agree_call']) && is_numeric($params['agree_call'])) {
            $conditions[] = $params['agree_call'] === '0' ? 'p.agree_call IS NULL' : 'p.agree_call = 1';
        }
        if(!empty($params["profile_type"])) {
            $conditions[] = (in_array('null', $params["profile_type"]) ? "p.profile_type IS NULL OR " : "") . "p.profile_type IN(" . implode(array_fill(0, count($params["profile_type"]), "?"), ",") . ")";
            foreach($params["profile_type"] as $param) {
                $bind[] = $param;
            }
        }


		if(isset($params["has_active_subscription"])) {
			if ($params["has_active_subscription"] == "1") {
				$joinSubscription = sprintf("
					JOIN %s s ON s.account_id = a.account_id AND s.subscription_status_id = %d
					", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
				);
			}
			else if ($params["has_active_subscription"] == "0") {
				$conditions[] = sprintf("
					a.account_id NOT IN (
						SELECT account_id FROM %s s	WHERE s.account_id = a.account_id AND s.subscription_status_id = %d
					)",
                    self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
				);
			}
		}
        if (isset($params['paid']) && $params['paid'] !== '') {
            $conditions[] = 't.transaction_id IS ' .($params['paid'] ? 'NOT ' : ''). 'NULL';
            $tables .= sprintf(
                ' 
                LEFT JOIN %s subscr ON a.account_id = subscr.account_id 
                    AND (subscr.subscription_status_id = %d OR subscr.active_until > NOW())
                LEFT JOIN %s t ON t.subscription_id = subscr.subscription_id
                ',
                self::TABLE_SUBSCRIPTION,
                SubscriptionStatus::ACTIVE,
                self::TABLE_TRANSACTION
            );
        }
		if(!empty($params["package_id"])) {
			if (empty($joinSubscription)) {
				$joinSubscription = sprintf("
					 JOIN %s s ON s.account_id = a.account_id
					 AND s.subscription_status_id = %d 
					", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
				);
			}

			$joinSubscription .= sprintf("
				JOIN (
					SELECT account_id, MIN(priority) AS priority
					FROM %s 
					WHERE subscription_status_id = %d
					GROUP BY account_id	
				) AS s2 ON s.account_id = s2.account_id AND s.priority = s2.priority
			", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE);

			$conditions[] = "s.package_id IN(" . implode(array_fill(0, count($params["package_id"]), "?"), ",") . ")";
			foreach($params["package_id"] as $param) {
				$bind[] = $param;
			}
		}

		// Login History
		if(!empty($params["login_ip"]) || !empty($params["login_action"]) || !empty($params["login_date_from"]) || !empty($params["login_date_to"])) {
			$subQuery = "a.account_id IN (SELECT lh.account_id FROM " . self::TABLE_LOGIN_HISTORY . " AS lh";
			$subQueryConditions = array();

			if(!empty($params["login_ip"])) {
				$subQueryConditions[] = "lh.ip_address LIKE ?";
				$bind[] = "%" . $params["login_ip"] . "%";
			}
			if(!empty($params["login_action"])) {
				$subQueryConditions[] = "lh.action IN(" . implode(array_fill(0, count($params["login_action"]), "?"), ",") . ")";
				foreach($params["login_action"] as $param) {
					$bind[] = $param;
				}
			}
			if(!empty($params["login_date_from"])) {
				$subQueryConditions[] = "lh.action_date > ?";
				$bind[] = $params["login_date_from"];
			}
			if(!empty($params["login_date_to"])) {
				$subQueryConditions[] = "lh.action_date <= ?";
				$bind[] = $params["login_date_to"];
			}

			if(!empty($subQueryConditions)) {
				$subQuery .= " WHERE " . implode(" AND ", $subQueryConditions);
			}

			$subQuery .= ")";
			$conditions[] = $subQuery;
		}

		if(!empty($joinSubscription)) {
			$tables .= " " . $joinSubscription;
		}

		$where = 'WHERE a.deleted_at IS NULL';

		if(!empty($conditions)) {
			$where .= ' AND '.implode(' AND ', $conditions);
		}

		// Limit
		if(!empty($limit)) {
			$limit = "LIMIT $limit";
		}

		$notEmptyParams = array_filter($params);
        if (count($notEmptyParams) === 1 && array_key_exists('domain', $notEmptyParams)) {
            $resultSet = $this->query(
                "SELECT COUNT(account_id) AS count FROM account WHERE domain_id = :domain_id AND deleted_at IS NULL",
                [':domain_id' => $notEmptyParams['domain']]
            );
        }
		else {
            $sql = sprintf("SELECT COUNT(DISTINCT(a.account_id)) AS count FROM %s %s", $tables, $where);
            $resultSet = $this->query($sql, $bind);
        }

        foreach ($resultSet as $row) {
            $row = (array)$row;
            $result["count"] = $row["count"];
        }

		// Data
        $sql = sprintf("SELECT %s FROM %s %s %s %s %s", $columns, $tables, $where, $group, $order, $limit);

		$resultSet = $this->query($sql, $bind);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result["data"][$row['account_id']] = $row['account_id'];
		}

		return $result;
	}
}
