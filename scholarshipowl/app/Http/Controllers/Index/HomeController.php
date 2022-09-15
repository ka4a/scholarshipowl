<?php

namespace App\Http\Controllers\Index;

use App\Entity\Account;
use App\Entity\Banner;
use App\Entity\Domain;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\EntityRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\SpecialOfferPage;
use App\Http\Controllers\Rest\AuthRestController;
use App\Http\Middleware\TrackingParamsMiddleware;
use App\Services\Account\ForgotPasswordService;
use App\Services\Marketing\CoregService;
use App\Services\PubSub\TransactionalEmailService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\Entity\Account\AccountStatus;
use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


/**
 * Home Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class HomeController extends BaseController {

    /**
     * @var ForgotPasswordService
     */
    protected $forgotPasswordService;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $specialOfferPageRepo;

    /**
     * @var EntityRepository
     */
    protected $bannersRepo;

    /**
     * @var CoregService
     */
    protected $coregService;

    /**
     * HomeController constructor.
     *
     * @param ForgotPasswordService $forgotPasswordService
     * @param EntityManager         $em
     * @param CoregService          $cs
     */
    public function __construct(ForgotPasswordService $forgotPasswordService, EntityManager $em, CoregService $cs)
    {
        parent::__construct();

        $this->em = $em;
        $this->forgotPasswordService = $forgotPasswordService;
        $this->specialOfferPageRepo = $em->getRepository(SpecialOfferPage::class);
        $this->bannersRepo = $em->getRepository(Banner::class);
        $this->coregService = $cs;
    }

    /**
	 * Homepage Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction(Request $request) {
        if (\Auth::user()) {
            return redirect(route('my-account'));
        }

		try {
			$file = "homepage";
			$data = array(
				"options" => array(
					"birthday_months" => Profile::getMonthsArray(array("" => "MM")),
					"birthday_days" => Profile::getDaysArray(array("" => "DD")),
					"birthday_years" => Profile::getYearsArray(array("" => "YYYY")),
					"gender" => array("" => "Gender") + Profile::getGenders(),
					"school_levels" => InfoServiceFactory::getArrayData("SchoolLevel", array("" => "Current School Level")),
					"degrees" => InfoServiceFactory::getArrayData("Degree", array("" => "Field of Study"))
				),
				"social" => false,
                "session" => $this->getRegistrationData()
			);

            $request->session()->put(AuthRestController::SESSION_LOGIN_REDIRECT, route('scholarships'));
			$model = $this->getCommonUserViewModel($file, $data);
			return $model->send();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}
	}


	/**
	 * Static Page Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function pageAction(Request $request)
    {
        $page = ltrim($request->path(), '/');
		$downloadPages = array("logos", "press/release", "finding-a-better-way-to-pay-for-college");

		if ($page == "howtoapply") {
			$page = "how-to-apply";
		}
		else if ($page == "whoweare") {
			$page = "who-we-are";
		}
		else if ($page == "whatwedo" || $page == "help") {
			$page = "what-we-do";
		}
		else if (in_array($page, $downloadPages)) {
			if ($page == "logos") {
				return \Response::download(public_path() . "/Logo_hires_.zip", "/ScreenGrabs.zip", "ScholarshipOwl_Logos.zip");
			}
			else if ($page == "press/release") {
				return \Response::download(public_path() . "/ScholarshipOwllaunchrelease20150601draftV.3.pdf", "ScholarshipOwl_PressReleases.pdf");
			}
			else if ($page == "finding-a-better-way-to-pay-for-college") {
				return \Response::download(public_path() . "/FindingABetterWayToPayForCollege.pdf", "ScholarshipOwl_FindingABetterWayToPayForCollege.pdf");
			}
		}


        $file = "pages/{$page}";
        $data = array(
            "social" => true,
            "coregs" => $this->coregService->getCoregsByPath('register'),
        );

        $request->session()->put(AuthRestController::SESSION_LOGIN_REDIRECT, route('my-account'));
        if ($page == "press") {
            $data["downloads"] = array(
                "ScholarshipOwl_Logos.zip" => "/Logo_hires_.zip",
                "ScreenGrabs.zip" => "/ScreenGrabs.zip",
                "YouDeserveItWinners.pdf" => "/documents/ScholarshipOwl-PR-YouDeserveIt-1stWinner.pdf",
                "ScholarshipOwl_FindingABetterWayToPayForCollege.pdf" => "/FindingABetterWayToPayForCollege.pdf"
            );
        }

        $model = $this->getCommonUserViewModel($file, $data);
        return $model->send();
	}


	/**
	 * Logout Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function logoutAction() {
        \Auth::logout();

		\Session::forget(self::SESSION_TEST_GROUPS);

		if ($impersonatorAccountId = \Session::pull('impersonatorAccountId')) {
			\Auth::guard('admin')->loginUsingId($impersonatorAccountId);

			return $this->redirect("admin/dashboard");
		}

		return $this->redirect('/')
            ->withCookie(\Cookie::forget(TrackingParamsMiddleware::COOKIE_MARKETING_SYSTEM));
	}

	/**
	 * Post Login Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postLoginAction() {
		$model = new JsonModel();
		try {
			$input = $this->getAllInput();
			$errors = array();

			if(empty($input["email"])) {
				$errors["email"] = "Email address is empty !";
			}
			else if(filter_var($input["email"], FILTER_VALIDATE_EMAIL) === false) {
				$errors["email"] = "Email address is invalid !";
			}

			if(empty($input["password"])) {
				$errors["password"] = "Password is empty !";
			}


			if(empty($errors)) {
				$credentials = array(
					"email" => $input["email"],
					"password" => $input["password"],
					"accountStatus" => AccountStatus::ACTIVE,
                    "domain" => Domain::SCHOLARSHIPOWL,
				);

				$remember = false;
				if(!empty($input["remember"])) {
					$remember = true;
				}

				if (\Auth::attempt($credentials, $remember)) {
					$redirect = "/my-account";

					if (!empty($input["login_redirect"])) {
						$redirect = $input["login_redirect"];
					}

					$model->setStatus(JsonModel::STATUS_REDIRECT);
					$model->setData($redirect);
					if(!\Request::ajax()){
						return \Redirect::to($redirect);
					}
				}
				else {
					$model->setStatus(JsonModel::STATUS_ERROR);
					$model->setData(array("email" => "Wrong email or password !", "password" => "Wrong email or password !"));
				}
			}
			else {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setData($errors);
			}
		}
		catch(\Exception $exc) {
		    $this->logInfo($exc->getMessage());
			$this->handleException($exc);
		}

		return $model->send();
	}

    /**
     * Post Forgot Password Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function postForgotPasswordAction() {
        $model = new JsonModel();

        try {
            $input = $this->getAllInput();
            $errors = array();

            /** @var AccountRepository $accountRepository */
            $accountRepository = \EntityManager::getRepository(Account::class);
            $account = $accountRepository->findByEmail($input['email']);

            if(empty($input["email"])) {
                $errors["email"] = "Email address is empty !";
            } else if(filter_var($input["email"], FILTER_VALIDATE_EMAIL) === false) {
                $errors["email"] = "Email address is invalid !";
            } else if(!$account) {
                $errors["email"] = "Email address is not registered !";
            }

            if(empty($errors)) {

                $forgotPasswordToken = $this->forgotPasswordService->updateToken($account);
                /**
                 * @var TransactionalEmailService $transactionEmailService
                 */
                $transactionEmailService = app(TransactionalEmailService::class);
                $transactionEmailService->sendCommonEmail($account, TransactionalEmailService::FORGOT_PASSWORD, [
                    'url' => route('reset-password', ['token' => $forgotPasswordToken->getToken()])
                ]);

                $model->setStatus(JsonModel::STATUS_OK);
                $model->setMessage("<h5>DEAR STUDENT,</h5>Your password has been successfully reset. Check your mail for details.");
            } else {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setData($errors);
            }
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $model->send();
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
	public function youDeserveItAction(Request $request)
    {
        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em->getRepository(Scholarship::class);

        if ($scholarship = $scholarshipRepository->findYouDeserveItScholarship()) {
            $timezone = new \DateTimeZone($scholarship->getTimezone());
            $expiryDate = new Carbon($scholarship->getExpirationDate()->format('Y-m-d H:i:s.u'), $timezone);
            $startDate = new Carbon($scholarship->getStartDate()->format('Y-m-d H:i:s.u'), $timezone);

            $drawDate = $expiryDate->copy()->addDays(12);

            \Event::dispatch('youdeserveit.render');

            $loginRedirect = route('select', ['reapply' => '1']);
            $request->session()->put(AuthRestController::SESSION_LOGIN_REDIRECT, $loginRedirect);

            return $this->getCommonUserViewModel('register/youDeserveIt', [
                'social' => true,
                'isMobile' => false,
                'expiryDate' => $expiryDate,
                'startDate' => $startDate,
                'drawDate' => $drawDate,
                'login_redirect' => $loginRedirect,
                'coregs' => $this->coregService->getCoregsByPath('register'),
            ])->send();
        }

        return \Redirect::to('');
	}

	public function testUpload(){
		$model = new ViewModel("testUpload");
		$data = array(
			"social" => true,
			"isMobile" => false
		);
		$model->setData($data);
		return $model->send();
	}

    /**
     * @param Request $request
     *
     * @return int
     */
    public function logErrorAction(Request $request)
    {
        \Log::error(
            sprintf("[LOG ERROR ACTION] Headers: \n%s\nError: \n%s", $request->headers, $request->get('error', null))
        );

        return 1;
    }

    /**
     * @param string $path
     *
     * @return mixed
     */
    public function specialOfferPageAction(Request $request, $path)
    {
        /** @var SpecialOfferPage $page */
        abort_if(null === ($page = $this->specialOfferPageRepo->findOneBy(['url' => $path])), 404);

        if (!$this->isMobile()) {
            \Session::put('payment_return', route('scholarships'));
        }

        $loginRedirect = route('special-offer-page', $page->getUrl());
        $request->session()->put(AuthRestController::SESSION_LOGIN_REDIRECT, $loginRedirect);

        return $this->getCommonUserViewModel('landing.special-offer-page', [
            'user' => \Auth::user(),
            'page' => $page,
            'login_redirect' => $loginRedirect,
        ])->send();
    }

    public function jobs()
    {
        return $this->getCommonUserViewModel('pages.jobs')->send();
    }

    public function winners()
    {
        return $this->getCommonUserViewModel('layout-vue')->send();
    }

    /**
     * @return mixed
     */
    public function vueLayout()
    {
        return view('layout-vue');
    }

    public function doubleYourScholarship()
    {
        return view('pages.lp.double-your-scholarship');
    }

}
