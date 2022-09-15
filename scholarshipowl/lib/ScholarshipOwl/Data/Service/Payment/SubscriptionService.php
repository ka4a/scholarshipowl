<?php namespace ScholarshipOwl\Data\Service\Payment;

use Carbon\Carbon;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Payment\Subscription;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Data\Service\IDDL;

class SubscriptionService extends AbstractService implements ISubscriptionService
{
    /**
     * @param $subscriptionId
     * @return null|Subscription
     *
     * @throws \Exception
     */
    public function getSubscriptionById($subscriptionId) {
        $subscription = $this->getEntityByColumn(
            Subscription::class, self::TABLE_SUBSCRIPTION, 'subscription_id', $subscriptionId
        );

        if (!$subscription) {
            throw new \Exception(sprintf('Subscription (ID: %s) not found.', $subscriptionId));
        }

        return $subscription;
    }

    public function getTopPrioritySubscription($accountId) {
        $result = new Subscription();
        $sql = sprintf("SELECT * FROM %s subscription WHERE subscription.account_id = ? AND (subscription.subscription_status_id = %d OR active_until > ?) ORDER BY subscription.priority ASC LIMIT 1", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE);

        $resultSet = $this->query($sql, [$accountId, Carbon::instance(new \DateTime())->addMinutes(1)]);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $result->populate($row);
        }

        return $result;
    }

    public function getTopPrioritySubscriptionWithCredit($accountId) {
        $result = new Subscription();
        $sql = sprintf("SELECT * FROM %s subscription WHERE subscription.account_id = ? AND (subscription.subscription_status_id = %d OR active_until > ?) AND subscription.scholarships_count > subscription.credit ORDER BY subscription.priority ASC LIMIT 1", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE);

        $resultSet = $this->query($sql, [$accountId, Carbon::instance(new \DateTime())->addMinutes(1)]);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $result->populate($row);
        }

        return $result;
    }

    public function getLowestPrioritySubscriptionWithCredit($accountId) {
        $result = new Subscription();
        $sql = sprintf("SELECT * FROM %s subscription WHERE subscription.account_id = ? AND (subscription.subscription_status_id = %d OR active_until > ?) AND subscription.credit > %d ORDER BY subscription.priority DESC LIMIT 1", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE, 0);

        $resultSet = $this->query($sql, [$accountId, Carbon::instance(new \DateTime())->addMinutes(1)]);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $result->populate($row);
        }

        return $result;
    }

    public function getUnlimitedUserSubscription($accountId) {
        $result = null;

        if (!empty($accountId)) {
            $sql = sprintf("
				SELECT * FROM %s
				WHERE account_id = ?
				AND (subscription.subscription_status_id = %d OR active_until > ?)
				AND is_scholarships_unlimited = %d
				LIMIT 1",
                self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE, 1
            );

            $resultSet = $this->query($sql, [$accountId, Carbon::instance(new \DateTime())->addMinutes(1)]);

            foreach($resultSet as $row) {
                $row = (array) $row;
                $result = new Subscription();

                $result->populate($row);
            }
        }

        return $result;
    }

    public function getTotalCredit($accountId) {
        $result = 0;

        if (!empty($accountId)) {
            $sql = sprintf("
				SELECT credit FROM %s
				WHERE account_id = ?
				AND (subscription_status_id = %d OR active_until > ?) ",
                self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
            );

            $resultSet = $this->query($sql, [$accountId, Carbon::instance(new \DateTime())->addMinutes(1)]);
            foreach($resultSet as $row) {
                $result += $row->credit;
            }
        }

        return $result;
    }

    public function getTotalScholarships($accountId) {
        $result = 0;

        if (!empty($accountId)) {
            $sql = sprintf("
				SELECT scholarships_count FROM %s
				WHERE account_id = ?
				AND (subscription_status_id = %d OR active_until > ?) ",
                self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
            );

            $resultSet = $this->query($sql, [$accountId, Carbon::instance(new \DateTime())->addMinutes(1)]);
            foreach($resultSet as $row) {
                $result += $row->scholarships_count;
            }
        }

        return $result;
    }

    public function getTotalSubscriptionsCount($accountId) {
        $result = 0;

        if (!empty($accountId)) {
            $sql = sprintf("
				SELECT count(*) AS cnt FROM %s
				WHERE account_id = ?
				AND (subscription_status_id = %d OR active_until > ?) ",
                self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
            );

            $resultSet = $this->query($sql, [$accountId, Carbon::instance(new \DateTime())->addMinutes(1)]);
            foreach($resultSet as $row) {
                $result = $row->cnt;
            }
        }

        return $result;
    }

	public function getBoughtPackages() {
		$result = array();

		return $result;
	}

	public function getPotentialExpiredSubscriptions() {
		$result = array();

		$sql = sprintf("
			SELECT subscription_id FROM %s
			WHERE subscription_status_id = %d
			AND
			(
				(credit = 0 AND is_scholarships_unlimited = 0)
				OR
				(end_date <> '0000-00-00 00:00:00' AND end_date < NOW())
			)
		", self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE);

		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$result[] = $row->subscription_id;
		}

		return $result;
	}

	public function expireSubscription($subscriptionId) {
		$result = false;

		$sql = sprintf("UPDATE %s SET subscription_status_id = %d WHERE subscription_id = ?", self::TABLE_SUBSCRIPTION, SubscriptionStatus::EXPIRED);
		$result = $this->execute($sql, array($subscriptionId));

		return $result;
	}

	public function cancelSubscription($subscriptionId) {
		$result = false;

		$sql = sprintf("UPDATE %s SET subscription_status_id = %d WHERE subscription_id = ?", self::TABLE_SUBSCRIPTION, SubscriptionStatus::CANCELED);
		$result = $this->execute($sql, array($subscriptionId));

		return $result;
	}

	public function expireSubscriptions($subscriptionIds) {
		$result = false;

		try {
			$this->beginTransaction();

			$marks = implode(array_fill(0, count($subscriptionIds), "?"), ",");
			$sql = sprintf("
				UPDATE %s SET subscription_status_id = %d
				WHERE subscription_id IN(%s)
				", self::TABLE_SUBSCRIPTION, SubscriptionStatus::EXPIRED, $marks
			);

			$result = $this->execute($sql, $subscriptionIds);
			$this->commit();
		}
		catch(\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

	private function getSqlDateParts($startDate = '', $endDate = '') {
		if ($startDate == '') {
			if ($endDate == '') {
				$datePart = '';
				$params = array();
			}
			else {
				$datePart = ' AND start_date <= ?';
				$params   = array($endDate);
			}
		}
		else {
			if ($endDate == '') {
				$datePart = ' AND start_date >= ?';
				$params   = array($startDate);
			}
			else {
				$datePart = ' AND start_date BETWEEN ? AND ?';
				$params = array($startDate, $endDate);
			}
		}
		return array($datePart,$params);
	}

	public function getScholarshipApplicationsDated($startDate = '', $endDate = '') {
		$result = array();

		list($datePart, $params) = $this->getSqlDateParts($startDate, $endDate);

		$sql = sprintf("
			SELECT SUM(scholarships_count) as scholarships FROM %s WHERE subscription_status_id = %d " . $datePart,
			self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
		);

		$resultSet = $this->query($sql, $params);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result = $row['scholarships'];
		}

		return $result;
	}

	public function getUniqueCustomersDated($startDate = '', $endDate = '') {
		$result = array();

		list($datePart, $params) = $this->getSqlDateParts($startDate, $endDate);

		$sql = sprintf("
			SELECT COUNT(DISTINCT(account_id)) as customers FROM %s WHERE subscription_status_id = %d " . $datePart,
			self::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
		);

		$resultSet = $this->query($sql, $params);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result = $row['customers'];
		}
		return $result;
	}

	public function getTotalAmountDated($startDate = '', $endDate = '') {
		$result = array();

		list($datePart, $params) = $this->getSqlDateParts($startDate, $endDate);

		$sql = sprintf("
			SELECT SUM(DISTINCT(price)) as amount FROM %s WHERE subscription_status_id = %d " . $datePart,
			IDDL::TABLE_SUBSCRIPTION, SubscriptionStatus::ACTIVE
		);

		$resultSet = $this->query($sql, $params);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result = $row['amount'];
		}
		return $result;
	}

	public function getTotalsByPackageDated($startDate = '', $endDate = '') {
		$result = array();

		list($datePart, $params) = $this->getSqlDateParts($startDate, $endDate);
		$sql = sprintf("
		SELECT package_id,
			SUM(price) AS amount,
			SUM(scholarships_count) as scholarships FROM %s
			WHERE subscription_status_id = %d " . $datePart .
			" GROUP BY package_id ORDER BY package_id ",
			self::TABLE_SUBSCRIPTION,
			SubscriptionStatus::ACTIVE
		);

		$resultSet = $this->query($sql, $params);
		foreach($resultSet as $row) {
			$row = (array) $row;
			$result[$row['package_id']] = array('scholarships' => $row['scholarships'], 'amount' => $row['amount']);
		}

		return $result;
	}

    public function renewSubscriptions() {
        // Get active recurrent subscriptions
        $sql = sprintf(
            "SELECT subscription_id, expiration_period_type, expiration_period_value
			FROM %s
			WHERE expiration_type = '%s'
			AND subscription_status_id = %d ",
			self::TABLE_SUBSCRIPTION, Subscription::EXPIRATION_TYPE_RECURRENT, SubscriptionStatus::ACTIVE
        );

        $resultSet = $this->query($sql);
        foreach($resultSet as $row) {
            //  Reset credit for found subscriptions
            $sql = sprintf("UPDATE subscription SET credit = scholarships_count, renewal_date = renewal_date + interval 1 %s WHERE subscription_id = %d  AND renewal_date < now() - interval 1 %s", $row->expiration_period_type, $row->subscription_id, $row->expiration_period_type);
        }
    }

}
