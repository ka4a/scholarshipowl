<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Package;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Data\Service\Account\ReferralService;
use ScholarshipOwl\Data\Service\Account\ReferralAwardService;
use ScholarshipOwl\Data\Service\Account\ReferralShareService;
use ScholarshipOwl\Data\Service\Payment\ApplicationService;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Payment\StatisticService as PaymentStatisticService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


/**
 * Refer A Friend Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class ReferAFriendController extends BaseController
{
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
	 * Refer A Friend Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/refer-a-friend/index");

        $data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Refer A Friend" => "/admin/refer-a-friend"
			),
			"title" => "Refer A Friend",
			"active" => "refer_a_friend",
		);

        $model->setData($data);
        return $model->send();
	}


	/**
	 * Refer A Friend Save Award Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveAwardAction() {
		$model = new ViewModel("admin/refer-a-friend/awards-save");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Refer A Friend" => "/admin/refer-a-friend",
				"Awards" => "/admin/refer-a-friend/awards",
			),
			"active" => "refer_a_friend",
			"award" => new \App\Entity\ReferralAward(),
			"options" => array(
				"award_types" => array("" => "--- Select ---") + \App\Entity\ReferralAwardType::getReferralAwardTypes(),
				"active" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
				"packages" => array("" => "--- Select ---"),
			),
		);

		try {
			$service = new ReferralAwardService();
			$packageService = new PackageService();

			$packages = $packageService->getPackages();
			foreach ($packages as $packageId => $package) {
				$data["options"]["packages"][$packageId] = $package->getName();
			}

			$data["options"]["referralChannels"] = \App\Entity\ReferralAwardShare::getReferralChannels();

			$id = $this->getQueryParam("id");
			if(empty($id)) {
				$data["title"] = "Add Award";
				$data["breadcrumb"]["Add Award"] = "/admin/refer-a-friend/awards/save";
			}
			else {
				$award = $service->getReferralAward($id);

				$data["title"] = $award->getName();
				$data["award"] = $award;
				$data["breadcrumb"]["Edit Award"] = "/admin/refer-a-friend/awards/save?id=$id";
				$data["options"]["shareNumber"] = $service->getShareNumber($id);
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Refer A Friend Post Save Award Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postSaveAwardAction() {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();
			$errors = array();

			if (empty($input["name"])) {
				$errors["name"] = "Name is empty !";
			}

			if (empty($input["referral_award_type_id"])) {
				$errors["referral_award_type_id"] = "Award type not selected !";
			}

			if($input["referral_award_type_id"] != \App\Entity\ReferralAwardType::NUMBER_OF_SHARES) {	//	If it's not number of shares award
				if (empty($input["referrals_number"]) && $input["referrals_number"] != "0") {
					$errors["referrals_number"] = "Referrals number is empty !";
				} else {
					if (!is_numeric($input["referrals_number"])) {
						$errors["referrals_number"] = "Referrals number not valid !";
					} else if (!$input["referrals_number"] > 0) {
						$errors["referrals_number"] = "Referrals number must be greater than 0 !";
					}
				}
			}

			if (empty($input["referred_package_id"]) && $input["referred_package_id"] != "0") {
				$errors["referred_package_id"] = "Referred package not selected !";
			}

			if (empty($input["is_active"]) && $input["is_active"] != "0") {
				$errors["is_active"] = "Is active is empty !";
			}

            if (empty($errors)) {
				$service = new ReferralAwardService();
				$award = new \App\Entity\ReferralAward();

                foreach ($input as $key => $value) {
                    if ($key == "referral_award_id" && $value != '') {
                        $award->setReferralAwardId($value);
                    } else if ($key == "referral_award_type_id") {
                        $award->setReferralAwardType((int)$value);
                    } else if ($key == "name") {
                        $award->setName($value);
                    } else if ($key == "description") {
                        $award->setDescription($value);
                    } else if ($key == "redirect_description") {
                        $award->setRedirectDescription($value);
                    } else if ($key == "referrals_number") {
                        $award->setReferralsNumber($value);
                    } else if ($key == "referral_package_id") {
                        $award->setReferralPackage($this->em->find(Package::class, $value));
                    } else if ($key == "referred_package_id") {
                        $award->setReferredPackage($this->em->find(Package::class, $value));
                    } else if ($key == "is_active") {
                        $award->setIsActive($value);
                    }
                }

				if (empty($input["referral_award_id"])) {
                    \EntityManager::persist($award);
                    \EntityManager::flush($award);
                    $referralAwardId = $award->getReferralAwardId();
                    dd($referralAwardId);
					foreach($input as $key => $value){
						if(strpos($key, "_share_number") !== false){
							$service->saveShareNumber($referralAwardId, str_replace("_share_number", "", $key), $value);
						}
					}

					$model->setStatus(JsonModel::STATUS_REDIRECT);
					$model->setMessage("Award saved !");
					$model->setData("/admin/refer-a-friend/awards");
				}
				else {
                    \EntityManager::persist($award);
                    \EntityManager::flush($award);

					foreach($input as $key => $value){
						if(strpos($key, "_share_number") !== false){
							$service->saveShareNumber($input["referral_award_id"], str_replace("_share_number", "", $key), $value);
						}
					}

					$model->setStatus(JsonModel::STATUS_OK);
					$model->setMessage("Award saved !");
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
	 * Refer A Friend Activate Award Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function activateAwardAction() {
		try {
			$service = new ReferralAwardService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->activateReferralAward($id);
			}
			else {
				throw new \Exception("Referral award id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/refer-a-friend/awards");
	}


	/**
	 * Refer A Friend Deactivate Award Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deactivateAwardAction() {
		try {
			$service = new ReferralAwardService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->deactivateReferralAward($id);
			}
			else {
				throw new \Exception("Referral award id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/refer-a-friend/awards");
	}


	/**
	 * Refer A Friend Awards Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function awardsAction() {
		$model = new ViewModel("admin/refer-a-friend/awards");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Refer A Friend" => "/admin/refer-a-friend",
				"Awards" => "/admin/refer-a-friend/awards"
			),
			"title" => "Awards",
			"active" => "refer_a_friend",
			"awards" => array(),
			"packages" => array(),
		);

		try {
			$packageService = new PackageService();

			$data["awards"] = $this->em->getRepository(\App\Entity\ReferralAward::class)->findAll();
			$data["packages"] = $packageService->getPackages();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Refer A Friend Awards History Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function awardsHistoryAction() {
		$model = new ViewModel("admin/refer-a-friend/awards-history");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Refer A Friend" => "/admin/refer-a-friend",
				"Awards" => "/admin/refer-a-friend/awards",
				"Awards History" => "/admin/refer-a-friend/awards/history"
			),
			"title" => "Awards History",
			"active" => "refer_a_friend",
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Refer A Friend Search Referrals Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function searchAction() {
		$model = new ViewModel("admin/refer-a-friend/search");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Refer A Friend" => "/admin/refer-a-friend",
				"Search Referrals" => "/admin/refer-a-friend/search",
			),
			"title" => "Search Referrals",
			"active" => "refer_a_friend",
			"referrals" => array(),
			"count" => 0,
			"applications" => array(),
			"eligibles" => array(),
			"search" => array(
				"referral_first_name" => "",
				"referral_last_name" => "",
				"referral_email" => "",
				"referral_created_date_from" => "",
				"referral_created_date_to" => "",
				"referral_channel" => "",
				"referred_first_name" => "",
				"referred_last_name" => "",
				"referred_email" => "",
				"referred_created_date_from" => "",
				"referred_created_date_to" => "",
			),
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/refer-a-friend/search",
				"url_params" => array()
			),
            "options" => array(
                "referral_channels" => \App\Entity\Referral::getReferralChannels()
            )
		);

		try {
			$service = new ReferralService();
			$applicationService = new ApplicationService();
			$paymentStatisticService = new PaymentStatisticService();

			$display = 20;
			$pagination = $this->getPagination($display);

			$input = $this->getAllInput();
			unset($input["page"]);
			foreach($input as $key => $value) {
				$data["search"][$key] = $value;
			}

			$searchResult = $service->searchReferrals($data["search"], $pagination["limit"]);
			if (!empty($searchResult["data"])) {

				foreach ($searchResult["data"] as $referral) {
					$referralId = $referral->getReferralAccount()->getAccountId();
					$referredId = $referral->getReferredAccount()->getAccountId();

					if (!array_key_exists($referralId, $data["eligibles"])) {
						$data["eligibles"][$referralId] = $this->scholarships->findEligibleNotAppliedScholarshipsIds($referralId);
					}

					if (!array_key_exists($referredId, $data["eligibles"])) {
						$data["eligibles"][$referredId] = $this->scholarships->findEligibleNotAppliedScholarshipsIds($referredId);
					}
				}
			}


			$data["referrals"] = $searchResult["data"];
			$data["count"] = $searchResult["count"];
			$data["pagination"]["page"] = $pagination["page"];
			$data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
			$data["pagination"]["url_params"] = $data["search"];
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}

	/**
	 * Refer A Friend Share Report Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 */
	public function shareReportAction() {
		$model = new ViewModel("admin/refer-a-friend/share-report");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Refer A Friend" => "/admin/refer-a-friend",
				"Share Report" => "/admin/refer-a-friend/share-report"
			),
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/refer-a-friend/share-report",
				"url_params" => array()
			),
			"title" => "Share Report",
			"active" => "refer_a_friend",
			"shares" => array(),
			"options" => array(
				"referral_channels" => \App\Entity\Referral::getReferralChannels()
			)

		);

		try {
			$service = new ReferralShareService();

			$display = 50;
			$pagination = $this->getPagination($display);

			$shareReport = $service->getShareReport($pagination["limit"]);

			$data["shares"] = $shareReport["data"];
			$data["count"] = $shareReport["count"];
			$data["pagination"]["page"] = $pagination["page"];
			$data["pagination"]["pages"] = ceil($shareReport["count"] / $display);
			$data["pagination"]["url_params"] = array();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}
}
