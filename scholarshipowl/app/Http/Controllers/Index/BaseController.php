<?php

namespace App\Http\Controllers\Index;

use App\Entity\FeaturePaymentSet;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Jobs\HasOffersPostbackJob;
use App\Services\EligibilityCacheService;
use App\Services\PopupService;
use ScholarshipOwl\Data\Entity\Info\MilitaryAffiliation;
use ScholarshipOwl\Data\Entity\Mission\MissionGoalType;
use ScholarshipOwl\Data\Service\Account\ReferralAwardService;
use ScholarshipOwl\Data\Service\Info\MilitaryAffiliationService;
use ScholarshipOwl\Data\Service\Marketing\AccountHasoffersFlagService;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;
use ScholarshipOwl\Data\Service\Mission\MissionAccountService;
use ScholarshipOwl\Data\Service\Mission\MissionOrderService;
use ScholarshipOwl\Data\Service\Mission\MissionService;
use ScholarshipOwl\Data\Service\Payment\ApplicationService;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;
use ScholarshipOwl\Http\AbstractController;
use ScholarshipOwl\Http\ViewModel;


/**
 * Base Controller for frontend
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class BaseController extends AbstractController {
	// Maximum Number Of Packages
	const MAX_NUMBER_OF_PACKAGES = 4;

	// GET Param Names
	const GET_PARAM_PAYMENT_SHOW_POPUP = "upgrade";

	// Session Variable Names
	const SESSION_TEST_GROUPS = "test_groups";
	const SESSION_SELECTED_SCHOLARSHIPS = "scholarships_selected";
    const SESSION_REGISTER_PREFIX = "REGISTER";


	private $user;
	private $subscription;
	private $applicationsCount;
	private $submittedApplicationsCount;
	private $eligibilityCount;
	private $isMobile;

	// @TODO Handle All This ID's Properly
	protected $tracking = array(
			"10012" => array(
				//"register2" => "google/google_register",
					"payment" => "google/google_payment", // more-services is real url
					"apply" => "google/google_apply_paid", // paid user apply (select)
			),
			"10013" => array(
				//"register2" => "google/google_register",
					"payment" => "google/google_payment", // more-services is real url
					"apply" => "google/google_apply_paid", // paid user apply (select)
			),
			"10094" => array(
					"payment" => "facebook-pixel" // more-services is real url
			),
	);

	public function __construct() {
		$this->user = null;
		$this->subscription = null;
		$this->applicationsCount = null;
		$this->eligibilityCount = null;
		$this->isMobile = null;
	}


	/**
	 * Handles Exception For Front
	 *
	 * @access protected
	 * @param \Exception $exc
	 * @return void
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function handleException($exc) {
		handle_exception($exc);
	}


	/**
	 * Get Common User View Model
	 *
	 * @access protected
	 * @param string $file
	 * @param array $data
	 * @return \ScholarshipOwl\Http\ViewModel
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getCommonUserViewModel($file, $data = array()) {
        /** @var \App\Entity\Account $account */
        $account = \Auth::user();

        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app()->get(EligibilityCacheService::class);

        $result = new ViewModel($file);
        $result->user = $this->getLoggedUser();
        $result->tracking_params = base64_encode(serialize($this->getTrackingParams()));

        /**
         * @var Package[] $packages
         */
        $packages = FeaturePaymentSet::newPackages();
        $packageService = new PackageService();

        foreach ($packages as &$package) {
            $packageService->packagePlaceholdersProcessor($package);
        }

        $result->packages = $packages;
        $result->popupPackages = $this->getPopupPackages();
        $result->allVisiblePackages = $result->packages + $result->popupPackages;

        $result->isMobile = $this->isMobile();
        $result->paypal = \Config::get("scholarshipowl.payment.paypal");
        $result->social = false;
        $result->offerId = $this->getHasOffersId();
        $result->eligibility_count = $account ? $elbCacheService->getAccountEligibleCount($account->getAccountId()) : 0;
        $result->eligibility_amount = $account ? $elbCacheService->getAccountEligibleAmount($account->getAccountId()) : 0;

        if ($this->isLoggedUser()) {
//			$subscription = $this->getLoggedUserSubscription();
//			$result->is_paid = $subscription->isPaidAcquiredType();

            $accountHasoffersFlagService = new AccountHasoffersFlagService();

            $accountHasoffersFlag = $accountHasoffersFlagService->getFlagForAccount($this->getLoggedUser()->getAccountId());

            if($accountHasoffersFlag){
                if(!$accountHasoffersFlag->isSent()) {
                    $result->showHasoffersIframe = true;
                    $accountHasoffersFlagService->setSent($this->getLoggedUser()->getAccountId());
                }
            }
        }

        if (\Input::has(self::GET_PARAM_PAYMENT_SHOW_POPUP) && \Input::get(self::GET_PARAM_PAYMENT_SHOW_POPUP) == "true"){
            $result->payment_show_popup = true;
        }

        if($result->user) {
            $service = new MarketingSystemService();
            $result->marketingSystemAccount = $service->getMarketingSystemAccount($result->user->getAccountId());
        }

        /** @var PopupService $popupService */
        $popupService = app(PopupService::class);

        $result->popups = $popupService->getPopupsByPage(\Request::path(), $account? $account->getAccountId() : null);

        foreach ($data as $key => $value) {
            $result->$key = $value;
        }

        return $result;
    }


	/**
	 * Is Mobile Device
	 *
	 * @access protected
	 * @return bool
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function isMobile() {
		if (!isset($this->isMobile)) {
            $this->isMobile = is_mobile();
		}

		return $this->isMobile;
	}

	protected function getLoggedUserSubscription() {
		if(!isset($this->subscription)) {
			$user = $this->getLoggedUser();

			if(isset($user)) {
				try {
					$service = new SubscriptionService();
					$this->subscription = $service->getTopPrioritySubscription($user->getAccountId());
					$this->subscription->setCredit($service->getTotalCredit($user->getAccountId()));
					$this->subscription->setScholarshipsCount($service->getTotalScholarships($user->getAccountId()));

					if($subscription = $service->getUnlimitedUserSubscription($user->getAccountId())){
						$this->subscription->setScholarshipsUnlimited(true);
					}
				}
				catch(\Exception $exc) {
					$this->handleException($exc);
				}
			}
		}

		return $this->subscription;
	}

	protected function getLoggedUserApplicationsCount() {
		if(!isset($this->applicationsCount)) {
			$user = $this->getLoggedUser();

			if(isset($user)) {
				try {
					$service = new ApplicationService();

					$counts = $service->getApplicationsCount($user->getAccountId());
					if(array_key_exists($user->getAccountId(), $counts)) {
						$this->applicationsCount = $counts[$user->getAccountId()];
					}
					else {
						$this->applicationsCount = 0;
					}
				}
				catch(\Exception $exc) {
					$this->handleException($exc);
				}
			}
		}

		return $this->applicationsCount;
	}


	protected function getPackages($limit = 4) {
		$packageService = new PackageService();

		if($this->isMobile()) {
			return $packageService->getPackagesForMobilePayment($limit);
		}

		return $packageService->getPackagesForPayment($limit);
	}


	protected function getPopupPackages() {
		$packageService = new PackageService();

		return $packageService->getPackagesForPopup();
	}


	protected function getMissionsPackages(){
		$missionService = new MissionService();
		return $missionService->getMissionsPackages();
	}


	protected function getLoggedUserSubmittedApplicationsCount() {
		if(!isset($this->submittedApplicationsCount)) {
			$user = $this->getLoggedUser();

			if(isset($user)) {
				try {
					$service = new ApplicationService();

					$counts = $service->getSubmittedApplicationsCount($user->getAccountId());
					if(array_key_exists($user->getAccountId(), $counts)) {
						$this->submittedApplicationsCount = $counts[$user->getAccountId()];
					}
					else {
						$this->submittedApplicationsCount = 0;
					}
				}
				catch(\Exception $exc) {
					$this->handleException($exc);
				}
			}
		}

		return $this->submittedApplicationsCount;
	}

	protected function getTrackingParams() {
		$params = array();

        $params = \Input::get();
        foreach($params as $key => $value) {
            // Subdomain ???
            $firstChar = substr($key, 0, 1);
            if(in_array($firstChar, array("/", "\\"))) {
                unset($params[$key]);
            }
        }

		return $params;
	}

	protected function getTracking($url) {
		$result = "";

		$ref = $this->getQueryParam("ref", "");
		if(array_key_exists($ref, $this->tracking)) {
			if(array_key_exists($url, $this->tracking[$ref])) {
				$result = $this->tracking[$ref][$url];
			}
		}

		return $result;
	}

	protected function getHasOffersId() {
		$offerId = \Input::get("offer_id") ? \Input::get("offer_id") : "";

		if(!$offerId) {
			if($this->getLoggedUser()) {
				$service = new MarketingSystemService();
				$marketingSystemAccount = $service->getMarketingSystemAccount($this->getLoggedUser()->getAccountId());
				$offerId = $marketingSystemAccount->getHasOffersOfferId();
			}
		}

		return $offerId;
	}

	public function registerHasOffers($url)
    {
        HasOffersPostbackJob::dispatch(\Auth::user(), $url);
	}

	public function __call($method, $args) {
		if(count($args) == 1){
			return $this->$method($args[0]);
		}else{
			return $this->$method($args[0], $args[1]);
		}


		throw new \Exception();
	}

	protected function getLoggedUserMilitaryAffiliation() {
		$militaryAffiliation = new MilitaryAffiliation();

		$user = $this->getLoggedUser();

		if(isset($user)) {
			try {
				$service = new MilitaryAffiliationService();
                $userMilitary = $user->getProfile()->getMilitaryAffiliation();
                $militaryAffiliationId = is_null($userMilitary) ? null : $userMilitary->getId();
				$militaryAffiliation = $service->getMilitaryAffiliation($militaryAffiliationId);
			}
			catch(\Exception $exc) {
				$this->handleException($exc);
			}
		}

		return $militaryAffiliation;
	}

    /**
     * Gets data from session during registration
     *
     * @access private
     * @return array
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    protected function getRegistrationData() {
        $result = array();
        $session = $this->getAllSession();

        if(array_key_exists(self::SESSION_REGISTER_PREFIX, $session)) {
            $result = $session[self::SESSION_REGISTER_PREFIX];
        }

        return $result;
    }


    /**
     * @param array $default
     */
    protected function setRegistrationData(array $default = [])
    {
        $input = $this->getAllInput() + $default;

        $input["agree_call"] = (empty($input["agree_call"]))?0:1;

        $fields = array(
            "birthday_month", "birthday_day", "birthday_year", "gender", "school_level_id", "is_subscribed",
            "first_name", "last_name", "email", "phone", 'country_code', 'study_country',
            "ethnicity_id", "citizenship_id", "enrollment_month", "enrollment_year", "gpa", "degree_id", "degree_type_id", "career_goal_id", "study_online", "graduation_month", "graduation_year",
            "address", "address2", "city", "state_id", "state_name", "zip", "university", "highschool", "enrolled", "military_affiliation_id", "profile_type", "agree_call"
        );

        foreach($fields as $field) {
            if(array_key_exists($field, $input) && isset($input[$field])) {
                $key = sprintf("%s.%s", self::SESSION_REGISTER_PREFIX, $field);
                $value = $input[$field];

                if($field == "military_affiliation_id" && $value == "-1"){
                    $value = 0;
                }

                $this->setSession($key, $value);
            }
        }
    }
}
