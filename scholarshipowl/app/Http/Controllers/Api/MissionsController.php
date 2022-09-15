<?php
/**
 * Author: Ivan Krkotic (clone@mail2joe.com)
 * Date: 22/6/2015
 */

namespace App\Http\Controllers\Api;

use ScholarshipOwl\Data\Entity\Mission\MissionGoalType;
use ScholarshipOwl\Data\Service\Mission\MissionOrderService;
use ScholarshipOwl\Data\Service\Mission\MissionService;
use ScholarshipOwl\Data\Service\Mission\MissionAccountService;


class MissionsController extends BaseController{
    /**
     * Missions Index Action - Gets Mission Goals and Statuses (GET)
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function indexAction() {
        $model = $this->getOkModel("mission-goals");
        $data = array();

        try {
            $input = $this->getAllInput();
            $accountId = $this->getLoggedUser()->getAccountId();

            if (!empty($input["mission"])) {
                $missionId = $input["mission"];
                $missionService = new MissionService();
                $missionOrderService = new MissionOrderService();
                $missionAccountService = new MissionAccountService();

                $missionGoalsStatuses = $missionAccountService->getMissionAccountGoalStatusesByMissionId($missionId, $accountId);
                $missionGoalsStarted = $missionAccountService->getMissionAccountGoalStartedByMissionId($missionId, $accountId);


                // Affiliate Goals
                $missionGoals = $missionService->getOrderedMissionGoals($missionId, true);

				foreach ($missionGoals as $missionGoalId => $missionGoal) {
					$affiliateGoalId = $missionGoal->getAffiliateGoal()->getAffiliateGoalId();
					$referralAwardId = $missionGoal->getReferralAward()->getReferralAwardId();
					$row = array();
					$row["mission_goal_id"] = $missionGoalId;
					$row["name"] = $missionGoal->getName();
					$row["points"] = $missionGoal->getPoints();
					$row["parameters"] = $missionGoal->getParameters();

					if ($missionGoal->getMissionGoalType()->getMissionGoalTypeId() == MissionGoalType::AFFILIATE) {
						$row["affiliate_goal"] = array(
							"affiliate_goal_id" => $affiliateGoalId,
							"name" => $missionGoal->getAffiliateGoal()->getName(),
							"url" => $missionGoal->getAffiliateGoal()->getUrl(),
							"generated_url" => sprintf("%s/affiliate/goal/%d/%d", url()->current(), $affiliateGoalId, $accountId),
							"description" => $missionGoal->getAffiliateGoal()->getDescription(),
							"logo" => url()->current()."/system/affiliate/".$missionGoal->getAffiliateGoal()->getLogo(),
							"redirect_description" => $missionGoal->getAffiliateGoal()->getRedirectDescription(),
						);
						$row["type"] = MissionGoalType::AFFILIATE;
					}else if($missionGoal->getMissionGoalType()->getMissionGoalTypeId() == MissionGoalType::REFER_A_FRIEND) {
						$row["referral_award"] = array(
							"referral_award_id" => $referralAwardId,
							"name" => $missionGoal->getReferralAward()->getName(),
							"description" => $missionGoal->getReferralAward()->getDescription(),
							"redirect_description" => $missionGoal->getReferralAward()->getRedirectDescription()
						);
						$row["type"] = MissionGoalType::REFER_A_FRIEND;
					}else if($missionGoal->getMissionGoalType()->getMissionGoalTypeId() == MissionGoalType::ADVERTISEMENT){
						$row["type"] = MissionGoalType::ADVERTISEMENT;
						$row["html"] = \View::make("includes.goal-ads.ad")->with(array(
							"adId" => $row["parameters"]
						))->render();
					}

					$row["is_accomplished"] = array_key_exists($missionGoalId, $missionGoalsStatuses) ? $missionGoalsStatuses[$missionGoalId] : "0";
					$row["is_started"] = array_key_exists($missionGoalId, $missionGoalsStarted) ? $missionGoalsStarted[$missionGoalId] : "0";

					$data[] = $row;
				}
            }
            $model->setData($data);
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }

        return $model->send();
    }


    /**
     * Missions History Action - Gets All Mission Activity (GET)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function historyAction() {
    	$model = $this->getOkModel("mission-history");
        $data = array();

        try {
        	$missionService = new MissionService();
        	$missionAccountService = new MissionAccountService();

        	$usedMissionIds = array();
        	$missions = $missionService->getMissionsData(true);
        	$missionAccounts = $missionAccountService->getMissionAccounts($this->getLoggedUser()->getAccountId());

        	foreach ($missionAccounts as $missionAccountId => $missionAccount) {
        		$row = array();
        		$row["mission_id"] = $missionAccount->getMission()->getMissionId();
        		$row["name"] = $missionAccount->getMission()->getName();
        		$row["description"] = $missionAccount->getMission()->getDescription();
        		$row["status"] = ucwords(str_replace("_", " ", $missionAccount->getStatus()));
        		$row["start_date"] = $this->formatDate($missionAccount->getMission()->getStartDate());
        		$row["end_date"] = $this->formatDate($missionAccount->getMission()->getEndDate());
        		$row["reward"] = $missionAccount->getMission()->getRewardMessage();
        		$row["active"] = $missionAccount->getMission()->isActive();

        		$data[] = $row;
        		$usedMissionIds[] = $missionAccount->getMission()->getMissionId();
        	}

        	foreach ($missions as $missionId => $mission) {
        		if (!in_array($missionId, $usedMissionIds)) {
        			$row = array();
        			$row["mission_id"] = $mission->getMissionId();
        			$row["name"] = $mission->getName();
        			$row["description"] = $mission->getDescription();
                    $row["status"] = "Not Started";
        			$row["start_date"] = $this->formatDate($mission->getStartDate());
        			$row["end_date"] = $this->formatDate($mission->getEndDate());
        			$row["reward"] = $mission->getRewardMessage();
                    $row["active"] = $mission->isActive();

        			$data[] = $row;
        		}
        	}

        	$model->setData($data);
    	}
    	catch (\Exception $exc) {
    		$this->handleException($exc);
    		$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
    	}

    	return $model->send();
    }

    /**
     * Missions Status Action - Gets Mission And Goal Statuses (GET)
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function statusAction($missionId) {
        $model = $this->getOkModel("mission-status");
        $data = array();

        if ($missionId == 49) {
            $model->setData(['mission_status' => 'completed']);
            return $model->send();
        }

        try {
            $accountId = $this->getLoggedUser()->getAccountId();
            $missionService = new MissionService();
            $missionAccountService = new MissionAccountService();

            $data["mission_status"] = $missionAccountService->getMissionAccountStatus($missionId, $accountId);

            $missionGoalsStatuses = $missionAccountService->getMissionAccountGoalStatusesByMissionId($missionId, $accountId);
            foreach ($missionGoalsStatuses as $missionGoalId => $missionGoalsStatus) {
                $row["is_accomplished"] = $missionGoalsStatus;

                $data["goals"][$missionGoalId] = $row;
            }

            $model->setData($data);
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }

        return $model->send();
    }

    /**
     * Missions Notify Action - Set account notified for a mission (GET)
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function notifyAction() {
        $model = $this->getOkModel("mission-notify");
        $data = array();

        try {
            $accountId = $this->getLoggedUser()->getAccountId();
            $missionAccountService = new MissionAccountService();

            $input = $this->getAllInput();

            if(!empty($input["missionId"])){
                $missionAccountService->setNotified($input["missionId"], $accountId);
            }
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }

        return $model->send();
    }


    /**
     * Missions Start By Refer A Friend (POST)
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function startReferAFriendAction($referralAwardId) {
    	$model = $this->getOkModel("refer-a-friend");

    	try {
    		$accountId = $this->getLoggedUser()->getAccountId();

    		$missionAccountService = new MissionAccountService();
    		$missionAccountService->saveReferAFriendGoal($referralAwardId, $accountId);
    	}
    	catch (\Exception $exc) {
    		$this->handleException($exc);
    		$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
    	}

    	return $model->send();
    }


    private function formatDate($date, $format = "m/d/Y") {
    	$result = "";

    	if (!empty($date) && $date != "0000-00-00 00:00:00") {
    		$result = date($format, strtotime($date));

    	}

    	return $result;
    }
}
