<?php

/**
 * StatisticService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	19. May 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\Subscription;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Service\AbstractService;


class StatisticService extends AbstractService implements IStatisticService {
	public function getTopPrioritySubscriptions($accountIds) {
		$result = array();
		
		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}
		
		
		if (!empty($accountIds)) {
			$sql = sprintf("
				SELECT s.*
				FROM %s s
				JOIN  (
					SELECT account_id, MIN(priority) AS priority
					FROM %s 
					WHERE account_id IN (%s)
					AND subscription_status_id = %d
					GROUP BY account_id
				) AS s2 ON s.account_id = s2.account_id AND s.priority = s2.priority
				WHERE s.account_id IN (%s)
				AND s.subscription_status_id = %d
			", 
				self::TABLE_SUBSCRIPTION, self::TABLE_SUBSCRIPTION, 
				implode(",", $accountIds), SubscriptionStatus::ACTIVE,
				implode(",", $accountIds), SubscriptionStatus::ACTIVE
			);
			
			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$row = (array) $row;
				
				$entity = new Subscription();
				$entity->populate($row);
				
				$result[$row["account_id"]] = $entity;
			}
		}
		
		return $result;
	}
	
	public function hasUnlimitedSubscriptions($accountIds) {
		$result = array();
		
		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}
		
		if (!empty($accountIds)) {
			$sql = sprintf("
				SELECT account_id, COUNT(*) AS count 
				FROM %s
				WHERE account_id IN (%s)
				AND subscription_status_id = %d
				AND is_scholarships_unlimited = %d
				GROUP BY account_id
			", self::TABLE_SUBSCRIPTION, implode(",", $accountIds), SubscriptionStatus::ACTIVE, 1);
			
			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$result[$row->account_id] = $row->count;
			}
		}
		
		return $result;
	}
	
	public function hasPaidSubscriptions($accountIds) {
		$result = array();
		
		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}
		
		$sql = sprintf("
			SELECT s.account_id, s.subscription_id
			FROM %s AS s
			JOIN `transaction` AS t ON t.subscription_id = s.subscription_id
			WHERE s.subscription_status_id = %d	AND s.account_id IN(" . implode(",", $accountIds) . ")
		", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE);
		
		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$result[$row->account_id] = $row->subscription_id;
		}
		
		return $result;
	}
}
