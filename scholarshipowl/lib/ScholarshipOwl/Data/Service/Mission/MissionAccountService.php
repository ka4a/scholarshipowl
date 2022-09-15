<?php

/**
 * MissionAccountService
 *
 * @package     ScholarshipOwl\Data\Service\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	22. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Mission;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\SubscriptionAcquiredType;
use App\Events\Account\MissionCompletedEvent;
use ScholarshipOwl\Data\Entity\Mission\MissionAccount;
use ScholarshipOwl\Data\Entity\Mission\MissionGoal;
use ScholarshipOwl\Data\Entity\Mission\MissionGoalAccount;
use ScholarshipOwl\Data\Entity\Mission\MissionGoalType;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Data\Entity\Mission\Mission;


class MissionAccountService extends AbstractService implements IMissionAccountService {
	public function saveMissionsGoals($missionsGoals, $accountId) {
		$result = 0;

		try {
			$this->beginTransaction();

			$activeAccountMissionIds = array();


			// Get Existing Mission Ids
			$sql = sprintf("
				SELECT ma.mission_id
				FROM %s AS ma
				JOIN %s AS m ON m.mission_id = ma.mission_id
				WHERE m.is_active = 1
				AND ma.account_id = ?
				AND ma.status <> ?
			", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION);

			$resultSet = $this->query($sql, array($accountId, MissionAccount::STATUS_COMPLETED));
			foreach ($resultSet as $row) {
				$activeAccountMissionIds[] = $row->mission_id;
			}


			// Save Mission & Goals
			foreach ($missionsGoals as $missionId => $goals) {

				// Save Mission & Goals If Not Saved
				if (!in_array($missionId, $activeAccountMissionIds)) {
					$sql = sprintf("
						INSERT INTO %s (mission_id, account_id, status, points, date_started, date_ended)
						VALUES (
							?, ?, ?,
							(SELECT SUM(points) FROM %s WHERE mission_id = ?),
							NOW(), '0000-00-00 00:00:00'
						)
					", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION_GOAL);

					$this->execute($sql, array($missionId, $accountId, MissionAccount::STATUS_PENDING, $missionId));
					$missionAccountId = $this->getLastInsertId();

					$sql = sprintf("
						INSERT INTO %s (mission_account_id, mission_goal_id, is_started, is_accomplished, date_started, date_accomplished)
						SELECT %d, mission_goal_id, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'
						FROM %s
						WHERE mission_id = ?
					", self::TABLE_MISSION_GOAL_ACCOUNT, $missionAccountId, self::TABLE_MISSION_GOAL);
					$this->execute($sql, array($missionId));
				}


				// Update Mission Goals For Start
				if (!empty($goals)) {
					$sql = sprintf("
						UPDATE %s AS mga
						JOIN %s AS ma ON ma.mission_account_id = mga.mission_account_id
						SET
							mga.is_started = 1,
							mga.date_started = NOW()
						WHERE ma.account_id = ?
						AND mga.mission_goal_id IN(%s)
					", self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_ACCOUNT, implode(",", $goals));

					$this->execute($sql, array($accountId));
				}
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

	public function saveAffiliateGoal($affiliateGoalId, $accountId) {
		try {
			$this->beginTransaction();

			$goals = array();
			$missionAccountIds = array();


			// Get Goals Using Affiliate Goal ID
			$sql = sprintf("
				SELECT mg.mission_id, mg.mission_goal_id
				FROM %s AS mg
				JOIN %s AS m ON m.mission_id = mg.mission_id
				WHERE mg.affiliate_goal_id = ?
				AND m.is_active = 1
			", self::TABLE_MISSION_GOAL, self::TABLE_MISSION);

			$resultSet = $this->query($sql, array($affiliateGoalId));
			foreach ($resultSet as $row) {
				if (!array_key_exists($row->mission_id, $goals)) {
					$goals[$row->mission_id] = array();
				}

				$goals[$row->mission_id][] = $row->mission_goal_id;
			}


			// Get Active Mission Ids From Mission Account
			$sql = sprintf("
				SELECT ma.mission_id
				FROM %s AS ma
				JOIN %s AS m ON m.mission_id = ma.mission_id
				WHERE m.is_active = 1
				AND ma.account_id = ?
				AND ma.status <> ?
			", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION);

			$resultSet = $this->query($sql, array($accountId, MissionAccount::STATUS_COMPLETED));
			foreach ($resultSet as $row) {
				$missionAccountIds[] = $row->mission_id;
			}


			// Save Mission & Goals
			foreach ($goals as $missionId => $goalIds) {

				// Save Mission & Goals If Not Saved
				if (!in_array($missionId, $missionAccountIds)) {
					$sql = sprintf("
						INSERT INTO %s (mission_id, account_id, status, points, date_started, date_ended)
						VALUES (
							?, ?, ?,
							(SELECT SUM(points) FROM %s WHERE mission_id = ?),
							NOW(), '0000-00-00 00:00:00'
						)
					", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION_GOAL);

					$this->execute($sql, array($missionId, $accountId, MissionAccount::STATUS_PENDING, $missionId));
					$missionAccountId = $this->getLastInsertId();

					$sql = sprintf("
						INSERT INTO %s (mission_account_id, mission_goal_id, is_started, is_accomplished, date_started, date_accomplished)
						SELECT %d, mission_goal_id, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'
						FROM %s
						WHERE mission_id = ?
					", self::TABLE_MISSION_GOAL_ACCOUNT, $missionAccountId, self::TABLE_MISSION_GOAL);
					$this->execute($sql, array($missionId));
				}


				// Update Mission Goals To Accomplished & Mission Status
				if (!empty($goalIds)) {
					$sql = sprintf("
						UPDATE %s AS mga
						JOIN %s AS ma ON ma.mission_account_id = mga.mission_account_id
						SET
							mga.is_accomplished = 1,
							mga.date_accomplished = NOW()
						WHERE ma.account_id = ?
						AND mga.mission_goal_id IN(%s)
					", self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_ACCOUNT, implode(",", $goalIds));

					$this->execute($sql, array($accountId));


					$sql = sprintf("
						UPDATE %s AS ma
						JOIN %s AS m ON m.mission_id = ma.mission_id
						SET ma.status = ?
						WHERE ma.mission_id = ?
						AND ma.account_id = ?
						AND ma.status <> ?
						AND m.is_active = 1
					", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION);

					$this->execute($sql, array(MissionAccount::STATUS_IN_PROGRESS, $missionId, $accountId, MissionAccount::STATUS_COMPLETED));
				}
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}
	}

	public function saveReferAFriendGoal($referralAwardId, $accountId) {
		try {
			$this->beginTransaction();
			$missionId = null;
			$goals = array();
            $goalIds = array();
			$missionGoalIds = array();

            // Get Goals Using Referral Award ID
            $sql = sprintf("
				SELECT mg.mission_id, mg.mission_goal_id
				FROM %s AS mg
				JOIN %s AS m ON m.mission_id = mg.mission_id
				WHERE mg.referral_award_id = ?
				AND m.is_active = 1
			", self::TABLE_MISSION_GOAL, self::TABLE_MISSION);

            $resultSet = $this->query($sql, array($referralAwardId));
            foreach ($resultSet as $row) {
                $goalIds[] = $row->mission_goal_id;
                $missionId = $row->mission_id;
            }

			// Check if the Mission is started by this user
			$sql = sprintf("
				SELECT ma.mission_account_id
				FROM %s AS ma
				WHERE ma.mission_id = ?
				AND ma.account_id = ?
				AND ma.status <> ?
			", self::TABLE_MISSION_ACCOUNT);

			$result = $this->query($sql, array($missionId, $accountId, MissionAccount::STATUS_COMPLETED));

            //  If the mission is not started start the mission
			if(count($result) == 0){
                $sql = sprintf("
						INSERT INTO %s (mission_id, account_id, status, points, date_started, date_ended)
						VALUES (
							?, ?, ?,
							(SELECT SUM(points) FROM %s WHERE mission_id = ?),
							NOW(), '0000-00-00 00:00:00'
						)
					", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION_GOAL);

                $this->execute($sql, array($missionId, $accountId, MissionAccount::STATUS_PENDING, $missionId));
                $missionAccountId = $this->getLastInsertId();
            }else{
                $missionAccountId = $result[0]->mission_account_id;
            }

            //  Get all active goals for this mission
            $sql = sprintf("
				SELECT mission_goal_id
				FROM %s
				WHERE mission_id = ?
				AND is_active = 1
			", self::TABLE_MISSION_GOAL);

            $resultSet = $this->query($sql, array($missionId));
            foreach ($resultSet as $row) {
                $missionGoalIds[] = $row->mission_goal_id;
            }

            //  Get all started goals for this mission
            $sql = sprintf("
				SELECT mission_goal_id
				FROM %s
				WHERE mission_account_id = ?
			", self::TABLE_MISSION_GOAL_ACCOUNT);

            $resultSet = $this->query($sql, array($missionAccountId));
            foreach ($resultSet as $row) {
                $goals[] = $row->mission_goal_id;
            }

            $addedNewGoal = false;
			foreach ($missionGoalIds as $missionGoalId) {
				// Save Goals If Not Saved
				if (!in_array($missionGoalId, $goals)) {
					$sql = sprintf("
						INSERT INTO %s (mission_account_id, mission_goal_id, is_started, is_accomplished, date_started, date_accomplished)
						VALUES (%d, %d, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00')
					", self::TABLE_MISSION_GOAL_ACCOUNT, $missionAccountId, $missionGoalId);
					$this->execute($sql);

                    $addedNewGoal = true;
				}


				// Update Mission Goals From Request To Started
				if (!empty($goalIds) && $addedNewGoal) {
					$sql = sprintf("
						UPDATE %s AS mga
						JOIN %s AS ma ON ma.mission_account_id = mga.mission_account_id
						SET
							mga.is_started = 1,
							mga.date_started = NOW()
						WHERE ma.account_id = ?
						AND mga.mission_goal_id IN(%s)
					", self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_ACCOUNT, implode(",", $goalIds));

					$this->execute($sql, array($accountId));
				}
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}
	}

    public function completeReferAFriendGoals($accountId){
        try{
            $this->beginTransaction();
            $sql = sprintf("SELECT mga.date_started, mga.mission_goal_account_id, ra.referrals_number
              FROM %s mga
              JOIN %s mg ON mg.mission_goal_id = mga.mission_goal_id
              JOIN %s ra ON ra.referral_award_id = mg.referral_award_id
              JOIN %s ma ON ma.mission_id = mg.mission_id
              WHERE mga.is_started = 1 AND mga.is_accomplished = 0
              AND mg.mission_goal_type_id = %d AND mg.is_active = 1
              AND ma.account_id = ?", self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_GOAL, self::TABLE_REFERRAL_AWARD, self::TABLE_MISSION_ACCOUNT, MissionGoalType::REFER_A_FRIEND);

            $resultSet = $this->query($sql, array($accountId));

            if(count($resultSet)){
                foreach($resultSet as $row){
                    $startDate = $row->date_started;
                    $missionGoalAccountId = $row->mission_goal_account_id;
                    $referralsNumber = $row->referrals_number;

                    $sql = sprintf("SELECT COUNT(*) as num_referred
                        FROM %s r
                        JOIN %s a ON a.account_id = r.referred_account_id
                        WHERE a.created_date > ?
                        AND r.referral_account_id = ?", self::TABLE_REFERRAL, self::TABLE_ACCOUNT);

                    $result = $resultSet = $this->query($sql, array($startDate, $accountId));

                    if(count($result) && $result[0]->num_referred >= $referralsNumber){
                        $sql = sprintf("UPDATE %s
                            SET
                                is_accomplished = 1,
                                date_accomplished = NOW()
                            WHERE mission_goal_account_id = ?
                        ", self::TABLE_MISSION_GOAL_ACCOUNT);

                        $this->execute($sql, array($missionGoalAccountId));
                    }
                }
            }

            $this->commit();
        }catch (\Exception $exc) {
            $this->rollback();
            throw $exc;
        }
    }


   	public function searchMissionAccount($params = array(), $limit = "") {
		$result = array(
			"count" => 0,
			"data" => array()
		);

		// @TODO Complete Search

		$sql = sprintf("
			SELECT
				ma.mission_account_id, ma.mission_id, ma.account_id, ma.status, ma.points, ma.date_started, ma.date_ended,
				m.name, m.is_active,
				p.first_name, p.last_name
			FROM %s AS ma
			JOIN %s AS m ON m.mission_id = ma.mission_id
			JOIN %s AS p ON p.account_id = ma.account_id
			ORDER BY ma.mission_account_id DESC
		", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION, self::TABLE_PROFILE);

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$entity = new MissionAccount();
			$entity->setMissionAccountId($row->mission_account_id);
			$entity->setStatus($row->status);
			$entity->setPoints($row->points);
			$entity->setDateStarted($row->date_started);
			$entity->setDateEnded($row->date_ended);
			$entity->getMission()->setMissionId($row->mission_id);
			$entity->getMission()->setName($row->name);
			$entity->getMission()->setActive($row->is_active);
			$entity->getAccount()->setAccountId($row->account_id);
			$entity->getAccount()->getProfile()->setFirstName($row->first_name);
			$entity->getAccount()->getProfile()->setLastName($row->last_name);

			$result["data"][$entity->getMissionAccountId()] = $entity;
		}


		$missionAccountIds = array_keys($result["data"]);
		if (!empty($missionAccountIds)) {
			$sql = sprintf("
				SELECT
					mg.mission_goal_id, mg.mission_id, mg.mission_goal_type_id, mg.name, mg.points, mg.affiliate_goal_id,
					mga.mission_goal_account_id, mga.mission_account_id, mga.is_accomplished,
					ag.name AS affiliate_goal_name, ag.url AS affiliate_goal_url,
					a.name AS affiliate_name
				FROM %s AS mg
				JOIN %s AS mga ON mga.mission_goal_id = mg.mission_goal_id
				JOIN %s AS ag ON ag.affiliate_goal_id = mg.affiliate_goal_id
				JOIN %s AS a ON a.affiliate_id = ag.affiliate_id
				WHERE mga.mission_account_id IN(%s)
			", self::TABLE_MISSION_GOAL, self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_AFFILIATE_GOAL, self::TABLE_AFFILIATE, implode(",", $missionAccountIds));

			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$entity = new MissionGoalAccount();
				$entity->setMissionGoalAccountId($row->mission_goal_account_id);
				$entity->setAccomplished($row->is_accomplished);
				$entity->getMissionGoal()->setMissionGoalId($row->mission_goal_id);
				$entity->getMissionGoal()->getMissionGoalType()->setMissionGoalTypeId($row->mission_goal_type_id);
				$entity->getMissionGoal()->setName($row->name);
				$entity->getMissionGoal()->setPoints($row->points);
				$entity->getMissionGoal()->getAffiliateGoal()->setAffiliateGoalId($row->affiliate_goal_id);
				$entity->getMissionGoal()->getAffiliateGoal()->setName($row->affiliate_goal_name);
				$entity->getMissionGoal()->getAffiliateGoal()->setUrl($row->affiliate_goal_url);
				$entity->getMissionGoal()->getAffiliateGoal()->getAffiliate()->setName($row->affiliate_name);

				$result["data"][$row->mission_account_id]->addMissionGoalAccount($entity);
			}
		}

		$result["count"] = count($result["data"]);
		return $result;
	}


	public function getMissionAccounts($accountId) {
		$result = array();

		$sql = sprintf("
		 	SELECT
				ma.mission_account_id, ma.status, ma.points, ma.date_started, ma.date_ended,
				m.mission_id, m.package_id, m.name, m.description, m.message, m.is_active, m.start_date, m.end_date, m.reward_message
		 	FROM %s AS ma
		 	JOIN %s AS m ON m.mission_id = ma.mission_id
		 	WHERE ma.account_id = ?
		 	AND m.is_visible = 1
		 	AND m.is_active = 1
		 ", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION);

		$resultSet = $this->query($sql, array($accountId));
		foreach ($resultSet as $row) {
			$entity = new MissionAccount();
			$entity->setMissionAccountId($row->mission_account_id);
			$entity->setStatus($row->status);
			$entity->setPoints($row->points);
			$entity->setDateStarted($row->date_started);
			$entity->setDateEnded($row->date_ended);
			$entity->getMission()->setMissionId($row->mission_id);
			$entity->getMission()->setName($row->name);
			$entity->getMission()->setDescription($row->description);
			$entity->getMission()->setMessage($row->message);
			$entity->getMission()->setActive($row->is_active);
			$entity->getMission()->setStartDate($row->start_date);
			$entity->getMission()->setEndDate($row->end_date);
			$entity->getMission()->setRewardMessage($row->reward_message);

            $result[$entity->getMissionAccountId()] = $entity;
		}

		return $result;
	}

	public function getMissionGoalAccounts($accountId) {
		$result = array();

		$sql = sprintf("
			SELECT
				mga.mission_goal_account_id, mga.is_started, mga.is_accomplished, mga.date_started, mga.date_accomplished,
				a.name AS affiliate_name, ag.name AS affiliate_goal_name,
				m.name AS mission_name, mg.points AS mission_goal_points
			FROM %s AS mga
			JOIN %s AS ma ON ma.mission_account_id = mga.mission_account_id
			JOIN %s AS m ON m.mission_id = ma.mission_id
			JOIN %s AS mg ON mg.mission_id = m.mission_id AND mg.mission_goal_id = mga.mission_goal_id
			JOIN %s AS ag ON ag.affiliate_goal_id = mg.affiliate_goal_id
			JOIN %s AS a ON a.affiliate_id = ag.affiliate_id
			WHERE ma.account_id = ?
			AND mg.mission_goal_type_id = %d
			ORDER BY m.mission_id DESC
		",
			self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION, self::TABLE_MISSION_GOAL,
			self::TABLE_AFFILIATE_GOAL, self::TABLE_AFFILIATE, MissionGoalType::AFFILIATE);

		$resultSet = $this->query($sql, array($accountId));
		foreach ($resultSet as $row) {
			$entity = new MissionGoalAccount();
			$entity->setMissionGoalAccountId($row->mission_goal_account_id);
			$entity->setStarted($row->is_started);
			$entity->setAccomplished($row->is_accomplished);
			$entity->setDateStarted($row->date_started);
			$entity->setDateAccomplished($row->date_accomplished);
			$entity->getMissionAccount()->getMission()->setName($row->mission_name);
			$entity->getMissionGoal()->getAffiliateGoal()->getAffiliate()->setName($row->affiliate_name);
			$entity->getMissionGoal()->getAffiliateGoal()->setName($row->affiliate_goal_name);
			$entity->getMissionGoal()->setPoints($row->mission_goal_points);
			$entity->getMissionAccount()->getAccount()->setAccountId($accountId);

			$result[$row->mission_goal_account_id] = $entity;
		}

		return $result;
	}


    public function getMissionAccountStatus($missionId, $accountId){
        $result = null;

        $sql = sprintf("
		 	SELECT status
		 	FROM %s
		 	WHERE mission_id = ?
		 	AND account_id = ?
		 ", self::TABLE_MISSION_ACCOUNT);

        $resultSet = $this->query($sql, array($missionId, $accountId));

        if($resultSet){
            $result = $resultSet[0]->status;
        }

        return $result;
    }

    public function isNotified($missionId, $accountId){
        $result = null;

        $sql = sprintf("
		 	SELECT is_notified
		 	FROM %s
		 	WHERE mission_id = ?
		 	AND account_id = ?
		 ", self::TABLE_MISSION_ACCOUNT);

        $resultSet = $this->query($sql, array($missionId, $accountId));

        if($resultSet){
            $result = $resultSet[0]->is_notified;
        }

        return $result;
    }

    public function setNotified($missionId, $accountId){
        $result = null;

        $sql = sprintf("
		 	UPDATE %s
		 	SET is_notified = 1
		 	WHERE mission_id = ?
		 	AND account_id = ?
		 	AND status = ?
		 ", self::TABLE_MISSION_ACCOUNT);

        $this->execute($sql, array($missionId, $accountId, MissionAccount::STATUS_COMPLETED));
    }

	public function getMissionAccountGoalStatusesByMissionId($missionId, $accountId) {
		 $result = array();

		 $sql = sprintf("
		 	SELECT mga.mission_goal_id, mga.is_accomplished
		 	FROM %s AS mga
		 	JOIN %s AS ma ON ma.mission_account_id = mga.mission_account_id
		 	WHERE ma.mission_id = ?
		 	AND ma.account_id = ?
		 ", self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_ACCOUNT);

		 $resultSet = $this->query($sql, array($missionId, $accountId));
		 foreach ($resultSet as $row) {
		 	$result[$row->mission_goal_id] = $row->is_accomplished;
		 }

		 return $result;
	}

    public function getMissionAccountGoalStartedByMissionId($missionId, $accountId) {
        $result = array();

        $sql = sprintf("
		 	SELECT mga.mission_goal_id, mga.is_started
		 	FROM %s AS mga
		 	JOIN %s AS ma ON ma.mission_account_id = mga.mission_account_id
		 	WHERE ma.mission_id = ?
		 	AND ma.account_id = ?
		 ", self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_ACCOUNT);

        $resultSet = $this->query($sql, array($missionId, $accountId));
        foreach ($resultSet as $row) {
            $result[$row->mission_goal_id] = $row->is_started;
        }

        return $result;
    }

	public function getLastStartedMissionByAccountId($accountId) {
		$result = null;

		$sql = sprintf("
			SELECT m.name, ma.status, ma.date_started, ma.date_ended
			FROM %s AS m
			JOIN %s AS ma ON ma.mission_id = m.mission_id
			WHERE ma.account_id = ?
			ORDER BY ma.date_started DESC
			LIMIT 1
		", self::TABLE_MISSION, self::TABLE_MISSION_ACCOUNT);

		$resultSet = $this->query($sql, array($accountId));
		foreach ($resultSet as $row) {
			$entity = new MissionAccount();
			$entity->getMission()->setName($row->name);
			$entity->setStatus($row->status);
			$entity->setDateStarted($row->date_started);
			$entity->setDateEnded($row->date_ended);

			$result = $entity;
		}

		return $result;
	}

	public function getLastStartedMissionByAccountIds($accountIds) {
		$result = array();

		$sql = sprintf("
			SELECT * FROM (
				SELECT m.name, ma.account_id, ma.status, ma.date_started, ma.date_ended
				FROM %s AS m
				JOIN %s AS ma ON ma.mission_id = m.mission_id
				WHERE ma.account_id IN(%s)
				ORDER BY ma.date_started DESC
			) AS tmp
			GROUP BY tmp.account_id
		", self::TABLE_MISSION, self::TABLE_MISSION_ACCOUNT, implode(",", $accountIds));

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$entity = new MissionAccount();
			$entity->getMission()->setName($row->name);
			$entity->setStatus($row->status);
			$entity->setDateStarted($row->date_started);
			$entity->setDateEnded($row->date_ended);

			$result[$row->account_id] = $entity;
		}

		return $result;
	}

	public function getNumberOfCompletedGoalsByAccountId($accountId) {
		$result = 0;

		$sql = sprintf("
			SELECT COUNT(mga.mission_goal_account_id) AS count
			FROM %s AS ma
			JOIN % AS mga ON mga.mission_account_id = ma.mission_account_id
			WHERE mga.is_accomplished = 1
			AND ma.account_id = ?
		", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION_GOAL_ACCOUNT);

		$resultSet = $this->query($sql, array($accountId));
		foreach ($resultSet as $row) {
			$result = $row->count;
		}

		return $result;
	}

	public function getNumberOfCompletedGoalsByAccountIds($accountIds) {
		$result = array();

		$sql = sprintf("
			SELECT ma.account_id, COUNT(mga.mission_goal_account_id) AS count
			FROM %s AS ma
			JOIN %s AS mga ON mga.mission_account_id = ma.mission_account_id
			WHERE mga.is_accomplished = 1
			AND ma.account_id IN(%s)
			GROUP BY ma.account_id
		", self::TABLE_MISSION_ACCOUNT, self::TABLE_MISSION_GOAL_ACCOUNT, implode(",", $accountIds));

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$result[$row->account_id] = $row->count;
		}

		return $result;
	}

	public function getNumberOfStartedGoals($missionGoalsId) {
		$result = array();

		$sql = sprintf("
			SELECT mga.mission_goal_id, COUNT(mga.mission_goal_account_id) AS count
			FROM %s AS mga
			WHERE mga.mission_goal_id IN(%s)
			GROUP BY mga.mission_goal_id
		", self::TABLE_MISSION_GOAL_ACCOUNT, implode(",", $missionGoalsId));

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$result[$row->mission_goal_id] = $row->count;
		}

		return $result;
	}

	public function completeMissions($accountId) {
		$result = array();

		try {
			$this->beginTransaction();

			$sql = sprintf("
				SELECT mg.mission_id, SUM(mg.points) AS points
				FROM %s AS mg
				JOIN %s AS mga ON mga.mission_goal_id = mg.mission_goal_id AND mga.is_accomplished = 1
				JOIN %s AS ma ON ma.mission_account_id = mga.mission_account_id AND ma.mission_id = mg.mission_id AND ma.status <> ?
				WHERE ma.account_id = ?
				GROUP BY mg.mission_id
				HAVING SUM(mg.points) >= 100
			", self::TABLE_MISSION_GOAL, self::TABLE_MISSION_GOAL_ACCOUNT, self::TABLE_MISSION_ACCOUNT);

			$resultSet = $this->query($sql, array(MissionAccount::STATUS_COMPLETED, $accountId));
			foreach ($resultSet as $row) {
				$result[$row->mission_id] = $row->points;
			}


			if (!empty($result)) {
				$sql = sprintf("
					UPDATE %s SET status = ?, date_ended = NOW()
					WHERE account_id = ? AND mission_id IN(%s)
				", self::TABLE_MISSION_ACCOUNT, implode(",", array_keys($result)));

				$this->execute($sql, array(MissionAccount::STATUS_COMPLETED, $accountId));

                $service = new \ScholarshipOwl\Data\Service\Mission\MissionService();
                $packages = $service->getMissionsPackagesIds(array_keys($result));

                foreach ($packages as $packageId) {
                    \PaymentManager::applyPackageOnAccount($accountId, $packageId, SubscriptionAcquiredType::MISSION);
                }

				// Fire Event
                \Event::dispatch(new MissionCompletedEvent($accountId, $result));
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}
}
