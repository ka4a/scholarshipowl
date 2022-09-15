<?php

/**
 * MissionService
 *
 * @package     ScholarshipOwl\Data\Service\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	15. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Mission;

use ScholarshipOwl\Data\Entity\Mission\Mission;
use ScholarshipOwl\Data\Entity\Mission\MissionGoal;
use ScholarshipOwl\Data\Entity\Mission\MissionGoalType;
use ScholarshipOwl\Data\Service\AbstractService;


class MissionService extends AbstractService implements IMissionService {

    const CACHE_TAGS = ['missions', 'package'];
    const CACHE_KEY_MISSIONS_PACKAGES = 'missions.list.packages';

    public function getMission($missionId, $goals = true) {
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE mission_id = ?", self::TABLE_MISSION);
		$resultSet = $this->query($sql, array($missionId));
		foreach ($resultSet as $row) {
			$result = new Mission();
			$result->populate((array) $row);
		}

		if (isset($result) && $goals == true) {
			$sql = sprintf("SELECT * FROM %s WHERE mission_id = ?", self::TABLE_MISSION_GOAL);
			$resultSet = $this->query($sql, array($missionId));
			foreach ($resultSet as $row) {
				$entity = new MissionGoal();
				$entity->populate((array) $row);

				$result->addMissionGoal($entity);
			}
		}

		return $result;
	}

	public function getMissions() {
		$result = array();
		$sql = sprintf("
			SELECT
				m.mission_id, m.package_id, m.name, m.description, m.is_active, m.start_date, m.end_date,
				p.name AS package_name, p.price, p.scholarships_count, p.is_scholarships_unlimited
			FROM %s AS m, %s AS p
			WHERE m.package_id = p.package_id
			ORDER BY package_id DESC
		", self::TABLE_MISSION, self::TABLE_PACKAGE);
		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Mission();
			$entity->populate($row);
			$entity->getPackage()->populate($row);
			$entity->getPackage()->setName($row["package_name"]);

			$result[$entity->getMissionId()] = $entity;
		}

		return $result;
	}

	public function getMissionsData($onlyActive = false) {
		$result = array();
		$conditions = ($onlyActive == true) ? "WHERE is_active = 1" : "";

		$sql = sprintf("
			SELECT mission_id, name, description, start_date, end_date, is_active, message, reward_message, package_id
			FROM %s
			%s
			AND is_visible = 1
		", self::TABLE_MISSION, $conditions);

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$entity = new Mission();
			$entity->populate((array) $row);

           $result[$entity->getMissionId()] = $entity;
		}

		return $result;
	}

	public function getMissionsList() {
		$result = array();

		$sql = sprintf("SELECT mission_id, name FROM %s", self::TABLE_MISSION);
		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$result[$row->mission_id] = $row->name;
		}

		return $result;
	}

	public function getMissionsPackages() {
		$result = array();

        if (null === ($resultSet = \Cache::tags(static::CACHE_TAGS)->get(static::CACHE_KEY_MISSIONS_PACKAGES))) {
            $sql = sprintf("
                SELECT
                    m.mission_id, m.name, m.description, m.message, m.success_message,
                    p.package_id, p.name AS package_name, p.description AS package_description, p.scholarships_count, p.is_scholarships_unlimited,
                    p.expiration_type, p.expiration_period_type, p.expiration_date, p.message AS package_message
                FROM %s AS m
                JOIN %s AS p ON p.package_id = m.package_id
                WHERE m.is_active = 1
                AND m.is_visible = 1
                AND m.end_date >= '%s'
            ", self::TABLE_MISSION, self::TABLE_PACKAGE, date("Y-m-d"));
            \Cache::tags(static::CACHE_TAGS)
                ->put(static::CACHE_KEY_MISSIONS_PACKAGES, $resultSet = $this->query($sql), 60 * 60);
        }

		foreach ($resultSet as $row) {
			$entity = new Mission();
			$entity->setMissionId($row->mission_id);
			$entity->setName($row->name);
			$entity->setDescription($row->description);
			$entity->setMessage($row->message);
			$entity->setSuccessMessage($row->success_message);
			$entity->getPackage()->setPackageId($row->package_id);
			$entity->getPackage()->setName($row->package_name);
			$entity->getPackage()->setDescription($row->package_description);
			$entity->getPackage()->setScholarshipsCount($row->scholarships_count);
			$entity->getPackage()->setScholarshipsUnlimited($row->is_scholarships_unlimited);
			$entity->getPackage()->setExpirationType($row->expiration_type);
			$entity->getPackage()->setExpirationPeriodType($row->expiration_period_type);
			$entity->getPackage()->setExpirationDate($row->expiration_date);
			$entity->getPackage()->setMessage($row->package_message);

			$result[$entity->getMissionId()] = $entity;
		}

		$missionIds = array_keys($result);
		if (!empty($missionIds)) {
			$sql = sprintf("SELECT * FROM %s WHERE mission_id IN(%s)", self::TABLE_MISSION_GOAL, implode(",", $missionIds));
			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$entity = new MissionGoal();
				$entity->populate($row);

				$result[$entity->getMission()->getMissionId()]->addMissionGoal($entity);
			}
		}

		return $result;
	}

	public function getMissionsPackagesIds($missionIds) {
		$result = array();

		$sql = sprintf("
			SELECT mission_id, package_id
			FROM %s
			WHERE mission_id IN(%s)
		", self::TABLE_MISSION, implode(",", $missionIds));

		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$result[$row->mission_id] = $row->package_id;
		}

		return $result;
	}

	public function getMissionAffiliateGoals($missionId) {
		$result = array();

		$sql = sprintf("
			SELECT DISTINCT
				mg.mission_goal_id, mg.name, mg.points,
				ag.affiliate_goal_id, ag.name AS affiliate_goal_name, ag.url, ag.description, ag.logo, ag.redirect_description
			FROM %s AS mg
			JOIN %s AS ag ON ag.affiliate_goal_id = mg.affiliate_goal_id
			WHERE mg.mission_goal_type_id = %d
			AND mg.mission_id = ?
			AND mg.is_active = 1
		", self::TABLE_MISSION_GOAL, self::TABLE_AFFILIATE_GOAL, MissionGoalType::AFFILIATE);

		$resultSet = $this->query($sql, array($missionId));
		foreach ($resultSet as $row) {
			$entity = new MissionGoal();
			$entity->setMissionGoalId($row->mission_goal_id);
			$entity->getMissionGoalType()->setMissionGoalTypeId(MissionGoalType::AFFILIATE);
			$entity->setName($row->name);
			$entity->setPoints($row->points);
			$entity->getAffiliateGoal()->setAffiliateGoalId($row->affiliate_goal_id);
			$entity->getAffiliateGoal()->setName($row->affiliate_goal_name);
			$entity->getAffiliateGoal()->setUrl($row->url);
			$entity->getAffiliateGoal()->setDescription($row->description);
			$entity->getAffiliateGoal()->setLogo($row->logo);
			$entity->getAffiliateGoal()->setRedirectDescription($row->redirect_description);

			$result[$entity->getMissionGoalId()] = $entity;
		}

		return $result;
	}

	public function getOrderedMissionGoals($missionId, $onlyActive = false) {
		$result = array();
		$sql = sprintf("
			SELECT DISTINCT
				*
			FROM %s AS mg
			WHERE mg.mission_id = ?
			".(($onlyActive)?"AND is_active = 1":"")."
			ORDER BY -mg.ordering DESC
		", self::TABLE_MISSION_GOAL);

		$resultSet = $this->query($sql, array($missionId));

		foreach ($resultSet as $row) {
			$entity = new MissionGoal();
			$entity->setMissionGoalId($row->mission_goal_id);
			$entity->setName($row->name);
			$entity->setPoints($row->points);
			$entity->setParameters($row->parameters);
			$entity->getMissionGoalType()->setMissionGoalTypeId($row->mission_goal_type_id);
			$entity->setOrdering($row->ordering);
			$entity->setActive($row->is_active);
			if($row->mission_goal_type_id == MissionGoalType::AFFILIATE){
				$sql = sprintf("
					SELECT DISTINCT
						ag.affiliate_goal_id, ag.name AS affiliate_goal_name, ag.url, ag.description, ag.logo, ag.redirect_description
					FROM %s AS ag
					WHERE ag.affiliate_goal_id = ?
					LIMIT 1
				", self::TABLE_AFFILIATE_GOAL);

				$resultSetAffiliate = $this->query($sql, array($row->affiliate_goal_id));

				foreach($resultSetAffiliate as $affiliateGoal){
					$entity->getAffiliateGoal()->setAffiliateGoalId($row->affiliate_goal_id);
					$entity->getAffiliateGoal()->setName($affiliateGoal->affiliate_goal_name);
					$entity->getAffiliateGoal()->setUrl($affiliateGoal->url);
					$entity->getAffiliateGoal()->setDescription($affiliateGoal->description);
					$entity->getAffiliateGoal()->setLogo($affiliateGoal->logo);
					$entity->getAffiliateGoal()->setRedirectDescription($affiliateGoal->redirect_description);
				}
			}else if($row->mission_goal_type_id == MissionGoalType::REFER_A_FRIEND){
				$sql = sprintf("
					SELECT
						ra.referral_award_id, ra.name AS referral_award_name, ra.description, ra.redirect_description
					FROM %s ra
					WHERE ra.referral_award_id = ?
					LIMIT 1
				", self::TABLE_REFERRAL_AWARD);
				$resultSetRefer = $this->query($sql, array($missionId));

				foreach($resultSetRefer as $referAward){
					$entity->getReferralAward()->setReferralAwardId($row->referral_award_id);
					$entity->getReferralAward()->setName($referAward->referral_award_name);
					$entity->getReferralAward()->setDescription($referAward->description);
					$entity->getReferralAward()->setRedirectDescription($referAward->redirect_description);
				}
			}

			$result[$row->mission_goal_id] = $entity;
		}

		return $result;
	}

	public function getMissionReferralAwardGoals($missionId) {
		$result = array();

		$sql = sprintf("
			SELECT
				mg.mission_goal_id, mg.name, mg.points,
				ra.referral_award_id, ra.name AS referral_award_name, ra.description, ra.redirect_description
			FROM %s AS mg
			JOIN %s AS ra ON ra.referral_award_id = mg.referral_award_id
			WHERE mg.mission_goal_type_id = %d
			AND mg.mission_id = ?
			AND mg.is_active = 1
		", self::TABLE_MISSION_GOAL, self::TABLE_REFERRAL_AWARD, MissionGoalType::REFER_A_FRIEND);
		$resultSet = $this->query($sql, array($missionId));

		foreach ($resultSet as $row) {
			$entity = new MissionGoal();
			$entity->setMissionGoalId($row->mission_goal_id);
			$entity->getMissionGoalType()->setMissionGoalTypeId(MissionGoalType::REFER_A_FRIEND);
			$entity->setName($row->name);
			$entity->setPoints($row->points);
			$entity->getReferralAward()->setReferralAwardId($row->referral_award_id);
			$entity->getReferralAward()->setName($row->referral_award_name);
			$entity->getReferralAward()->setDescription($row->description);
			$entity->getReferralAward()->setRedirectDescription($row->redirect_description);

			$result[$row->mission_goal_id] = $entity;
		}

		return $result;
	}

	public function getMissionsGoalsByAffiliateGoalId($goalId, $onlyActive = false, $notExpired = false) {
		$result = array();

		$conditions = "";
		if ($onlyActive == true) {
			$conditions .= " AND m.is_active = 1 ";
		}
		if ($notExpired == true) {
			$conditions .= " AND DATE(m.end_date) > DATE(NOW()) ";
		}

		$sql = sprintf("
			SELECT m.mission_id, mg.mission_goal_id
			FROM %s AS m
			JOIN %s AS mg ON mg.mission_id = m.mission_id
			WHERE mg.affiliate_goal_id = ?
			%s
		", self::TABLE_MISSION, self::TABLE_MISSION_GOAL, $conditions);

		$resultSet = $this->query($sql, array($goalId));
		foreach ($resultSet as $row) {
			if (!array_key_exists($row->mission_id, $result)) {
				$result[$row->mission_id] = array();
			}

			$result[$row->mission_id][] = $row->mission_goal_id;
		}

		return $result;
	}

    public function getRedirectMessage($goalId){
        $result = null;

        $sql = sprintf("
			SELECT
				redirect_description
			FROM %s
			WHERE affiliate_goal_id = ?
			LIMIT 1
		", self::TABLE_AFFILIATE_GOAL);

        $resultSet = $this->query($sql, array($goalId));

        $result = $resultSet[0]->redirect_description;

        return $result;
    }

    public function getRedirectTime($goalId){
        $result = null;

        $sql = sprintf("
			SELECT
				redirect_time
			FROM %s
			WHERE affiliate_goal_id = ?
			LIMIT 1
		", self::TABLE_AFFILIATE_GOAL);

        $resultSet = $this->query($sql, array($goalId));

        $result = $resultSet[0]->redirect_time;

        return $result;
    }


	public function addMission(Mission $mission, $goals = array()) {
		return $this->saveMission($mission, $goals, true);
	}

	public function updateMission(Mission $mission, $goals = array()) {
		return $this->saveMission($mission, $goals, false);
	}

	public function deleteMissionGoal($missionGoalId) {
		return $this->execute(sprintf("DELETE FROM %s WHERE mission_goal_id = ?", self::TABLE_MISSION_GOAL), array($missionGoalId));
	}

	public function setMissionGoalInactive($missionGoalId) {
		return $this->execute(sprintf("UPDATE %s SET is_active = 0 WHERE mission_goal_id = ?", self::TABLE_MISSION_GOAL), array($missionGoalId));
	}


	public function activateMission($missionId) {
		return $this->toggleActivation($missionId, 1);
	}

	public function deactivateMission($missionId) {
		return $this->toggleActivation($missionId, 0);
	}

	private function saveMission(Mission $mission, $goals = array(), $insert = true) {
		$result = 0;

		try {
			$this->beginTransaction();

			$missionId = $mission->getMissionId();
			$data = $mission->toArray();
			unset($data["mission_id"]);


			// Insert Or Update Mission
			if($insert == true) {
				$this->insert(self::TABLE_MISSION, $data);
				$missionId = $this->getLastInsertId();

				$result = $missionId;
			}
			else {
				$result = $this->update(self::TABLE_MISSION, $data, array("mission_id" => $missionId));
			}


			// Insert Or Update New Goals
			foreach ($goals as $missionGoal) {
				$data = $missionGoal->toArray();
				$data["mission_id"] = $missionId;
				$missionGoalId = $data["mission_goal_id"];
				unset($data["mission_goal_id"]);


				if ($data["mission_goal_type_id"] == MissionGoalType::AFFILIATE) {
					unset($data["referral_award_id"]);
				}
				else if ($data["mission_goal_type_id"] == MissionGoalType::REFER_A_FRIEND) {
					unset($data["affiliate_goal_id"]);
				}
				else if ($data["mission_goal_type_id"] == MissionGoalType::ADVERTISEMENT) {
					unset($data["referral_award_id"]);
					unset($data["affiliate_goal_id"]);
				}

				if (empty($missionGoalId)) {
					$this->insert(self::TABLE_MISSION_GOAL, $data);
				}
				else {
					$this->update(self::TABLE_MISSION_GOAL, $data, array("mission_goal_id" => $missionGoalId));
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

	private function toggleActivation($missionId, $value) {
		$result = 0;

		$sql = sprintf("UPDATE %s SET is_active = $value WHERE mission_id = ?", self::TABLE_MISSION);
		$result = $this->execute($sql, array($missionId));

		return $result;
	}
}
