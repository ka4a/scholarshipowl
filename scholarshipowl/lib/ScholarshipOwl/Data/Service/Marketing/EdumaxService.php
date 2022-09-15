<?php

namespace ScholarshipOwl\Data\Service\Marketing;

use App\Facades\EntityManager;
use Carbon\Carbon;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Service\AbstractService;


class EdumaxService extends AbstractService
{
    public function searchAccounts($params = array(), $limit = "")
    {
        $result = array(
            "count" => 0,
            "data" => array()
        );

        $tables = sprintf("%s AS a JOIN %s AS p ON a.account_id = p.account_id", self::TABLE_ACCOUNT,
            self::TABLE_PROFILE);
        $columns = "a.account_id, a.domain_id, a.created_date, a.email, a.last_updated_date, p.*";
        $where = "";
        $conditions = array();
        $order = "ORDER BY a.account_id DESC";
        $group = 'GROUP BY a.account_id';
        $bind = array();

        $joinSubscription = "";

        //  Default eligibility conditions
        $conditions[] = "p.school_level_id > 4";
        $conditions[] = "p.enrolled = 0";
        $conditions[] = "p.date_of_birth < ?";
        $bind[] = Carbon::now()->subYears(18)->toDateTimeString();

        // Conditions
        if (!empty($params['domain'])) {
            $conditions[] = 'a.domain_id = ?';
            $bind[] = $params['domain'];
        }
        if (!empty($params["created_date_from"])) {
            $conditions[] = "a.created_date > ?";
            $bind[] = $params["created_date_from"];
        }
        if (!empty($params["created_date_to"])) {
            $conditions[] = "a.created_date <= ?";
            $bind[] = $params["created_date_to"];
        }
        if (!empty($params["school_level_id"])) {
            $conditions[] = "p.school_level_id IN(" . implode(array_fill(0, count($params["school_level_id"]), "?"),
                    ",") . ")";
            foreach ($params["school_level_id"] as $param) {
                $bind[] = $param;
            }
        }
        if (!empty($params["degree_id"])) {
            $conditions[] = "p.degree_id IN(" . implode(array_fill(0, count($params["degree_id"]), "?"), ",") . ")";
            foreach ($params["degree_id"] as $param) {
                $bind[] = $param;
            }
        }
        if (!empty($params["degree_type_id"])) {
            $conditions[] = "p.degree_type_id IN(" . implode(array_fill(0, count($params["degree_type_id"]), "?"),
                    ",") . ")";
            foreach ($params["degree_type_id"] as $param) {
                $bind[] = $param;
            }
        }
        if (isset($params['agree_call']) && is_numeric($params['agree_call'])) {
            $conditions[] = $params['agree_call'] === '0' ? 'p.agree_call IS NULL' : 'p.agree_call = 1';
        }


        if (isset($params["has_active_subscription"])) {
            if ($params["has_active_subscription"] == "1") {
                $joinSubscription = sprintf("
					JOIN %s s ON s.account_id = a.account_id AND s.subscription_status_id = %d
					", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
                );
            } else {
                if ($params["has_active_subscription"] == "0") {
                    $conditions[] = sprintf("
					a.account_id NOT IN (
						SELECT account_id FROM %s s	WHERE s.account_id = a.account_id AND s.subscription_status_id = %d
					)",
                        self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
                    );
                }
            }
        }
        if (isset($params['paid']) && $params['paid'] !== '') {
            $conditions[] = 't.transaction_id IS ' . ($params['paid'] ? 'NOT ' : '') . 'NULL';
            $tables .= sprintf(
                ' LEFT JOIN %s subscr ON a.account_id = subscr.account_id LEFT JOIN %s t ON t.subscription_id = subscr.subscription_id',
                self::TABLE_SUBSCRIPTION,
                self::TABLE_TRANSACTION
            );
        }

        if (!empty($joinSubscription)) {
            $tables .= " " . $joinSubscription;
        }


        // Where
        if (!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }


        // Limit
        if (!empty($limit)) {
            $limit = "LIMIT $limit";
        }


        // Count
        $sql = sprintf("SELECT COUNT(DISTINCT(a.account_id)) AS count FROM %s %s", $tables, $where);
        $resultSet = $this->query($sql, $bind);
        foreach ($resultSet as $row) {
            $row = (array)$row;
            $result["count"] = $row["count"];
        }


        // Data
        // $sql = sprintf("SELECT %s FROM %s %s %s %s", $columns, $tables, $where, $order, $limit);
        $sql = sprintf("SELECT %s FROM %s %s %s %s %s", $columns, $tables, $where, $group, $order, $limit);
        $queryBuilder = EntityManager::createQueryBuilder()
        ->select('a.accountId', 'a.domain', 'a.createdDate', 'a.email', 'a.lastUpdatedDate', 'p.*')
        ->from(\App\Entity\Account::class, 'a')
        ->join(\App\Entity\Profile::class, 'p', 'p', 'a.accountId = p.accountId');
        // Conditions
        if (!empty($params['domain'])) {
            $queryBuilder->andWhere('a.domain = '.$params['domain']);
        }
        if (!empty($params["created_date_from"])) {
            $queryBuilder->andWhere('a.createdDate >  '.$params["created_date_from"]);
        }
        if (!empty($params["created_date_to"])) {
            $queryBuilder->andWhere('a.createdDate < '.$params["created_date_to"]);
        }
        if (!empty($params["school_level_id"])) {
            $param = implode(',', $params["school_level_id"]);
            $queryBuilder->andWhere('p.schoolLevel in ("'.$param.'")');
        }
        if (!empty($params["degree_id"])) {
            $param = implode(',', $params["degree_id"]);
            $queryBuilder->andWhere('p.degreeId in ("'.$param.'")');
        }
        if (!empty($params["degree_type_id"])) {
            $param = implode(',', $params["degree_type_id"]);
            $queryBuilder->andWhere('p.degreeTypeId in ("'.$param.'")');
        }
        if (isset($params['agree_call']) && is_numeric($params['agree_call'])) {
            $where = $params['agree_call'] === '0' ? 'p.agreeCall IS NULL' : 'p.agreeCall = 1';
            $queryBuilder->andWhere($where);
        }


        if (isset($params["has_active_subscription"])) {
            $queryBuilder->leftJoin(\App\Entity\Subscription::class, 's', 'p', 's.accountId = a.accountId');

            if ($params["has_active_subscription"] == "1") {
                $queryBuilder->andWhere('s.subscriptionStatus = 1');
            } else {
                $queryBuilder->andWhere('s.subscriptionStatus = 0');
            }
        }
        if (isset($params['paid']) && $params['paid'] !== '') {
            $queryBuilder->join(\App\Entity\Subscription::class, 's', 'p', 's.accountId = a.accountId');
            $queryBuilder->join(\App\Entity\Transaction::class, 't', 'p', 't.subscription = s.subscriptionId');
            $queryBuilder->andWhere('s.subscriptionStatus = 1');
            $queryBuilder->andWhere('t.subscriptionStatus = 1');

            $conditions[] = 't.transaction_id IS ' . ($params['paid'] ? 'NOT ' : '') . 'NULL';
            $tables .= sprintf(
                ' LEFT JOIN %s subscr ON a.account_id = subscr.account_id LEFT JOIN %s t ON t.subscription_id = subscr.subscription_id',
                self::TABLE_SUBSCRIPTION,
                self::TABLE_TRANSACTION
            );
        }

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @param Profile $profile
     */
    public function formatCategoryId($profile)
    {
        switch (intval($profile->getDegree()->getDegreeId())) {
            case 1:
            case 2:
            case 3:
            case 8:
            case 10:
            case 12:
            case 13:
            case 15:
            case 17:
            case 18:
            case 19:
            case 21:
            case 22:
            case 23:
            case 24:
            case 27:
            case 28:
            case 30:
            case 31:
            case 32:
            case 33:
            case 35:
            case 36:
            case 37:
                return false;
                break;
            case 4:
            case 14:
                return 1;
                break;
            case 5:
                return 5;
                break;
            case 6:
            case 29:
                return 9;
                break;
            case 7:
                return 6;
                break;
            case 9:
            case 11:
                return 2;
                break;
            case 16:
                return 4;
                break;
            case 20:
                return 7;
                break;
            case 25:
                return 12;
                break;
            case 26:
            case 34:
                return 11;
                break;
        }
    }

    public function formatMilitaryStatus($militaryAffiliationId)
    {
        $activeIds = [1, 2, 3, 4, 5, 6, 7, 8];
        $veteranIds = [14, 17, 20, 23, 26];
        $dependentIds = [9, 10, 12, 13, 15, 16, 18, 19, 21, 22, 24, 25, 27, 28];
        $reserveIds = [11];

        if (in_array($militaryAffiliationId, $activeIds)) {
            return "Active Duty";
        } else {
            if (in_array($militaryAffiliationId, $veteranIds)) {
                return "Veteran";
            } else {
                if (in_array($militaryAffiliationId, $reserveIds)) {
                    return "Reserves";
                } else {
                    if (in_array($militaryAffiliationId, $dependentIds)) {
                        if (in_array($militaryAffiliationId, [9, 12, 15, 18, 21, 24, 27])) {
                            return "Dependent";
                        }
                        return "Spouse";
                    } else {
                        return "No U.S. Military Affiliation";
                    }
                }
            }
        }
    }

    public function formatMilitaryAffiliation($militaryAffiliationId)
    {
        if ($militaryAffiliationId == 1) {
            return ["name" => "militaryaffiliation", "value" => "U.S. Army"];
        } else {
            if ($militaryAffiliationId == 2) {
                return ["name" => "militaryaffiliation", "value" => "U.S. Navy"];
            } else {
                if ($militaryAffiliationId == 3) {
                    return ["name" => "militaryaffiliation", "value" => "U.S. Air Force"];
                } else {
                    if ($militaryAffiliationId == 4) {
                        return ["name" => "militaryaffiliation", "value" => "U.S. Marine Corps"];
                    } else {
                        if ($militaryAffiliationId == 6) {
                            return ["name" => "militaryaffiliation", "value" => "U.S. Coast Guard"];
                        }
                    }
                }
            }
        }
        return false;
    }

    public function formatCitizenship($citizenshipId)
    {
        switch ($citizenshipId) {
            case 1:
                return "Y";
                break;
            default:
                return "N";
                break;
        }
    }
}
