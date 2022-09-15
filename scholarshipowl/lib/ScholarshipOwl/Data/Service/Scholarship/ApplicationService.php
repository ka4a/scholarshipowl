<?php

/**
 * ApplicationService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	24. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;

use App\Entity\Account;
use App\Entity\EssayFiles;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Facades\EntityManager;
use App\Services\PubSub\TransactionalEmailService;
use ScholarshipOwl\Data\Entity\Files\File;
use ScholarshipOwl\Data\Entity\Payment\Subscription;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionAcquiredType;
use ScholarshipOwl\Data\Entity\Scholarship\Application;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Data\Entity\Payment\ApplicationStatus;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;
use ScholarshipOwl\Data\Service\Scholarship\StatisticService as ScholarshipStatisticService;
use ScholarshipOwl\Util\Mailer;


class ApplicationService extends AbstractService implements IApplicationService {
    /**
     * @param $accountId
     * @param $scholarshipId
     * @return object|null
     */
	public function getApplication($accountId, $scholarshipId) {
		return EntityManager::getRepository(\App\Entity\Application::class)->findOneBy(['account' => $accountId, 'scholarship' => $scholarshipId]);
	}
	
	/**
	 * Gets Applications
	 *
	 * @param $accountId int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplications($accountId) {
		$result = array();

		$sql = sprintf("SELECT scholarship_id, application_status_id FROM %s WHERE account_id = ?", self::TABLE_APPLICATION);
		$resultSet = $this->query($sql, array($accountId));

		foreach ($resultSet as $row) {
			$result[$row->scholarship_id] = $row->application_status_id;
		}

		return $result;
	}


	/**
	 * Gets Applications By Status/es
	 *
	 * @param $accountId int
     * @param $statuses array
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplicationsByStatuses($accountId, $statuses = array()) {
		$result = array();

		$sql = sprintf("SELECT scholarship_id, application_status_id FROM %s WHERE account_id = ? AND FIND_IN_SET(application_status_id, ?)", self::TABLE_APPLICATION);
		$resultSet = $this->query($sql, array($accountId, implode(",", $statuses)));

		foreach ($resultSet as $row) {
			$result[$row->scholarship_id] = $row->application_status_id;
		}

		return $result;
	}


	/**
	 * Gets Applications Essays
	 *
	 * @param $accountId int
	 * @param $essayIds array
	 *
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplicationsEssays($accountId, $essayIds = array()) {
		$result = array();

		$bind = array($accountId);
		$condition = "";

		if (!empty($essayIds)) {
			if (!is_array($essayIds)) {
				$essayIds = array($essayIds);
			}

			$marks = implode(array_fill(0, count($essayIds), "?"), ",");
			$condition = "AND essay_id IN({$marks})";

			foreach ($essayIds as $essayId) {
				$bind[] = $essayId;
			}
		}

		$sql = sprintf("SELECT essay_id, text FROM %s WHERE account_id = ? %s", self::TABLE_APPLICATION_ESSAY, $condition);
		$resultSet = $this->query($sql, $bind);

		foreach ($resultSet as $row) {
			$result[$row->essay_id] = $row->text;
		}

		return $result;
	}


	/**
	 * Gets Applications Essays Ids
	 *
	 * @param $accountId int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplicationsEssaysIds($accountId) {
		$result = array();

		$sql = sprintf("SELECT essay_id FROM %s WHERE account_id = ?", self::TABLE_APPLICATION_ESSAY);
		$resultSet = $this->query($sql, array($accountId));

		foreach ($resultSet as $row) {
			$result[] = $row->essay_id;
		}

		return $result;
	}

	/**
	 * Gets Applications Essays Ids From uploaded files
	 *
	 * @param $accountId int
	 * @param $scholarshipId int
	 * @access public
	 * @return array
	 *
	 * @author Ivan Krkotic <ivan.krkotic@gmail.com>
	 */
	public function getApplicationEssayIdsFromFiles($accountId, $scholarshipId) {
		$result = array();

		$sql = sprintf("SELECT
			essay_id
		    FROM %s
		    join account_file on account_file.id = essay_files.account_file_id
		    where account_file.account_id = ?
		    and essay_files.scholarship_id = ?;", self::TABLE_ESSAY_FILES);

		$resultSet = $this->query($sql, array($accountId, $scholarshipId));

		foreach ($resultSet as $row) {
			$result[] = $row->essay_id;
		}

		return $result;
	}

	/**
	 * Gets File Ids Connected To Essay
	 *
	 * @param $accountId int
	 * @param $essayId int
	 * @return array
	 */
	public function getApplicationEssayFileIds($accountId, $essayId) {
		$result = array();

		$sql = sprintf("SELECT
			essay_files.account_file_id
		    FROM %s
		    join account_file on account_file.id = essay_files.file_id
		    where account_file.account_id = ?
		    and essay_files.essay_id = ?;", self::TABLE_ESSAY_FILES);

		$resultSet = $this->query($sql, array($accountId, $essayId));

		foreach ($resultSet as $row) {
			$result[] = $row->account_file_id;
		}

		return $result;
	}

    /**
     * Gets Applications Essays Text
     *
     * @param $accountId int, $essayId int
     * @access public
     * @return string
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function getApplicationEssayText($accountId, $essayId){
        $result = "";

        $sql = sprintf("SELECT text FROM %s WHERE account_id = ? AND essay_id = ?", self::TABLE_APPLICATION_ESSAY);
        $resultSet = $this->query($sql, array($accountId, $essayId));

        foreach($resultSet as $row) {
            $result = $row->text;
        }

        return $result;
    }

    /**
     * Sets Applications Essays Text
     *
     * @param $accountId int, $essayId int, $text string
     * @access public
     * @return int
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function setApplicationEssayText($accountId, $essayId, $text){
        $sql = sprintf("INSERT INTO %s
		 	(`account_id`, `essay_id`, `text`) VALUES(%d, %d, ?)
			ON DUPLICATE KEY UPDATE text = ?" , self::TABLE_APPLICATION_ESSAY, $accountId, $essayId);

        $result = $this->execute($sql, array($text, $text));
        return $result;
    }


	/**
	 * Gets Application Essay's Status
	 *
	 * @param $accountId int, $essayId int
	 * @access public
	 * @return int
	 *
	 * @author Frank Castillo <frank.castillo@yahoo.com>
	 */
	public function getApplicationEssayStatus($accountId, $essayId) {
		$result = "";

		$sql = sprintf("SELECT application_essay_status_id FROM %s WHERE account_id = ? AND essay_id = ?",
			self::TABLE_APPLICATION_ESSAY);
		$resultSet = $this->query($sql, array($accountId, $essayId));

		foreach($resultSet as $row) {
			$result = $row->application_essay_status_id;
		}

		return $result;
	}


	/**
	 * Sets Application Essay's Status
	 *
	 * @param $accountId int, $essayId int, $applicationEssayStatusId int
	 * @access public
	 * @return int
	 *
	 * @author Frank Castillo <frank.castillo@yahoo.com>
	 */
	public function setApplicationEssayStatus($accountId, $essayId, $applicationEssayStatusId) {
		$sql = sprintf("UPDATE %s SET application_essay_status_id = %d WHERE account_id = %d AND essay_id = %d" , self::TABLE_APPLICATION_ESSAY, $applicationEssayStatusId, $accountId, $essayId);

		$result = $this->execute($sql);
		return $result;
	}


	/**
	 * Gets Applications By Status
	 *
	 * @param $applicationStatusId int
	 * @param $deadline string
	 * @param $active int
	 *
	 * @access public
	 * @return Application[]
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getApplicationsByStatus($applicationStatusId, $deadline = null, $active = null) {
		$result = array();
		$bind = array($applicationStatusId);

		if (!isset($deadline) && !isset($active)) {
			$sql = sprintf("SELECT account_id, scholarship_id FROM %s WHERE application_status_id = ?", self::TABLE_APPLICATION);
		}
		else {
			$conditions = array();
			if (!empty($deadline)) {
				$conditions[] = "DATE(s.expiration_date) >= DATE(NOW())";
			}

			if (!empty($active)) {
				$conditions[] = "s.is_active = ?";
				$bind[] = $active;
			}

			$conditions = implode(" AND ", $conditions);
			$sql = sprintf("
				SELECT a.account_id, a.scholarship_id
				FROM %s AS a
				JOIN %s AS s ON s.scholarship_id = a.scholarship_id
				WHERE a.application_status_id = ?
				AND %s
			", self::TABLE_APPLICATION, self::TABLE_SCHOLARSHIP, $conditions);
		}

		$resultSet = $this->query($sql, $bind);

		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Application();
			$entity->populate($row);

			$result[] = $entity;
		}

		return $result;
	}


	public function changeApplicationStatus($accountId, $scholarshipId, $applicationStatusId, $submitedData = null, $comment = null) {
		$result = 0;

		$updates = array();
		$bind = array();

		$updates[] = "application_status_id = ?";
		$bind[] = $applicationStatusId;

		if (isset($submitedData)) {
			$updates[] = "submited_data = ?";
			$bind[] = $submitedData;
		}

		if (isset($comment)) {
			$updates[] = "comment = ?";
			$bind[] = $comment;
		}

		$bind[] = $accountId;
		$bind[] = $scholarshipId;

		$sql = sprintf("UPDATE %s SET %s WHERE account_id = ? AND scholarship_id = ?", self::TABLE_APPLICATION, implode(",", $updates));
		$result = $this->execute($sql, $bind);

		return $result;
	}



	/**
	 * Apply Scholarships (Updates Subscription Credit)
	 *
	 * @param $accountId int
	 * @param $scholarshipIds array
	 * @param $applicationStatusId int
     * @param $subscriptionId int Apply scholarships for specific subscription
	 *
	 * @return int
     * @throws \Exception
	 *
     * @access public
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function applyScholarships($accountId, $scholarshipIds, $applicationStatusId, $subscriptionId = null) {
		$result = 0;

		try {
			$this->beginTransaction();
            $transactionEmailService = app(TransactionalEmailService::class);
            /**
             * @var AccountRepository $accountRepo
             */
            $accountRepo = \EntityManager::getRepository(Account::class);
            $subscriptionService = new SubscriptionService();

			$now = date("Y-m-d H:i:s");
			$bulk = array();
			$params = array();

			$sql = sprintf("SELECT scholarship_id FROM %s WHERE account_id = ?", self::TABLE_APPLICATION);
			$resultSet = $this->query($sql, array($accountId));
			foreach ($resultSet as $row) {
				$key = array_search($row->scholarship_id, $scholarshipIds);
				if ($key !== false) {
					unset($scholarshipIds[$key]);
				}
			}

            $count = count($scholarshipIds);
            $sql = "";
            while($count > 0) {
                $insertScholarshipIds = array();

                $subscription = $subscriptionId ?
                    $subscriptionService->getSubscriptionById($subscriptionId) :
                    $subscriptionService->getLowestPrioritySubscriptionWithCredit($accountId);

                $credit = $subscription->getCredit();
                $remove = min($credit, $count);

                for($i = 0; $i < $remove; $i++){
                    $insertScholarshipIds[] = array_shift($scholarshipIds);
                }

                if($remove == 0 && $count > 0){
                    //  If all subscriptions are filled, and there is still credits to be removed
                    if($subscription = $subscriptionService->getUnlimitedUserSubscription($accountId)){
                        for($i = 0; $i < $count; $i++){
                            $insertScholarshipIds[] = array_shift($scholarshipIds);
                        }
                    }
                }

                $sql = sprintf("UPDATE %s SET credit = credit - %d WHERE subscription_id = ?", self::TABLE_SUBSCRIPTION, $remove);
                $this->execute($sql, array($subscription->getSubscriptionId()));

                //  Send email when package is exhausted
                $testSubscription = $subscriptionService->getSubscriptionById($subscription->getSubscriptionId());
                if($testSubscription->getCredit() === 0 && !$subscriptionService->getUnlimitedUserSubscription($accountId)){
                    /** @var ScholarshipRepository $repository */
                    $repository = \EntityManager::getRepository(\App\Entity\Scholarship::class);
                    $account = $accountRepo->findById($accountId);

                    $transactionEmailService->sendCommonEmail($account, TransactionalEmailService::PACKAGE_EXHAUSTED, array(
                        "url" => url(""),
                        "count" => count($repository->findActiveScholarshipsIds())
                    ));
                }

                // Prevent infinity loop on the moment we don't have subscriptions with credits
                $count = $remove != 0 ? ($count - $remove) : 0;

                if (!empty($insertScholarshipIds)) {
                    $sql = sprintf("
                    INSERT INTO %s(account_id, scholarship_id, application_status_id, subscription_id, date_applied)
                    VALUES
                ", self::TABLE_APPLICATION
                    );

                    foreach ($insertScholarshipIds as $key => $scholarshipId) {
                        $bulk[] = "(?, ?, ?, ?, ?)";

                        $params[] = $accountId;
                        $params[] = $scholarshipId;
                        $params[] = $applicationStatusId;
                        $params[] = $subscription->getSubscriptionId();
                        $params[] = $now;

                        unset($insertScholarshipIds[$key]);
                    }
                    $sql .= implode(",", $bulk);
                }
            }
            if(!empty($sql)) {
                $result = $this->execute($sql, $params);
            }
			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}
		return $result;
	}

	/**
	 * Submit Scholarships (After Saving Essays)
	 *
	 * @param $accountId int
	 * @param $scholarshipIds array
	 *
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function submitScholarships($accountId, $scholarshipIds) {
		$result = 0;

		$bind = array($accountId);
		foreach ($scholarshipIds as $scholarshipId) {
			$bind[] = $scholarshipId;
		}

		$marks = implode(array_fill(0, count($scholarshipIds), "?"), ",");
		$sql = sprintf("
			UPDATE %s
			SET application_status_id = %d
			WHERE account_id = ?
			AND scholarship_id IN(%s)
		", self::TABLE_APPLICATION, ApplicationStatus::PENDING, $marks);

		$result = $this->execute($sql, $bind);
		return $result;
	}


	/**
	 * Undo Apply For Scholarships (Updates Subscription Credit)
	 *
	 * @param $accountId int
	 * @param $scholarshipIds array
	 * @param $subscriptionId int
	 *
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function undoApplyScholarships($accountId, $scholarshipIds) {
		$result = 0;

		try {
			$this->beginTransaction();

			$marks = implode(array_fill(0, count($scholarshipIds), "?"), ",");
            $count = count($scholarshipIds);
			$bind = array($accountId);
			foreach ($scholarshipIds as $scholarshipId) {
				$bind[] = $scholarshipId;
			}

			$sql = sprintf("DELETE FROM %s WHERE account_id = ? AND scholarship_id IN(%s)", self::TABLE_APPLICATION, $marks);
			$result = \DB::delete($sql, $bind);

			$sql = sprintf("
				DELETE FROM %s
				WHERE account_id = ?
				AND essay_id IN(
					SELECT essay_id
					FROM %s
					WHERE scholarship_id IN(%s)
				)
			", self::TABLE_APPLICATION_ESSAY, self::TABLE_ESSAY, $marks);
			$this->execute($sql, $bind);

			while($count > 0){
                $subscriptionService = new SubscriptionService();
                $subscription = $subscriptionService->getTopPrioritySubscriptionWithCredit($accountId);

                $subscriptionId = $subscription->getSubscriptionId();
                $credit = $subscription->getCredit();
                $scholarshipCount = $subscription->getScholarshipsCount();
                $available = $scholarshipCount - $credit;
                $return = ($count > $available)?$available:$count;
                if($return == 0 && $count > 0){
                    //  If all subscriptions are filled, and there is still credits to be returned
                    $subscription = $subscriptionService->getTopPrioritySubscription($accountId);
                    if (!$subscription->isScholarshipsUnlimited()) {
                        $subscriptionId = $subscription->getSubscriptionId();
                        $return = $count;
                    } else {
                        $count = 0; //  Don't return credits to unlimited scholarships
                    }
                }
				$sql = sprintf("UPDATE %s SET credit = credit + %d WHERE subscription_id = ?", self::TABLE_SUBSCRIPTION, $return);
				$this->execute($sql, array($subscriptionId));
                $count -= $return;
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

	public function getEssays($accountId, $essayIds = array()) {
		$result = array();
		$condition = "";

		if (!empty($essayIds)) {
			if (!is_array($essayIds)) {
				$essayIds = array($essayIds);
			}
		}

		if (!empty($essayIds)) {
			$marks = implode(array_fill(0, count($essayIds), "?"), ",");

			$bind = array($accountId);
			foreach ($essayIds as $essayId) {
				$bind[] = $essayId;
			}

			$sql = sprintf("SELECT essay_id, text FROM %s WHERE account_id = ? AND essay_id IN(%s)", self::TABLE_APPLICATION_ESSAY);
			$resultSet = $this->query($sql, array($bind));
		}
		else {
			$sql = sprintf("SELECT essay_id, text FROM %s WHERE account_id = ?", self::TABLE_APPLICATION_ESSAY);
			$resultSet = $this->query($sql, array($accountId));
		}

		foreach ($resultSet as $row) {
			$result[$row->essay_id] = $row->text;
		}

		return $result;
	}

	public function getEssaysSaved($essayIds) {
		$result = array();

		if (!is_array($essayIds)) {
			$essayIds = array($essayIds);
		}

		$marks = implode(array_fill(0, count($essayIds), "?"), ",");
		$sql = sprintf("
			SELECT essay_id, COUNT(*) AS count
			FROM %s
			WHERE essay_id IN(%s)
			GROUP BY essay_id
			", self::TABLE_APPLICATION_ESSAY, $marks
		);

		$resultSet = $this->query($sql, $essayIds);
		foreach($resultSet as $row) {
			$result[$row->essay_id] = $row->count;
		}

		return $result;
	}
}
