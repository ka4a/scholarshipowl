<?php

/**
 * IMissionAccountService
 *
 * @package     ScholarshipOwl\Data\Service\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	22. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Mission;

use ScholarshipOwl\Data\Entity\Mission\Mission;


interface IMissionAccountService {
	public function saveMissionsGoals($missionsGoals, $accountId);
	public function saveAffiliateGoal($affiliateGoalId, $accountId);
	public function saveReferAFriendGoal($referralAwardId, $accountId);
    public function completeReferAFriendGoals($accountId);
	
	public function searchMissionAccount($params = array(), $limit = "");
	
	public function getMissionAccounts($accountId);
	public function getMissionGoalAccounts($accountId);

    public function getMissionAccountStatus($missionId, $accountId);
    public function isNotified($missionId, $accountId);

    public function getMissionAccountGoalStatusesByMissionId($missionId, $accountId);
    public function getMissionAccountGoalStartedByMissionId($missionId, $accountId);
    
    public function getLastStartedMissionByAccountId($accountId);
    public function getLastStartedMissionByAccountIds($accountIds);
    public function getNumberOfCompletedGoalsByAccountId($accountId);
    public function getNumberOfCompletedGoalsByAccountIds($accountIds);
    public function getNumberOfStartedGoals($missionGoalsId);
    
    public function completeMissions($accountId);
}
