<?php

/**
 * LoginHistoryService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Branislav Jovanovic <branej@gmail.com>
 *
 * @created    	26. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;

use ScholarshipOwl\Data\Entity\Account\LoginHistory;
use ScholarshipOwl\Data\Service\AbstractService;

class LoginHistoryService extends AbstractService implements ILoginHistoryService {
	public function saveLogin($accountId) {
		$data = array(
			'account_id' => $accountId,
			'action' => LoginHistory::ACTION_LOGIN,
			'action_date' => date("Y-m-d H:i:s"),
			'ip_address' => \Request::getClientIp(),
		);

		$this->insert(self::TABLE_LOGIN_HISTORY, $data);
		$loginHistoryId = $this->getLastInsertId();
		$result = $loginHistoryId;
		return $result;
	}

	public function saveLogout($accountId) {
		$data = array(
			'account_id' => $accountId,
			'action' => LoginHistory::ACTION_LOGOUT,
			'action_date' => date("Y-m-d H:i:s"),
			'ip_address' => \Request::getClientIp(),
		);

		$this->insert(self::TABLE_LOGIN_HISTORY, $data);
		$loginHistoryId = $this->getLastInsertId();
		$result = $loginHistoryId;
		return $result;
	}

    /**
     * @param $accountId
     * @param string $limit
     * @return LoginHistory[]
     */
	public function getAccountLoginHistory($accountId, $limit = "") {
		$result = array();

		$order = "ORDER BY action_date DESC";

		// Limit
		if(!empty($limit)) {
			$limit = "LIMIT $limit";
		}

		$sql = sprintf('SELECT * from %s WHERE account_id = ? %s %s', self::TABLE_LOGIN_HISTORY, $order, $limit);
		
		$resultSet = $this->query($sql, array($accountId));
		foreach($resultSet as $row) {
			$row = (array) $row;
			$entity = new LoginHistory();
			$entity->populate($row);
			$result[] = $entity;
		}
		return $result;
	}
	
	public function getLastLoginDate($accountIds) {
		$result = array();
		
		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}
		
		if (!empty($accountIds)) {
			$marks = implode(array_fill(0, count($accountIds), "?"), ",");
			
			$sql = sprintf("
				SELECT account_id, MAX(action_date) AS action_date 
				FROM %s 
				WHERE action = '%s'
				AND account_id IN(%s)
				GROUP BY account_id 
				", self::TABLE_LOGIN_HISTORY, LoginHistory::ACTION_LOGIN, $marks
			);
			
			$resultSet = $this->query($sql, $accountIds);
			foreach ($resultSet as $row) {
				$result[$row->account_id] = $row->action_date;
			}
		}
		
		return $result;
	}
}
