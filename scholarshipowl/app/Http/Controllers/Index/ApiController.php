<?php

namespace App\Http\Controllers\Index;

use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Data\Entity\Scholarship\ApplicationStatus;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;
use ScholarshipOwl\Data\Service\Scholarship\ApplicationService;
use ScholarshipOwl\Data\Service\Scholarship\ScholarshipService;
use ScholarshipOwl\Data\Service\Scholarship\StatisticService as ScholarshipStatisticService;
use ScholarshipOwl\Http\JsonModel;


/**
 * Api Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class ApiController extends BaseController
{
	const ERROR_CODE_SYSTEM_ERROR = -1;
	const ERROR_CODE_APPLY_NOT_SELECTED = 1000;
	const ERROR_CODE_APPLY_PAID_MEMBERS_ONLY = 1001;
	const ERROR_CODE_APPLY_NO_CREDIT = 1002;
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * ReferAFriendController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->scholarships = $em->getRepository(Scholarship::class);
    }



	/**
	 * Apply Action - Gets Scholarships (GET)
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function applyAction() {
		if(!$this->isLoggedUser()) {
			$model = $this->getRedirectModel("/", "homepage");
			return $model->send();
		}

		$model = $this->getOkModel("apply");

		try {
			$scholarshipService = new ScholarshipService();

			$scholarships = array();
			$scholarshipIds = $this->scholarships->findEligibleNotAppliedScholarshipsIds($this->getLoggedUser()->getAccountId());
			if (!empty($scholarshipIds)) {
				$scholarships = $scholarshipService->getScholarshipsData($scholarshipIds);
			}

			foreach ($scholarships as $scholarshipId => $scholarship) {
				$scholarships[$scholarshipId]["expiration_date"] = date("d/m/Y", strtotime($scholarship["expiration_date"]));
				$scholarships[$scholarshipId]["amount"] = number_format($scholarship["amount"]);
			}

			$model->setData(array("scholarships" => $scholarships));
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}


	/**
	 * Post Apply Action - Apply For Scholarships (POST)
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postApplyAction() {
		if(!$this->isLoggedUser()) {
			$model = $this->getRedirectModel("/", "homepage");
			return $model->send();
		}

		$model = $this->getOkModel("apply_post");

		try {
			$input = $this->getAllInput();

			$applicationService = new ApplicationService();
			$scholarshipService = new ScholarshipService();

			$account = $this->getLoggedUser();
			$subscription = $this->getLoggedUserSubscription();


			if (!empty($input["scholarships"])) {
				$scholarships = $scholarshipService->getScholarshipsData($input["scholarships"]);

				// Get Applied Scholarships Data
				$free = 0;
				$paid = 0;
				$pending = array();
				$needMoreInfo = array();

				foreach ($scholarships as $scholarshipId => $scholarship) {
					if ($scholarship["is_free"] == "1") {
						$free++;
					}
					else {
						$paid++;
					}

					if (empty($scholarship["essays"])) {
						$pending[] = $scholarshipId;
					}
					else {
						$needMoreInfo[] = $scholarshipId;
					}
				}


				// Credit Assertation
				if ($subscription->isEmpty()) {
					if (!empty($paid)) {
						$model = $this->getErrorModel(self::ERROR_CODE_APPLY_PAID_MEMBERS_ONLY);
					}
				}
				else {
					$credit = $subscription->getCredit();

					if (!$subscription->isScholarshipsUnlimited() && $credit <= 0) {
						$subscriptionService = new SubscriptionService();
						$scholarshipStatisticService = new ScholarshipStatisticService();

						$subscriptionService->expireSubscription($subscription->getSubscriptionId());

						$model = $this->getRedirectModel("apply", "apply");
					}
					else if (!$subscription->isScholarshipsUnlimited() && $paid > $credit) {
						$model = $this->getErrorModel(self::ERROR_CODE_APPLY_NO_CREDIT);
					}
				}


				// Apply For Scholarships
				if ($model->getStatus() != JsonModel::STATUS_ERROR) {
					$subscriptionId = $subscription->getSubscriptionId();

					if (!empty($pending)) {
						$applicationService->applyScholarships($account->getAccountId(), $pending, ApplicationStatus::PENDING, $subscriptionId);
					}

					if (!empty($needMoreInfo)) {
						$applicationService->applyScholarships($account->getAccountId(), $needMoreInfo, ApplicationStatus::NEED_MORE_INFO, $subscriptionId);
						$model = $this->getRedirectModel("essays", "essays");
					}
				}
			}
			else {
				$model = $this->getErrorModel(self::ERROR_CODE_APPLY_NOT_SELECTED);
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}


	/**
	 * Delete Apply Action - Remove Applied Scholarships (DELETE)
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deleteApplyAction() {
		if(!$this->isLoggedUser()) {
			$model = $this->getRedirectModel("/", "homepage");
			return $model->send();
		}

		$model = $this->getOkModel("apply_delete");

		try {
			$input = $this->getAllInput();

			$applicationService = new ApplicationService();
			$scholarshipService = new ScholarshipService();

			$account = $this->getLoggedUser();
			$subscription = $this->getLoggedUserSubscription();


			if (!empty($input["scholarships"])) {
				$scholarships = $scholarshipService->getScholarshipsData($input["scholarships"]);
				$scholarshipIds = array_keys($scholarships);

				if (!empty($scholarshipIds)) {
					$applicationService->undoApplyScholarships($account->getAccountId(), $scholarshipIds, $subscription->getSubscriptionId());
				}
			}
			else {
				$model = $this->getErrorModel(self::ERROR_CODE_APPLY_NOT_SELECTED);
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}


	/**
	 * My Applications Action - Gets Applied Scholarships Data (GET)
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function myApplicationsAction() {
		if(!$this->isLoggedUser()) {
			$model = $this->getRedirectModel("/", "homepage");
			return $model->send();
		}

		$model = $this->getOkModel("my-applications");
		$data = array("scholarships" => array());

		try {
			$applicationService = new ApplicationService();
			$scholarshipService = new ScholarshipService();

			$account = $this->getLoggedUser();
			$applications = $applicationService->getApplications($account->getAccountId());

			if (!empty($applications)) {
				$scholarships = $scholarshipService->getScholarshipsData(array_keys($applications));
				$essayIds = $applicationService->getApplicationsEssaysIds($account->getAccountId());

				foreach ($scholarships as $scholarship) {
					$row = array(
						"scholarship_id" => $scholarship["scholarship_id"],
						"description" => $scholarship["description"],
						"title" => $scholarship["title"],
						"expiration_date" => date("d/m/Y", strtotime($scholarship["expiration_date"])),
						"amount" => number_format($scholarship["amount"]),
						"essay_status" => "",
						"application_status" => "",
                        "essays" => array(),
					);

					$essays = $scholarship["essays"];
					$essaysStarted = false;
					$essaysCompleted = true;

					foreach ($essays as $essayId => $essay) {
						if (!in_array($essayId, $essayIds)) {
							$essaysCompleted = false;
						}
						else {
							$essaysStarted = true;
						}
                        $row["essays"][$essayId] = $essay;
					}

					if (empty($essays)) {
						$row["essay_status"] = "None";
					}
					else {
						if ($essaysCompleted == true) {
							$row["essay_status"] = "Done";
						}
						else {
							if ($essaysStarted == true) {
								$row["essay_status"] = "In progress";
							}
							else {
								$row["essay_status"] = "Not started";
							}
						}
					}

					if ($applications[$scholarship["scholarship_id"]] == ApplicationStatus::PENDING) {
						$row["application_status"] = "Submitted";
					}
					else if ($applications[$scholarship["scholarship_id"]] == ApplicationStatus::NEED_MORE_INFO) {
						if ($essaysCompleted == true) {
							$row["application_status"] = "Complete";
						}
						else {
							$row["application_status"] = "Incomplete";
						}
					}

					$data["scholarships"][$scholarship["scholarship_id"]] = $row;
				}
			}

			$model->setData($data);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}


	private function getOkModel($message) {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_OK);
		$model->setMessage($message);

		return $model;
	}

	private function getErrorModel($code, $message = "") {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_ERROR);
		$model->setMessage(empty($message) ? $this->getErrorMessageByCode($code) : $message);
		$model->setData($code);

		return $model;
	}

	private function getRedirectModel($url, $message = "") {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_REDIRECT);
		$model->setMessage($message);
		$model->setData($url);

		return $model;
	}

	private function getErrorMessageByCode($code) {
		$result = "";

		if ($code == self::ERROR_CODE_SYSTEM_ERROR) {
			$result = "System error";
		}
		else if ($code == self::ERROR_CODE_APPLY_NOT_SELECTED) {
			$result = "No selected scholarships";
		}
		else if ($code == self::ERROR_CODE_APPLY_PAID_MEMBERS_ONLY) {
			$result = "No paid member";
		}
		else if ($code == self::ERROR_CODE_APPLY_NO_CREDIT) {
			$result = "No credit";
		}

		return $result;
	}
}
