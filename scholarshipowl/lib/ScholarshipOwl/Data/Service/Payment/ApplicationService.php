<?php

/**
 * ApplicationService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	24. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\Application;
use ScholarshipOwl\Data\Entity\Payment\ApplicationStatus;
use ScholarshipOwl\Data\Service\AbstractService;


class ApplicationService extends AbstractService implements IApplicationService {
	public function getApplications($accountId) {
		$result = array();

		$sql = sprintf("
			SELECT 
				a.account_id, a.scholarship_id, a.application_status_id, a.comment, a.date_applied,
				s.title, s.url, s.application_type, s.amount
			FROM %s AS a, %s AS s
			WHERE a.scholarship_id = s.scholarship_id
			AND a.account_id = ?
			ORDER BY a.date_applied DESC	
		", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP);

		$resultSet = $this->query($sql, array($accountId));
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Application();
			$entity->populate($row);

			$result[] = $entity;
		}

		return $result;
	}

	public function getApplicationsCount($accountId) {
		$result = array();

		if(!is_array($accountId)) {
			$accountId = array($accountId);
		}

		if(!empty($accountId)) {
			$sql = sprintf("
				SELECT account_id, COUNT(scholarship_id) AS count
				FROM %s
				WHERE account_id IN (%s)
				GROUP BY account_id
			", self::TABLE_APPLICATION, implode(",", $accountId));

			$resultSet = $this->query($sql);
			foreach($resultSet as $row) {
				$row = (array) $row;
				$result[$row["account_id"]] = $row["count"];
			}
		}

		return $result;
	}

    public function getSubmittedApplicationsCount($accountId) {
        $result = array();

        if(!is_array($accountId)) {
            $accountId = array($accountId);
        }

        if(!empty($accountId)) {
            $sql = sprintf("
				SELECT account_id, COUNT(scholarship_id) AS count
				FROM %s
				WHERE account_id IN (%s)
				AND application_status_id = %s
				GROUP BY account_id
			", self::TABLE_APPLICATION, implode(",", $accountId), ApplicationStatus::SUCCESS);

            $resultSet = $this->query($sql);
            foreach($resultSet as $row) {
                $row = (array) $row;
                $result[$row["account_id"]] = $row["count"];
            }
        }

        return $result;
    }

    public function getSubmittedApplicationsWithRequirementsCount($accountId) {
        $result = array();

        if(!is_array($accountId)) {
            $accountId = array($accountId);
        }

        if(!empty($accountId)) {
            $sql = sprintf("
                SELECT DISTINCT
                    a.account_id, COUNT(1) AS count
                FROM
                    %s a
                        LEFT JOIN
                    %s at ON a.scholarship_id = at.scholarship_id
                        AND a.account_id = at.account_id
                        LEFT JOIN
                    %s af ON a.scholarship_id = af.scholarship_id
                        AND a.account_id = af.account_id
                        LEFT JOIN
                    %s ai ON a.scholarship_id = ai.scholarship_id
                        AND a.account_id = ai.account_id
                WHERE
                    a.account_id IN (%s)
                        AND application_status_id = %s
                        AND (af.account_id IS NOT NULL
                        OR at.account_id IS NOT NULL
                        OR ai.account_id IS NOT NULL)
                GROUP BY a.account_id
			", self::TABLE_APPLICATION, self::TABLE_APPLICATION_TEXT, self::TABLE_APPLICATION_FILE, self::TABLE_APPLICATION_IMAGE, implode(",", $accountId), ApplicationStatus::SUCCESS);

            $resultSet = $this->query($sql);
            foreach($resultSet as $row) {
                $row = (array) $row;
                $result[$row["account_id"]] = $row["count"];
            }
        }

        return $result;
    }

    public function getLastSubmittedApplicationWithEssay($accountId) {
        $result = array();

        if(!is_array($accountId)) {
            $accountId = array($accountId);
        }

        if(!empty($accountId)) {
            $sql = sprintf("
				SELECT a.account_id, DATE(a.date_applied) AS count
				FROM %s a
				RIGHT JOIN %s at ON a.scholarship_id = at.scholarship_id
				AND a.account_id = at.account_id
				WHERE a.account_id IN (%s)
				AND application_status_id = %s
				GROUP BY account_id
				ORDER BY a.date_applied desc
			", self::TABLE_APPLICATION, self::TABLE_APPLICATION_TEXT, implode(",", $accountId), ApplicationStatus::SUCCESS);

            $resultSet = $this->query($sql);
            foreach($resultSet as $row) {
                $row = (array) $row;
                $result[$row["account_id"]] = $row["count"];
            }
        }

        return $result;
    }

	public function getApplicationScholarshipsData($accountId) {
		$result = array();

		$sql = sprintf("SELECT scholarship_id, application_status_id FROM %s WHERE account_id = ?", self::TABLE_APPLICATION);
		$resultSet = $this->query($sql, array($accountId));

		foreach ($resultSet as $row) {
			$result[$row->scholarship_id] = $row->application_status_id;
		}

		return $result;
	}

	public function savePendingApplications($accountId, $scholarshipIds, $subscriptionId = null) {
		return $this->saveStatusApplications(ApplicationStatus::PENDING, $accountId, $scholarshipIds, $subscriptionId);
	}

	public function saveNeedMoreInfoApplications($accountId, $scholarshipIds, $subscriptionId = null) {
		return $this->saveStatusApplications(ApplicationStatus::NEED_MORE_INFO, $accountId, $scholarshipIds, $subscriptionId);
	}

	private function saveStatusApplications($statusId, $accountId, $scholarshipIds, $subscriptionId = null) {
		$result = 0;

		try {
			$this->beginTransaction();

			$now = date("Y-m-d H:i:s");
			$bulk = array();
			$params = array();


			$sql = sprintf("
					INSERT INTO %s(account_id, scholarship_id, application_status_id, subscription_id, date_applied) 
					VALUES
				", self::TABLE_APPLICATION
			);

			foreach($scholarshipIds as $scholarshipId) {
				$bulk[] = sprintf("(?, ?, %d, ?, '%s')", $statusId, $now);

				$params[] = $accountId;
				$params[] = $scholarshipId;
				$params[] = $subscriptionId;
			}

			$sql .= implode(",", $bulk);
			$sql .= " ON DUPLICATE KEY UPDATE account_id = account_id";

			$result = $this->execute($sql, $params);


			if(isset($subscriptionId)) {
				$count = count($scholarshipIds);

				$sql = sprintf("UPDATE %s SET credit = credit - %d WHERE subscription_id = ? AND credit > 0", self::TABLE_SUBSCRIPTION, $count);
				$this->execute($sql, array($subscriptionId));
			}

			$this->commit();
		}
		catch(\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

	public function getPendingApplications() {
		$result = array();

		$sql = sprintf("
			SELECT account_id, scholarship_id FROM %s WHERE application_status_id = %d
			", self::TABLE_APPLICATION, ApplicationStatus::PENDING
		);

		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Application();
			$entity->populate($row);

			$result[] = $entity;
		}

		return $result;
	}

	public function getNeedMoreInfoApplications($accountId) {
		$result = array();

		$sql = sprintf("
			SELECT * FROM %s WHERE account_id = ? AND application_status_id  = %d",
		 	self::TABLE_APPLICATION, ApplicationStatus::NEED_MORE_INFO
		);

		$resultSet = $this->query($sql, array($accountId));
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Application();
			$entity->populate($row);

			$result[] = $entity;
		}

		return $result;
	}

	public function changeApplicationStatus($accountId, $scholarshipId, $applicationStatusId) {
		$result = 0;

		$sql = sprintf("
			UPDATE %s 
			SET application_status_id = %d
			WHERE account_id = ? 
			AND scholarship_id = ?
		", self::TABLE_APPLICATION, $applicationStatusId
		);

		$result = $this->execute($sql, array($accountId, $scholarshipId));
		return $result;
	}

	public function getApplicationsAmount($accountIds) {
		$result = array();

		if (!is_array($accountIds)) {
			$accountIds = array($accountIds);
		}

		if (!empty($accountIds)) {
			$marks = implode(array_fill(0, count($accountIds), "?"), ",");

			$sql = sprintf("
				SELECT a.account_id, SUM(s.amount) AS amount
				FROM %s a
				JOIN %s s ON s.scholarship_id = a.scholarship_id
				WHERE a.account_id IN(%s)
				GROUP BY a.account_id
				", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP, $marks
			);

			$resultSet = $this->query($sql, $accountIds);
			foreach($resultSet as $row) {
				$result[$row->account_id] = $row->amount;
			}
		}

		return $result;
	}
}

