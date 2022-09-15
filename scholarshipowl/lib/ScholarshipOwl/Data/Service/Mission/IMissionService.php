<?php

/**
 * IMissionService
 *
 * @package     ScholarshipOwl\Data\Service\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	12. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Mission;

use ScholarshipOwl\Data\Entity\Mission\Mission;


interface IMissionService {
	// Getting Missions
	public function getMission($missionId, $goals = true);
	public function getMissions();
	public function getMissionsData($onlyActive = false);
	public function getMissionsList();
	public function getMissionsPackages();
	public function getMissionsPackagesIds($missionIds);
	public function getMissionAffiliateGoals($missionId);
	public function getMissionReferralAwardGoals($missionId);
	public function getMissionsGoalsByAffiliateGoalId($goalId, $onlyActive = false, $notExpired = false);
    public function getRedirectMessage($goalId);
    public function getRedirectTime($goalId);
	
	
	// Saving Missions Functions
	public function addMission(Mission $mission, $goals = array());
	public function updateMission(Mission $mission, $goals = array());
	public function deleteMissionGoal($missionGoalId);
	
	public function activateMission($missionId);
	public function deactivateMission($missionId);
}
