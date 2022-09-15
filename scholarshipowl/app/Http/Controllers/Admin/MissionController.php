<?php

namespace App\Http\Controllers\Admin;

use ScholarshipOwl\Data\Entity\Mission\Mission;
use ScholarshipOwl\Data\Entity\Mission\MissionAccount;
use ScholarshipOwl\Data\Entity\Mission\MissionGoal;
use ScholarshipOwl\Data\Entity\Mission\MissionGoalType;
use ScholarshipOwl\Data\Service\Account\ReferralAwardService;
use ScholarshipOwl\Data\Service\Marketing\AffiliateService;
use ScholarshipOwl\Data\Service\Mission\MissionAccountService;
use ScholarshipOwl\Data\Service\Mission\MissionService;
use ScholarshipOwl\Data\Service\Mission\SearchService as MissionSearchService;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


/**
 * Missions Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class MissionController extends BaseController {

	/**
	 * Missions Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/missions/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Missions" => "/admin/missions"
			),
			"title" => "Missions",
			"active" => "missions"
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Missions Search Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function searchAction() {
		$model = new ViewModel("admin/missions/search");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Missions" => "/admin/missions",
				"Search Missions" => "/admin/missions/search"
			),
			"title" => "Search Missions",
			"active" => "missions",
			"missions" => array()
		);

		try {
			$service = new MissionService();
			$data["missions"] = $service->getMissions();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Save Mission Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveAction() {
		$model = new ViewModel("admin/missions/save");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Missions" => "/admin/missions",
			),
			"title" => "Add Mission",
			"active" => "missions",
			"mission" => new Mission(),
			"mission_goals" => array(),
			"options" => array(
				"active" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
				"visible" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
				"packages" => array("" => "--- Select ---"),
				"affiliates" => array(),
				"used_goals" => array(),
				"awards" => array()
			)
		);


		try {
			$missionService = new MissionService();
			$missionAccountService = new MissionAccountService();
			$packageService = new PackageService();
			$affiliateService = new AffiliateService();
			$referralAwardService = new ReferralAwardService();

			$packages = $packageService->getPackages();
			foreach ($packages as $packageId => $package) {
				$data["options"]["packages"][$packageId] = $package->getName();
			}

			$data["options"]["affiliates"] = $affiliateService->getAffiliates();
			$data["options"]["awards"] = $referralAwardService->getReferralAwards(false);


			$id = $this->getQueryParam("id");
			if (empty($id)) {
				$data["title"] = "Add Mission";
				$data["breadcrumb"]["Add Mission"] = "/admin/missions/save";
			}
			else {
				$mission = $missionService->getMission($id, true);
				$savedMissionGoals = $mission->getMissionGoals();

				if (!empty($savedMissionGoals)) {
					$data["options"]["used_goals"] = $missionAccountService->getNumberOfStartedGoals(array_keys($savedMissionGoals));
				}


				foreach ($missionService->getOrderedMissionGoals($id) as $missionGoalId => $missionGoal) {
					if ($missionGoal->getMissionGoalType()->getMissionGoalTypeId() == MissionGoalType::AFFILIATE) {
						$data["mission_goals"][$missionGoal->getAffiliateGoal()->getAffiliateGoalId()] = array(
							"mission_goal_id" => $missionGoalId,
							"name" => $missionGoal->getName(),
							"type" => $missionGoal->getMissionGoalType()->getMissionGoalTypeId(),
							"points" => $missionGoal->getPoints(),
							"parameters" => $missionGoal->getParameters(),
							"active" => $missionGoal->isActive(),
							"ordering" => $missionGoal->getOrdering(),
						);
					}
					else if ($missionGoal->getMissionGoalType()->getMissionGoalTypeId() == MissionGoalType::REFER_A_FRIEND) {
						$data["mission_goals"][$missionGoal->getReferralAward()->getReferralAwardId()] = array(
							"mission_goal_id" => $missionGoalId,
							"name" => $missionGoal->getName(),
							"type" => $missionGoal->getMissionGoalType()->getMissionGoalTypeId(),
							"points" => $missionGoal->getPoints(),
							"parameters" => $missionGoal->getParameters(),
							"active" => $missionGoal->isActive(),
							"ordering" => $missionGoal->getOrdering(),
						);
					}else if ($missionGoal->getMissionGoalType()->getMissionGoalTypeId() == MissionGoalType::ADVERTISEMENT) {
						$data["mission_goals"][$missionGoalId] = array(
							"mission_goal_id" => $missionGoalId,
							"name" => $missionGoal->getName(),
							"type" => $missionGoal->getMissionGoalType()->getMissionGoalTypeId(),
							"points" => $missionGoal->getPoints(),
							"parameters" => $missionGoal->getParameters(),
							"active" => $missionGoal->isActive(),
							"ordering" => $missionGoal->getOrdering(),
						);
					}
				}

				$data["title"] = $mission->getName();
				$data["mission"] = $mission;
				$data["breadcrumb"]["Search Missions"] = "/admin/missions/search";
				$data["breadcrumb"]["Edit Mission"] = "/admin/missions/save?id=$id";
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Post Save Mission Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postSaveAction() {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();
			$errors = array();

			$affiliateService = new AffiliateService();
			$missionService = new MissionService();
			$referralAwardService = new ReferralAwardService();

			$affiliatesGoals = $affiliateService->getAffiliatesGoals();
			$referralAwards = $referralAwardService->getReferralAwards(false);
			$goals = array();


			if (empty($input["name"])) {
				$errors["name"] = "Name is empty !";
			}

			if (empty($input["start_date"])) {
				$errors["start_date"] = "Start date is empty !";
			}

			if (empty($input["end_date"])) {
				$errors["end_date"] = "End date is empty !";
			}

			if (empty($input["package_id"])) {
				$errors["package_id"] = "Package not selected !";
			}

			if (empty($input["is_active"]) && $input["is_active"] != "0") {
				$errors["is_active"] = "Is active is empty !";
			}

			if (empty($input["is_visible"]) && $input["is_visible"] != "0") {
				$errors["is_visible"] = "Is visible is empty !";
			}

			if (empty($input["description"])) {
				$errors["description"] = "Description is empty !";
			}

			$sortingInput = $input["sorting"];
			$sorting = array();
			$sort = 1;
			foreach(explode(",", $sortingInput) as $id){
				$sorting[$id] = $sort++;
			}

			$activeGoalIds = array();
			foreach ($affiliatesGoals as $affiliateGoalId => $affiliateGoal) {
				$missionGoalIdInput = sprintf("affiliate_goal_%d_mission_goal_id", $affiliateGoalId);
				$nameInput = sprintf("affiliate_goal_%d_name", $affiliateGoalId);
				$pointsInput = sprintf("affiliate_goal_%d_points", $affiliateGoalId);
				$activeInput = sprintf("affiliate_goal_%d_active", $affiliateGoalId);

				if (!empty($input[$nameInput])) {
					if (empty($input[$pointsInput])) {
						$errors[$pointsInput] = "Points are empty";
					}

					if ($input[$pointsInput] > 100) {
						$errors[$pointsInput] = "Points must be smaller than 100";
					}

					$missionGoal = new MissionGoal();
					$missionGoal->getAffiliateGoal()->setAffiliateGoalId($affiliateGoalId);
					$missionGoal->setMissionGoalType(new MissionGoalType(MissionGoalType::AFFILIATE));
					$missionGoal->setName($input[$nameInput]);
					$missionGoal->setPoints($input[$pointsInput]);

					if (!empty($input[$missionGoalIdInput])) {
						$missionGoal->setMissionGoalId($input[$missionGoalIdInput]);
						$activeGoalIds[] = $input[$missionGoalIdInput];
						if(array_key_exists($input[$missionGoalIdInput], $sorting)){
							$missionGoal->setOrdering((Int)$sorting[$input[$missionGoalIdInput]]);
						}
					}else if(array_key_exists($affiliateGoalId."_sorting", $sorting)){
						$missionGoal->setOrdering((Int)$sorting[$affiliateGoalId."_sorting"]);
					}

					if (!empty($input[$activeInput])) {
						$missionGoal->setActive(1);
					}
					else {
						$missionGoal->setActive(0);
					}

					$goals[] = $missionGoal;
				}
			}

			foreach ($referralAwards as $referralAwardId => $referralAward) {
				$missionGoalIdInput = sprintf("referral_award_%d_mission_goal_id", $referralAwardId);
				$nameInput = sprintf("referral_award_%d_name", $referralAwardId);
				$pointsInput = sprintf("referral_award_%d_points", $referralAwardId);
				$activeInput = sprintf("referral_award_%d_active", $referralAwardId);


				if (!empty($input[$nameInput])) {
					if (empty($input[$pointsInput])) {
						$errors[$pointsInput] = "Points are empty";
					}

					if ($input[$pointsInput] > 100) {
						$errors[$pointsInput] = "Points must be smaller than 100";
					}

					$missionGoal = new MissionGoal();
					$missionGoal->getReferralAward()->setReferralAwardId($referralAwardId);
					$missionGoal->setMissionGoalType(new MissionGoalType(MissionGoalType::REFER_A_FRIEND));
					$missionGoal->setName($input[$nameInput]);
					$missionGoal->setPoints($input[$pointsInput]);
					$missionGoal->setOrdering(0);

					if (!empty($input[$missionGoalIdInput])) {
						$missionGoal->setMissionGoalId($input[$missionGoalIdInput]);
						$activeGoalIds[] = $input[$missionGoalIdInput];
						if(array_key_exists($input[$missionGoalIdInput], $sorting)){
							$missionGoal->setOrdering((Int)$sorting[$input[$missionGoalIdInput]]);
						}
					}else if(array_key_exists($referralAwardId."_sorting", $sorting)){
						$missionGoal->setOrdering((Int)$sorting[$referralAwardId."_sorting"]);
					}

					if (!empty($input[$activeInput])) {
						$missionGoal->setActive(1);
					}
					else {
						$missionGoal->setActive(0);
					}

					$goals[] = $missionGoal;
				}
			}

			foreach($input as $key => $value){
				if(strpos($key, "ad_name_") !== false){
					$adId = str_replace("ad_name_", "", $key);
					$missionGoalIdInput = sprintf("ad_mission_goal_id_%s", $adId);
					$nameInput = sprintf("ad_name_%s", $adId);
					$parametersInput = sprintf("ad_parameters_%s", $adId);
					$activeInput = sprintf("ad_active_%s", $adId);

					$missionGoal = new MissionGoal();
					$missionGoal->setMissionGoalType(new MissionGoalType(MissionGoalType::ADVERTISEMENT));
					$missionGoal->setName($input[$nameInput]);
					$missionGoal->setParameters($input[$parametersInput]);
					$missionGoal->setOrdering(0);

					if(array_key_exists($adId, $sorting)){
						$missionGoal->setOrdering((Int)$sorting[$adId]);
					}else if(!empty($input[$missionGoalIdInput]) && array_key_exists($input[$missionGoalIdInput], $sorting)){
						$missionGoal->setOrdering((Int)$sorting[$input[$missionGoalIdInput]]);
					}

					if (!empty($input[$missionGoalIdInput])) {
						$missionGoal->setMissionGoalId($input[$missionGoalIdInput]);
						$activeGoalIds[] = $input[$missionGoalIdInput];
					}

					if (!empty($input[$activeInput])) {
						$missionGoal->setActive(1);
					}
					else {
						$missionGoal->setActive(0);
					}

					$goals[] = $missionGoal;
				}
			}

			if(!empty($input["mission_id"])){
				foreach($missionService->getMissionAffiliateGoals($input["mission_id"]) as $missionAffiliateGoalId => $missionAffiliateGoal){
					if(!in_array($missionAffiliateGoalId, $activeGoalIds)){
						$missionService->setMissionGoalInactive($missionAffiliateGoalId);
					}
				}
			}


			if (empty($errors)) {
				$service = new MissionService();

				$mission = new Mission();

				$mission->populate($input);


				if (empty($input["mission_id"])) {
					$missionId = $service->addMission($mission, $goals);

					$model->setStatus(JsonModel::STATUS_REDIRECT);
					$model->setMessage("Mission saved !");
					$model->setData("/admin/missions/search");
				}
				else {
					$service->updateMission($mission, $goals);

					$model->setStatus(JsonModel::STATUS_OK);
					$model->setMessage("Mission saved !");
				}
			}
			else {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please fix errors !");
				$model->setData($errors);
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}


	/**
	 * Delete Mission Goal Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deleteMissionGoalAction($missionGoalId) {
		$model = new JsonModel();

		try {
			$service = new MissionService();
			$service->deleteMissionGoal($missionGoalId);

			$model->setStatus(JsonModel::STATUS_OK);
		}
		catch (\Exception $exc) {
			$this->logError($exc);
			$model->setStatus(JsonModel::STATUS_ERROR);
		}

		return $model->send();
	}


	/**
	 * Activate Mission Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function activateAction() {
		try {
			$service = new MissionService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->activateMission($id);
			}
			else {
				throw new \Exception("Mission id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/missions/search");
	}


	/**
	 * Deactivate Mission Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deactivateAction() {
		try {
			$service = new MissionService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->deactivateMission($id);
			}
			else {
				throw new \Exception("Mission id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/missions/search");
	}


	/**
	 * Mission Accomplishments Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function accomplishmentsAction() {
		$model = new ViewModel("admin/missions/accomplishments");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Missions" => "/admin/missions",
				"Accomplishments" => "/admin/missions/accomplishments"
			),
			"title" => "Accomplishment",
			"active" => "missions",
			"count" => 0,
			"data" => array(),
			"options" => array(
				"statuses" => MissionAccount::getMissionAccountStatuses(),
			),
		);

		try {
			$service = new MissionAccountService();

			$searchResult = $service->searchMissionAccount();
			$data["count"] = $searchResult["count"];
			$data["data"] = $searchResult["data"];
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Mission Search Progress Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function progressAction() {
		$model = new ViewModel("admin/missions/progress");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Missions" => "/admin/missions",
				"Search Progress" => "/admin/missions/progress"
			),
			"title" => "Search Progress",
			"active" => "missions",
			"data" => array(),
			"count" => 0,
			"search" => array(
				"mission_id" => array(),
				"mission_status" => "",
				"mission_started_from" => "",
				"mission_started_to" => "",
				"mission_ended_from" => "",
				"mission_ended_to" => "",
				"affiliate_goal_id" => array(),
				"affiliate_goal_status" => "",
				"affiliate_goal_started_from" => "",
				"affiliate_goal_started_to" => "",
				"affiliate_goal_accomplished_from" => "",
				"affiliate_goal_accomplished_to" => "",
				"first_name" => "",
				"last_name" => "",
			),
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/missions/progress",
				"url_params" => array(),
			),
			"options" => array(
				"missions" => array(),
				"missions_statuses" => MissionAccount::getMissionAccountStatuses(),
				"affiliate_goals" => array(),
				"affiliate_goals_statuses" => array("" => "--- Select ---", "pending" => "Pending", "started" => "Started", "accomplished" => "Accomplished"),
			),
		);

		try {
			$affiliateService = new AffiliateService();
			$missionService = new MissionService();
			$missionSearchService = new MissionSearchService();

			$input = $this->getAllInput();
			$display = 50;
			$pagination = $this->getPagination($display);

			unset($input["page"]);
			foreach($input as $key => $value) {
				$data["search"][$key] = $value;
			}

			$searchResult = $missionSearchService->searchMissionAccount($data["search"], $pagination["limit"]);

			$data["data"] = $searchResult["data"];
			$data["count"] = $searchResult["count"];
			$data["pagination"]["page"] = $pagination["page"];
			$data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
			$data["pagination"]["url_params"] = $data["search"];

			$data["options"]["missions"] = $missionService->getMissionsList();
			$data["options"]["affiliate_goals"] = $affiliateService->getAffiliatesList();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}
}
