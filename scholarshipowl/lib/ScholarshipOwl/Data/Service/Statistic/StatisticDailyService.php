<?php

/**
 * StatisticDailyService
 *
 * @package     ScholarshipOwl\Data\Service\Statistic
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	03. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Statistic;

use ScholarshipOwl\Data\Entity\Account\LoginHistory;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Entity\Payment\TransactionStatus;
use ScholarshipOwl\Data\Entity\Account\AccountType;
use ScholarshipOwl\Data\Entity\Statistic\StatisticDaily;
use ScholarshipOwl\Data\Entity\Statistic\StatisticDailyType;
use ScholarshipOwl\Data\Service\AbstractService;


class StatisticDailyService extends AbstractService implements IStatisticDailyService {

    /**
     * @var string
     */
    protected $date;

    public function __construct(\DateTime $date = null)
    {
        $date = ($date ?: new \DateTime());

        $this->date = $date->format('Y-m-d');
    }

    public function search($params = array(), $limit = "") {
		$result = array(
			"data" => array(),
			"count" => 0
		);

        $where = "";
		$conditions = array();
		$bind = array();

        if(!empty($params["statistic_daily_type_id"])) {
            $conditions[] = "statistic_daily_type_id IN(" . implode(array_fill(0, count($params["statistic_daily_type_id"]), "?"), ",") . ")";
            foreach($params["statistic_daily_type_id"] as $param) {
                $bind[] = $param;
            }
        }

        if(!empty($params["statistic_daily_date_from"]) && !empty($params["statistic_daily_date_to"])){
            if($params["statistic_daily_date_from"] > $params["statistic_daily_date_to"]){
                $tmp = $params["statistic_daily_date_from"];
                $params["statistic_daily_date_from"] = $params["statistic_daily_date_to"];
                $params["statistic_daily_date_to"] = $tmp;
            }
        }

        if(!empty($params["statistic_daily_date_from"])) {
            $conditions[] = "statistic_daily_date > ?";
            $bind[] = $params["statistic_daily_date_from"];
        }

        if(!empty($params["statistic_daily_date_to"])) {
            $conditions[] = "statistic_daily_date <= ?";
            $bind[] = $params["statistic_daily_date_to"];
        }

        if(!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }

        if(!empty($limit)) {
        	$limit = "LIMIT $limit";
        }

        $ordering = "ORDER BY statistic_daily_date DESC, statistic_daily_type_id";

        // Count
        $sql = sprintf("SELECT COUNT(*) AS count FROM %s %s", self::TABLE_STATISTIC_DAILY, $where);
        $resultSet = $this->query($sql, $bind);
        foreach($resultSet as $row) {
        	$row = (array) $row;
        	$result["count"] = $row["count"] / count(StatisticDailyType::getStatisticDailyTypes());
        }

        // Data
        $sql = sprintf("SELECT * FROM %s %s %s %s", self::TABLE_STATISTIC_DAILY, $where, $ordering, $limit);
		$resultSet = $this->query($sql, $bind);
		foreach ($resultSet as $row) {
			$row = (array) $row;

			$entity = new StatisticDaily();
			$entity->populate($row);

			$result["data"][$entity->getStatisticDailyDate()][] = $entity;
		}

		return $result;
	}

	public function saveNewAccountsStatistic() {
		return $this->saveStatistic(
			new StatisticDailyType(StatisticDailyType::NEW_ACCOUNTS),
			sprintf("
				SELECT IFNULL(COUNT(*), 0)
				FROM %s 
				WHERE DATE(created_date) = '" .$this->date. "'
			", self::TABLE_ACCOUNT)
		);
	}

	public function saveNewPayingAccountsStatistic() {
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::NEW_PAYING_ACCOUNTS),
            sprintf("
				SELECT
                    IFNULL(COUNT(DISTINCT t.account_id), 0)
                FROM
                    %s t
                JOIN
                    %s a ON t.account_id = a.account_id
                WHERE
                    DATE(a.created_date) = '" .$this->date. "'
                AND DATE(t.created_date) = '" .$this->date. "'
            	AND t.transaction_status_id = %d
			", self::TABLE_TRANSACTION, self::TABLE_ACCOUNT, TransactionStatus::SUCCESS)
        );
	}

    public function saveFreeTrialStatisticsNewSubscriptions()
    {
        return $this->saveStatistic(
            StatisticDailyType::create(StatisticDailyType::FREE_TRIAL_SUBSCRIPTIONS),
            sprintf("
                SELECT
                    IFNULL(COUNT(DISTINCT s.account_id), 0)
                FROM
                    %s s
                WHERE
                    s.free_trial = 1 AND
                    DATE(s.start_date) = '" .$this->date. "'
            ", self::TABLE_SUBSCRIPTION)
        );
    }

    public function saveFreeTrialStatistics1stCharge()
    {
        return $this->saveStatistic(
            StatisticDailyType::create(StatisticDailyType::FREE_TRIAL_1ST_CHARGE),
            sprintf("
                SELECT
                    IFNULL(COUNT(DISTINCT t.account_id), 0)
                FROM
                    %s t
                JOIN
                    %s s ON s.subscription_id = t.subscription_id
                JOIN
                    %s p ON p.package_id = s.package_id
                WHERE
                    p.free_trial = 1 AND
                    s.recurrent_count = 1 AND
                    DATE(t.created_date) = '" .$this->date. "'
            ", self::TABLE_TRANSACTION, self::TABLE_SUBSCRIPTION, self::TABLE_PACKAGE)
        );
    }

    public function saveTotalAccountsStatistic() {
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::TOTAL_ACCOUNTS),
            sprintf("
				SELECT IFNULL(COUNT(*), 0)
                FROM %s
                ", self::TABLE_ACCOUNT)
        );
	}

	public function saveTotalPayingAccountsStatistic() {
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::TOTAL_PAYING_ACCOUNTS),
            sprintf("
				SELECT IFNULL(COUNT(account_id), 0)
                FROM %s
                WHERE DATE(created_date) = '" .$this->date. "'
            	AND transaction_status_id = %d
			", self::TABLE_TRANSACTION, TransactionStatus::SUCCESS)
        );
	}

	public function saveTotalLoggedAccountsStatistic() {
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::TOTAL_LOGGED_ACCOUNTS),
            sprintf("
				SELECT IFNULL(count(DISTINCT(account_id)), 0)
                FROM %s
                WHERE action = '%s'
                AND DATE(action_date) = '" .$this->date. "'
			", self::TABLE_LOGIN_HISTORY, LoginHistory::ACTION_LOGIN)
        );
	}

    public function saveNumberOfNewAccountsWithFreeApplicationsSentStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::NEW_ACCOUNTS_WITH_FREE_APPLICATIONS),
            sprintf("
				SELECT IFNULL(COUNT(DISTINCT(acc.account_id)), 0)
                FROM
                    %s a
                        JOIN
                    %s s ON a.scholarship_id = s.scholarship_id
                        JOIN
                    %s acc ON acc.account_id = a.account_id
                WHERE
                    s.is_free = 1
                        AND DATE(a.date_applied) = '" .$this->date. "'
                        AND DATE(acc.created_date) = '" .$this->date. "'
			", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP, self::TABLE_ACCOUNT)
        );
    }

    public function saveNumberOfNewCustomersWithPaidApplicationsSentStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::NEW_ACCOUNTS_WITH_PAID_APPLICATIONS),
            sprintf("
				SELECT IFNULL(COUNT(DISTINCT(acc.account_id)), 0)
                FROM
                    %s a
                        JOIN
                    %s s ON a.scholarship_id = s.scholarship_id
                        JOIN
                    %s acc ON acc.account_id = a.account_id
                WHERE
                    s.is_free = 0
                        AND DATE(a.date_applied) = '" .$this->date. "'
                        AND DATE(acc.created_date) = '" .$this->date. "'
			", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP, self::TABLE_ACCOUNT)
        );
    }

    public function saveNumberOfFreeApplicationsSentStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::FREE_APPLICATIONS_SENT),
            sprintf("
				SELECT
                    IFNULL(COUNT(*), 0)
                FROM
                    %s a
                        JOIN
                    %s s ON a.scholarship_id = s.scholarship_id
                        JOIN
                    %s acc ON acc.account_id = a.account_id
                WHERE
                    s.is_free = 1
                        AND DATE(a.date_applied) = '" .$this->date. "'
                        AND DATE(acc.created_date) = '" .$this->date. "'
			", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP, self::TABLE_ACCOUNT)
        );
    }

    public function saveNumberOfPaidApplicationsSentStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::PAID_APPLICATIONS_SENT),
            sprintf("
				SELECT
                    IFNULL(COUNT(*), 0)
                FROM
                    %s a
                        JOIN
                    %s s ON a.scholarship_id = s.scholarship_id
                        JOIN
                    %s acc ON acc.account_id = a.account_id
                WHERE
                    s.is_free = 0
                        AND DATE(a.date_applied) = '" .$this->date. "'
                        AND DATE(acc.created_date) = '" .$this->date. "'
			", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP, self::TABLE_ACCOUNT)
        );
    }

    public function saveDepositAmountStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::DEPOSIT_AMOUNT),
            sprintf("
				SELECT IFNULL(SUM(amount), 0)
            FROM
                %s
            WHERE
                transaction_status_id = %s
                AND DATE(created_date) = '" .$this->date. "'
			", self::TABLE_TRANSACTION, TransactionStatus::SUCCESS)
        );
    }

    public function savePackagesSoldStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::PACKAGES_SOLD),
            sprintf("
			SELECT IFNULL(COUNT(*), 0)
            FROM %s
                WHERE transaction_status_id = %s
                AND DATE(created_date) = '" .$this->date. "'
			", self::TABLE_TRANSACTION, TransactionStatus::SUCCESS)
        );
    }

    public function saveScholarshipApplicationsSoldStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::SCHOLARSHIP_APPLICATIONS_SOLD),
            sprintf("
			SELECT IFNULL(SUM(scholarships_count), 0)
            FROM %s
                WHERE subscription_status_id = %d
                AND DATE(start_date) = '" .$this->date. "'
			", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE)
        );
    }

    public function saveTotalNumberOfFreeApplicationsSentStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::TOTAL_FREE_APPLICATIONS_SENT),
            sprintf("
                    SELECT
                        IFNULL(COUNT(*), 0)
                    FROM
                        %s a
                            JOIN
                        %s s ON a.scholarship_id = s.scholarship_id
                    WHERE
                        s.is_free = 1
                            AND DATE(a.date_applied) = '" .$this->date. "'
                ", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP)
        );
    }

    public function saveTotalNumberOfPaidApplicationsSentStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::TOTAL_PAID_APPLICATIONS_SENT),
            sprintf("
				SELECT
                    IFNULL(COUNT(*), 0)
                FROM
                    %s a
                        JOIN
                    %s s ON a.scholarship_id = s.scholarship_id
                WHERE
                    s.is_free = 0
                        AND DATE(a.date_applied) = '" .$this->date. "'
			", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP, self::TABLE_ACCOUNT)
        );
    }

    public function saveDepositCorrectionsStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::DEPOSIT_CORRECTIONS),
            sprintf("
				SELECT IFNULL(COUNT(*), 0)
            FROM
                %s
            WHERE
                transaction_status_id IN (%s, %s, %s)
                AND DATE(created_date) = '" .$this->date. "'
			", self::TABLE_TRANSACTION, TransactionStatus::VOID, TransactionStatus::REFUND, TransactionStatus::CHARGEBACK)
        );
    }

    public function saveDepositCorrectionAmountStatistic(){
        return $this->saveStatistic(
            new StatisticDailyType(StatisticDailyType::DEPOSIT_CORRECTION_AMOUNT),
            sprintf("
				SELECT IFNULL(SUM(amount), 0)
            FROM
                %s
            WHERE
                transaction_status_id IN (%s, %s, %s)
                AND DATE(created_date) = '" .$this->date. "'
			", self::TABLE_TRANSACTION, TransactionStatus::VOID, TransactionStatus::REFUND, TransactionStatus::CHARGEBACK)
        );
    }

	private function saveStatistic(StatisticDailyType $type, $selectSql, $params = array()) {
		$result = 0;

		$sql = sprintf("
			INSERT INTO %s VALUES
			(?, '" .$this->date. "', (%s))
			ON DUPLICATE KEY UPDATE value = VALUES(value)
		", self::TABLE_STATISTIC_DAILY, $selectSql);

		$bind[] = $type->getStatisticDailyTypeId();
		foreach ($params as $param) {
			$bind[] = $param;
		}

		$result = $this->execute($sql, $bind);
		return $result;
	}
}
