<?php

/**
 * SearchService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	17. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;

use App\Entity\Account;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use ScholarshipOwl\Data\Entity\Scholarship\Application;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\AbstractService;


class SearchService extends AbstractService implements ISearchService {
	public function searchScholarships($params = array(), $limit = "", $allColumns = false) {
		$result = array(
			"count" => 0,
			"data" => array()
		);

		$table = self::TABLE_SCHOLARSHIP;
		$columns = ($allColumns == true) ? "*" : "scholarship_id, title, application_type, amount, is_free, is_active, is_recurrent, url, expiration_date, status";
		$where = "";
		$conditions = array();
		$order = "ORDER BY status ASC, is_active DESC, scholarship_id DESC ";
		$bind = array();


		// Conditions
		if(!empty($params["scholarship_id"])) {
			$conditions[] = "scholarship_id IN(" . implode(array_fill(0, count($params["scholarship_id"]), "?"), ",") . ")";
			foreach($params["scholarship_id"] as $param) {
				$bind[] = $param;
			}
		}
		if(!empty($params["title"])) {
			$conditions[] = "title LIKE ?";
			$bind[] = "%" . $params["title"] . "%";
		}
        if (!empty($params['status']) && is_numeric($params['status'])) {
            $conditions[] = "status = ?";
            $bind[] = $params['status'];
        }
		if(!empty($params["description"])) {
			$conditions[] = "description LIKE ?";
			$bind[] = "%" . $params["description"] . "%";
		}
		if(!empty($params["expiration_date_from"])) {
			$conditions[] = "expiration_date > ?";
			$bind[] = $params["expiration_date_from"];
		}
		if(!empty($params["expiration_date_to"])) {
			$conditions[] = "expiration_date <= ?";
			$bind[] = $params["expiration_date_to"];
		}
		if(!empty($params["amount_min"]) && is_numeric($params["amount_min"])) {
			$conditions[] = "amount > ?";
			$bind[] = $params["amount_min"];
		}
		if(!empty($params["amount_max"]) && is_numeric($params["amount_max"])) {
			$conditions[] = "amount <= ?";
			$bind[] = $params["amount_max"];
		}
		if(!empty($params["up_to_min"]) && is_numeric($params["up_to_min"])) {
			$conditions[] = "up_to > ?";
			$bind[] = $params["up_to_min"];
		}
		if(!empty($params["up_to_max"]) && is_numeric($params["up_to_max"])) {
			$conditions[] = "up_to <= ?";
			$bind[] = $params["up_to_max"];
		}
		if(!empty($params["application_type"])) {
			$conditions[] = "application_type = ?";
			$bind[] = $params["application_type"];
		}
		if(isset($params["is_active"]) && is_numeric($params["is_active"])) {
			$conditions[] = "is_active = ?";
			$bind[] = $params["is_active"];
		}
		if(isset($params["is_free"]) && is_numeric($params["is_free"])) {
			$conditions[] = "is_free = ?";
			$bind[] = $params["is_free"];
		}
		if(isset($params["is_recurrent"]) && is_numeric($params["is_recurrent"])) {
			$conditions[] = "is_recurrent = ?";
			$bind[] = $params["is_recurrent"];
		}

		if(!empty($conditions)) {
			$where = "WHERE " . implode(" AND ", $conditions);
		}


		// Limit
		if(!empty($limit)) {
			$limit = "LIMIT $limit";
		}


		// Count
		$sql = sprintf("SELECT COUNT(scholarship_id) AS count FROM %s %s", $table, $where);
		$resultSet = $this->query($sql, $bind);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result["count"] = $row["count"];
		}

		// Data
		$sql = sprintf("SELECT %s FROM %s %s %s %s", $columns, $table, $where, $order, $limit);
		$resultSet = $this->query($sql, $bind);
		foreach($resultSet as $row) {
			$entity = new Scholarship();
			$entity->populate((array) $row);

			$result["data"][$entity->getScholarshipId()] = $entity;
		}

		return $result;
	}

    public function searchApplications($params = array(), $limit = "", $asResultSet = false) {
        $result = array(
            "count" => 0,
            "data" => array()
        );
        $criteria = new Criteria();
        // Conditions
        if (!empty($params["account_id"])) {
            $criteria->andWhere(Criteria::expr()->eq("a.account", $params["account_id"]));
        }
        if (!empty($params["first_name"])) {
            $criteria->andWhere(Criteria::expr()->contains("p.firstName", $params["first_name"]));
        }
        if (!empty($params["last_name"])) {
            $criteria->andWhere(Criteria::expr()->contains("p.lastName", $params["last_name"]));
        }
        if (!empty($params["scholarship_id"])) {
            $criteria->andWhere(Criteria::expr()->eq("a.scholarship", $params["scholarship_id"]));
        }
        if (!empty($params["title"])) {
            $criteria->andWhere(Criteria::expr()->contains("s.title", $params["title"]));
        }
        if (!empty($params["expiration_date_from"])) {
            $criteria->andWhere(Criteria::expr()->gte("s.expirationDate", $params["send_date_from"]));
        }
        if (!empty($params["expiration_date_to"])) {
            $criteria->andWhere(Criteria::expr()->lte("s.expirationDate", $params["send_date_to"]));
        }
        if (!empty($params["date_applied_from"])) {
            $criteria->andWhere(Criteria::expr()->gte("a.dateApplied", $params["date_applied_from"]));
        }
        if (!empty($params["date_applied_to"])) {
            $criteria->andWhere(Criteria::expr()->lte("a.dateApplied", $params["date_applied_to"]));
        }
        if (!empty($params["application_status_id"])) {
            $criteria->andWhere(Criteria::expr()->in("a.applicationStatus", $params["application_status_id"]));
        }
        if (!empty($params["application_type"])) {
            $criteria->andWhere(Criteria::expr()->in("s.applicationType", $params["application_type"]));
        }

        $query = \App\Facades\EntityManager::getRepository(\App\Entity\Application::class)->createQueryBuilder("a")
            ->orderBy("a.dateApplied", "desc");

        if($criteria->getWhereExpression()) {
            $query->join(\App\Entity\Profile::class, "p", 'WITH', 'p.account = a.account')
            ->leftJoin(\App\Entity\Scholarship::class, "s", 'WITH', 'a.scholarship = s.scholarshipId')
            ->addCriteria($criteria);
        }

        if(!empty($limit)){
            $lim = explode(',', $limit);
            $query->setFirstResult($lim[0])->setMaxResults($lim[1]);
        }

        $applications = new Paginator($query->getQuery(), true);

        $res = $applications->getQuery()->getResult();
        $result["data"] = $res;

        if ($asResultSet == true) {
            $result = $res;
        }
        else {
            $result["count"] = $applications->count();
        }

        return $result;
    }
}
