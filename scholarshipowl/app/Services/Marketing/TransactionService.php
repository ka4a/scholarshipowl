<?php

/**
 * TransactionService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created        09. December 2014.
 * @copyright    Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\Subscription;
use ScholarshipOwl\Data\Entity\Payment\Transaction;
use ScholarshipOwl\Data\Service\AbstractService;

use ScholarshipOwl\Domain\Subscription as DomainSubscription;

class TransactionService extends AbstractService implements ITransactionService
{
    public function getTransaction($transactionId)
    {
        $result = new Transaction();

        $sql = sprintf("
			SELECT t.*, p.first_name, p.last_name, p.phone
			FROM %s t, %s p
			WHERE t.account_id = p.account_id AND t.transaction_id = ?
		", self::TABLE_TRANSACTION, self::TABLE_PROFILE);

        $resultSet = $this->query($sql, array($transactionId));
        foreach ($resultSet as $row) {
            $row = (array)$row;

            $result->populate($row);
            $result->getAccount()->getProfile()->populate($row);
        }

        return $result;
    }


    public function getTransactionByBankTransactionId($transactionId)
    {
        $result = NULL;

        $sql = sprintf("
			SELECT t.*, p.first_name, p.last_name, p.phone
			FROM %s t, %s p
			WHERE t.account_id = p.account_id
			AND t.bank_transaction_id = ?
		", self::TABLE_TRANSACTION, self::TABLE_PROFILE);

        $resultSet = $this->query($sql, array($transactionId));
        foreach ($resultSet as $row) {
            $row = (array)$row;
            $result = new Transaction($row);
            $result->getAccount()->getProfile()->populate($row);
        }

        return $result;
    }


    public function getAccountTransactions($accountId)
    {
        $result = array();
        $sql = sprintf("SELECT * FROM %s WHERE account_id = ?", self::TABLE_TRANSACTION);

        $resultSet = $this->query($sql, array($accountId));
        foreach ($resultSet as $row) {
            $row = (array)$row;

            $entity = new Transaction();
            $entity->populate($row);

            $result[$entity->getTransactionId()] = $entity;
        }

        return $result;
    }

    /**
     * @param Transaction $transaction
     * @return null|Transaction
     */
    public function addTransaction(Transaction $transaction)
    {
        $result = null;

        $data = $transaction->toArray();
        unset($data["transaction_id"]);

        if ($this->insert(self::TABLE_TRANSACTION, $data)) {
            $transaction->setTransactionId($this->getLastInsertId());
            $result = $transaction;
        }

        return $result;
    }

    public function searchTransactions($params = array(), $limit = "")
    {
        $result = array(
            "count" => 0,
            "amount" => 0,
            "data" => array(),
        );

        $tables = sprintf("%s AS t, %s AS s, %s AS a, %s AS p, %s AS pa", self::TABLE_TRANSACTION, self::TABLE_SUBSCRIPTION, self::TABLE_ACCOUNT, self::TABLE_PROFILE, self::TABLE_PACKAGE);
        $tablesCount = sprintf(", %s AS msad", self::TABLE_MARKETING_SYSTEM_ACCOUNT_DATA);
        $columns = "t.*, s.*, a.domain_id, p.first_name, p.last_name, p.phone, p.agree_call,
		            (SELECT 
		            GROUP_CONCAT(value)
		        FROM
		            `marketing_system_account_data` AS msad
		        WHERE
		            (name = 'affiliate_id' OR name = 'transaction_id')
		                AND msad.account_id = t.account_id
		                ) AS has_offers_params";
        $where = "";
        $having = "";
        $group = 'GROUP BY t.transaction_id';
        $conditions = [
            't.account_id = p.account_id',
            't.subscription_id = s.subscription_id',
            'a.deleted_at IS NULL',
            'a.account_id = t.account_id',
            'pa.package_id = s.package_id'
        ];
	    $conditionsCount = array();
        $order = "ORDER BY t.transaction_id DESC";
        $bind = $bindCount = array();

        // Conditions
        if (!empty($params['domain'])) {
            $conditions[] = 'a.domain_id = ?';
            $bind[] = $bindCount[] = $params['domain'];
        }

        if (!empty($params['first_name'])) {
            $conditions[] = 'p.first_name LIKE ?';
            $bind[] = $bindCount[] = '%'.$params['first_name'].'%';
        }

        if (!empty($params['last_name'])) {
            $conditions[] = 'p.last_name LIKE ?';
            $bind[] = $bindCount[] = '%'.$params['last_name'].'%';
        }

        if (!empty($params['phone'])) {
            $conditions[] = 'p.phone LIKE ?';
            $bind[] = $bindCount[] = '%'.preg_replace( '/[^0-9]/', '', $params['phone']).'%';
        }

        if (!empty($params['subscription_start_date_from'])) {
            $conditions[] = 's.start_date > ?';
            $bind[] = $bindCount[] = $params['subscription_start_date_from'];
        }

        if (!empty($params['subscription_start_date_to'])) {
            $conditions[] = 's.start_date <= ?';
            $bind[] = $bindCount[] = $params['subscription_start_date_to'];
        }

        if (!empty($params["transaction_status_id"])) {
            $conditions[] = "t.transaction_status_id IN(" . implode(array_fill(0, count($params["transaction_status_id"]), "?"), ",") . ")";
            foreach ($params["transaction_status_id"] as $param) {
                $bind[] = $bindCount[] = $param;
            }
        }
        if (!empty($params["subscription_expiration_type"]) && is_array($params["subscription_expiration_type"])) {
            $conditions[] = "s.expiration_type IN (" . implode(array_fill(0, count($params["subscription_expiration_type"]), '?'), ',') .  ")";
            foreach ($params["subscription_expiration_type"] as $param) {
                $bind[] = $bindCount[] = $param;
            }
        }
        if (!empty($params["payment_method_id"])) {
            $conditions[] = "t.payment_method_id IN(" . implode(array_fill(0, count($params["payment_method_id"]), "?"), ",") . ")";
            foreach ($params["payment_method_id"] as $param) {
                $bind[] = $bindCount[] = $param;
            }
        }
        if (is_array($params['payment_type_id']) && !empty($params['payment_type_id'])) {
            $conditions[] = 't.payment_type_id IN(' .implode(',', array_values($params['payment_type_id'])). ')';
        }
        if (!empty($params["credit_card_type"])) {
            $conditions[] = "t.credit_card_type IN(" . implode(array_fill(0, count($params["credit_card_type"]), "?"), ",") . ")";
            foreach ($params["credit_card_type"] as $param) {
                $bind[] = $bindCount[] = $param;
            }
        }
        if (!empty($params["device"])) {
            $conditions[] = "t.device IN(" . implode(array_fill(0, count($params["device"]), "?"), ",") . ")";
            foreach ($params["device"] as $param) {
                $bind[] = $bindCount[] = $param;
            }
        }
        if (!empty($params["created_date_from"])) {
            $conditions[] = "t.created_date > ?";
            $bind[] = $bindCount[] = $params["created_date_from"];
        }
        if (!empty($params["created_date_to"])) {
            $conditions[] = "t.created_date <= ?";
            $bind[] = $bindCount[] = $params["created_date_to"];
        }
        if (!empty($params["amount_min"])) {
            $conditions[] = "t.amount > ?";
            $bind[] = $bindCount[] = $params["amount_min"];
        }
        if (!empty($params["amount_max"])) {
            $conditions[] = "t.amount <= ?";
            $bind[] = $bindCount[] = $params["amount_max"];
        }
        if (!empty($params["provider_transaction_id"])) {
            $conditions[] = "t.provider_transaction_id LIKE ?";
            $bind[] = $bindCount[] = "%" . $params["provider_transaction_id"] . "%";
        }
        if (!empty($params["bank_transaction_id"])) {
            $conditions[] = "t.bank_transaction_id LIKE ?";
            $bind[] = $bindCount[] = "%" . $params["bank_transaction_id"] . "%";
        }
	    if (!empty($params["expiration_type"])) {
		    $conditions[] = "s.expiration_type IN(" . implode(array_fill(0, count($params["expiration_type"]), "?"), ",") . ")";
		    foreach ($params["expiration_type"] as $param) {
			    $bind[] = $bindCount[] = $param;
		    }
	    }
	    if (!empty($params["package"])) {
		    $conditions[] = "s.package_id IN(" . implode(array_fill(0, count($params["package"]), "?"), ",") . ")";
		    foreach ($params["package"] as $param) {
			    $bind[] = $bindCount[] = $param;
		    }
	    }
        if (!empty($params['package_free_trial'])) {
            $conditions[] = sprintf('pa.free_trial = ?');
            $bind[] = $bindCount[] = $params['package_free_trial'];
        }

        if (!empty($params['recurrent_number']) || !empty($params['recurrent_number_gt']) || !empty($params['recurrent_number_lt'])) {
            if ($params['recurrent_number'] !== 'NULL') {
                $recurrentNumberCondition = [];

                if (!empty($params['recurrent_number'])) {
                    $recurrentNumberCondition[] = 't.recurrent_number = ?';
                    $bind[] = $bindCount[] = $params['recurrent_number'];
                }

                if (!empty($params['recurrent_number_gt'])) {
                    $recurrentNumberCondition[] = 't.recurrent_number > ?';
                    $bind[] = $bindCount[] = $params['recurrent_number_gt'];
                }

                if (!empty($params['recurrent_number_lt'])) {
                    $recurrentNumberCondition[] = 't.recurrent_number < ?';
                    $bind[] = $bindCount[] = $params['recurrent_number_lt'];
                }

                $condition = sprintf('t.recurrent_number IS NOT NULL AND (%s)', implode(' OR ', $recurrentNumberCondition));
            } else {
                $condition = 't.recurrent_number IS NULL';
            }

            $conditions[] = $condition;
        }

        if (!empty($params["account_id"])) {
		    $conditions[] = "t.account_id = ?";
		    $bind[] = $bindCount[] =  $params["account_id"];
	    }
	    if (!empty($params["affiliate_id"])) {
		    $having = "HAVING has_offers_params LIKE ?";

		    $tablesCount = $tables . $tablesCount;

		    $conditionsCount[] = "msad.account_id = t.account_id";
		    $conditionsCount[] = "msad.value = ?";

		    $bind[] = $params["affiliate_id"].",%";
		    $bindCount[] = $params["affiliate_id"];
	    }

        if (!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }

	    $whereCount = $where;

	    if (!empty($conditionsCount)) {
		    if(empty($conditions)){
			    $whereCount = "WHERE ";
		    }else{
			    $whereCount .= " AND ";
		    }
		    $whereCount .= implode(" AND ", $conditionsCount);
	    }else{
		    $tablesCount = $tables;
	    }


        // Limit
        if (!empty($limit)) {
            $limit = "LIMIT $limit";
        }

        // Count
        $sql = sprintf("SELECT COUNT(DISTINCT(t.transaction_id)) AS count, COALESCE(SUM(t.amount), 0) AS amount
		        FROM %s %s", $tablesCount, $whereCount);

        $resultSet = $this->query($sql, $bindCount);

        foreach ($resultSet as $row) {
            $row = (array)$row;
            $result["count"] = $row["count"];
            $result["amount"] = $row["amount"];
        }

        // Data
        $sql = sprintf("SELECT %s FROM %s %s %s %s %s %s", $columns, $tables, $where, $group, $having, $order, $limit);
        $resultSet = $this->query($sql, $bind);

        foreach ($resultSet as $row) {
            $row = (array)$row;

            $transaction = new Transaction();
            $transaction->populate($row);
            $transaction->setSubscription(new DomainSubscription($row));

	        $hasOffersParameters = explode(",", $row["has_offers_params"]);

	        $transaction->has_offers_affiliate_id = "";
	        $transaction->has_offers_transaction_id = "";

	        if(count($hasOffersParameters) > 1){
		        $transaction->has_offers_affiliate_id = $hasOffersParameters[0];
		        $transaction->has_offers_transaction_id = $hasOffersParameters[1];
	        }


            $result["data"][$transaction->getTransactionId()] = $transaction;
        }

        return $result;
    }

    private function getSqlDateParts($startDate = '', $endDate = '')
    {
        if ($startDate == '') {
            if ($endDate == '') {
                $datePart = ' TRUE';
                $params = array();
            } else {
                $datePart = ' created_date <= ?';
                $params = array($endDate);
            }
        } else {
            if ($endDate == '') {
                $datePart = ' created_date >= ?';
                $params = array($startDate);
            } else {
                $datePart = ' created_date BETWEEN ? AND ?';
                $params = array($startDate, $endDate);
            }
        }
        return array($datePart, $params);
    }

    public function getTransactionsDated($startDate = '', $endDate = '')
    {
        $result = array();

        list($datePart, $params) = $this->getSqlDateParts($startDate, $endDate);

        $sql = sprintf("SELECT * FROM %s WHERE " . $datePart, self::TABLE_TRANSACTION);

        $resultSet = $this->query($sql, $params);
        foreach ($resultSet as $row) {
            $row = (array)$row;
            $result[] = $row;
        }

        return $result;
    }

    public function changeTransactionStatus($transactionId, $transactionStatusId)
    {
        $result = 0;

        $sql = sprintf("UPDATE %s SET transaction_status_id = ? WHERE transaction_id = ?", self::TABLE_TRANSACTION);
        $result = $this->execute($sql, array($transactionStatusId, $transactionId));

        return $result;
    }

    public function countSubscriptionTransactions(Subscription $subscription)
    {
        $transactions = \DB::table(self::TABLE_TRANSACTION)
            ->where('subscription_id', $subscription->getSubscriptionId())
            ->get();

        return count($transactions);
    }
}
