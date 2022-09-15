<?php

namespace App\Http\Controllers\Index;

use App\Events\Account\MissionUpdatedEvent;
use ScholarshipOwl\Data\Service\Marketing\AffiliateGoalMappingService;
use ScholarshipOwl\Data\Service\Marketing\AffiliateService;
use ScholarshipOwl\Data\Service\Marketing\RedirectRulesService;
use ScholarshipOwl\Data\Service\Mission\MissionService;
use ScholarshipOwl\Data\Service\Mission\MissionAccountService;
use ScholarshipOwl\Http\JsonModel;


/**
 * Affiliate Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class AffiliateController extends BaseController {

	/**
	 * Affiliate Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function affiliateAction($apiKey, $accountId, $goalId) {
		try {
			$service = new AffiliateService();


			// Assert API
			$affiliate = $service->getAffiliateByApiKey($apiKey, true);
			if (!isset($affiliate)) {
				return $this->errorResponse("Wrong api key");
			}
			else {
				$goals = $affiliate->getAffiliateGoals();
				$goalsIds = array_keys($goals);


				// Assert Goal ID
				if (!in_array($goalId, $goalsIds)) {
					return $this->errorResponse("Wrong goal id");
				}


				// Generate URL
				$url = "/affiliate/{$apiKey}/{$accountId}/{$goalId}";
				$params = \Input::all();

				$homepageRedirect = false;
				if (isset($params['redirect'])) {
					$homepageRedirect = true;
				}

				foreach ($params as $key => $value) {
					$firstChar = substr($key, 0, 1);
					if (in_array($firstChar, array("/", "\\"))) {
						unset($params[$key]);
					}
				}

				if (!empty($params)) {
					$url .= "?" . http_build_query($params);
				}


				// Save Response & Fire Event
				$service->saveResponse($accountId, $goalId, $url, $params);

                $service = new \ScholarshipOwl\Data\Service\Mission\MissionAccountService();
                $service->saveAffiliateGoal($goalId, $accountId);
                $service->completeMissions($accountId);

				if ($homepageRedirect) {
					return \Redirect::to("/");
				} else {
					return $this->okResponse("Success");
				}
			}
		}
		catch (\Exception $exc) {
			\Log::error($exc);
			return $this->errorResponse("Error occured");
		}
	}


	/**
	 * Affiliate Goal Save & Redirect Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function goalAction($goalId, $accountId = null) {
		try {
			$affiliateService = new AffiliateService();
			$missionService = new MissionService();
			$missionAccountService = new MissionAccountService();

			if (empty($accountId)) {
				if (!$this->isLoggedUser()) {
					throw new \RuntimeException("Account id empty");
				}

				$accountId = $this->getLoggedUser()->getAccountId();
			}


			// Get Mission IDs And Goals
			$missionsGoals = $missionService->getMissionsGoalsByAffiliateGoalId($goalId, true);
			$missionAccountService->saveMissionsGoals($missionsGoals, $accountId);

            \Event::dispatch(new MissionUpdatedEvent($accountId));

			// Redirect To Service URL
			$url = $affiliateService->getAffiliateGoalUrlById($goalId);
			$url = str_replace("{account_id}", $accountId, $url);

			return $this->redirect($url);
		}
		catch (\Exception $exc) {
			\Log::error($exc);
			return $this->errorResponse("Error occured");
		}
	}

	/**
	 * Affiliate Goal Redirect To Goal Id Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 */
	public function goalRedirectAction($goal, $accountId = null) {
		try {
			$service = new AffiliateGoalMappingService();
			$redirectRulesService = new RedirectRulesService();

			if (empty($accountId)) {
				if (!$this->isLoggedUser()) {
					throw new \RuntimeException("Account id empty");
				}

				$accountId = $this->getLoggedUser()->getAccountId();
			}

			$goal = $service->getAffiliateGoalMappingByParam($goal);

			if($redirectRulesService->checkUserAgainstRules($goal->getRedirectRulesSetId(), $accountId)){
				return $this->goalAction($goal->getAffiliateGoalId(), $accountId);
			}
			return $this->goalAction($goal->getAffiliateGoalIdSecondary(), $accountId);
		}
		catch (\Exception $exc) {
			\Log::error($exc);
			return $this->errorResponse("Error occurred");
		}
	}


	private function okResponse($message) {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_OK);
		$model->setMessage($message);

		return $model->send();
	}

	private function errorResponse($message) {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_ERROR);
		$model->setMessage($message);

		return $model->send();
	}
}
