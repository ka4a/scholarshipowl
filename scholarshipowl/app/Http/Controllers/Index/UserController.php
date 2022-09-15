<?php

namespace App\Http\Controllers\Index;

use App\Entity\AccountFile;
use App\Entity\Citizenship;
use App\Entity\EssayFiles;
use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\Setting;
use App\Entity\Subscription;
use App\Events\Account\ChangeEmailEvent;
use App\Events\Account\ChangePasswordEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Http\Traits\JsonResponses;
use App\Jobs\UpdateSubmissions;
use App\Listeners\ApplyForDYIScholarshipListener;
use App\Payment\RemotePaymentManager;
use App\Services\PaymentManager;
use App\Services\PubSub\TransactionalEmailService;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\Account\ForgotPasswordService;

use Illuminate\Support\Facades\Input;
use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Entity\Info\Country;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionAcquiredType;
use ScholarshipOwl\Data\Service\Account\ProfileService;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Data\Service\Info\MilitaryAffiliationService;
use ScholarshipOwl\Data\Service\Info\UniversityService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


/**
 * User Controller for profile, apply
 *
 * @author Marko Prelic <markomys@gmail.com>
 */
class UserController extends BaseController {

    use AuthorizesRequests;
    use JsonResponses;

    /**
     * @var ForgotPasswordService
     */
    protected $forgotPassword;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RemotePaymentManager
     */
    protected $remotePaymentManager;

    /**
     * @var PaymentManager
     */
    protected $pm;

    /**
     * @var TransactionalEmailService
     */
    protected $transactionEmailService;

    /**
     * @var \App\Services\Account\AccountService
     */
    protected $as;

    /**
     * UserController constructor.
     *
     * @param ForgotPasswordService                $forgotPassword
     * @param EntityManager                        $em
     * @param RemotePaymentManager                 $remotePaymentManager
     * @param PaymentManager                       $pm
     * @param TransactionalEmailService            $tes
     * @param \App\Services\Account\AccountService $as
     */
    public function __construct(
        ForgotPasswordService $forgotPassword,
        EntityManager $em,
        RemotePaymentManager $remotePaymentManager,
        PaymentManager $pm,
        TransactionalEmailService $tes,
        \App\Services\Account\AccountService $as
    ) {
        parent::__construct();

        $this->forgotPassword = $forgotPassword;
        $this->remotePaymentManager = $remotePaymentManager;
        $this->em = $em;
        $this->pm = $pm;
        $this->transactionEmailService = $tes;
        $this->as = $as;
    }

    /**
     * My Account Action
     *
     * @param Request $request
     *
     * @return mixed
     */
	public function myAccountAction(Request $request)
    {
		try {
            $model = $this->getCommonUserViewModel('layout-vue');
            \Session::forget('error');

            return $model->send();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}
	}

    /**
     * My Account Action (simplified for mobile native app)
     *
     * @param Request $request
     *
     * @return mixed
     */
	public function myAccountMobileAction(Request $request)
    {
		try {
            $model = $this->getCommonUserViewModel('users/layout-vue-optimized', ['isForMobileApp' => true]);
            \Session::forget('error');

            return $model->send();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}
	}


	/**
	 * My Applications Action
	 *
	 * @access public
	 * @return mixed
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function myApplicationsAction() {

        try {
			\Session::flash("payment_return", "my-applications");

            $accountFiles = \EntityManager::getRepository(AccountFile::class)->findBy([
                'account' => $this->getLoggedUser()->getAccountId()
            ]);

            $file = "users/my-applications";
            $model = $this->getCommonUserViewModel($file, [
                'accountFiles' => $accountFiles,
                'assignedfiles' => $this->getAssignedFiles($this->getLoggedUser()->getAccountId()),
            ]);

            return $model->send();
		} catch(\Exception $exc) {
			$this->handleException($exc);
		}
	}

	/**
	 * Apply Action
	 *
	 * @access public
	 * @return mixed
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function applyAction(Request $request)
    {
        if (!($account = \Auth::user()) || !$account instanceof \App\Entity\Account || !$account->isMember()) {
            if (setting("scholarships.redirect_free") != "select" && setting("scholarships.redirect_free") != "default") {
                return \Redirect::to(setting("scholarships.redirect_free"));
            }
        } else if (setting("scholarships.redirect_members") != "select" && setting("scholarships.redirect_members") != "default") {
            return \Redirect::to(setting("scholarships.redirect_members"));
        }

        if (features()->getName() == "PlansPage") {
            return \Redirect::to(route('plans'));
        }

        try {
			// Browser Cache
			header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
			header("Pragma: no-cache");
			header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");

			$request->session()->forget(self::SESSION_SELECTED_SCHOLARSHIPS);

			$this->registerHasOffers("select");

            $subscriptionAcquiredTypeId = $this->getLoggedUserSubscription()->getSubscriptionAcquiredType()->getSubscriptionAcquiredTypeId();
            $data = array(
                "displayAdditionalInfo" => ($subscriptionAcquiredTypeId == null
                    || $subscriptionAcquiredTypeId == SubscriptionAcquiredType::WELCOME) ? 0 : 1
            );

            if($subscriptionAcquiredTypeId == null) {
                if (setting("register.redirect_page") != "select" && !$this->isMobile()) {
                    return \Redirect::to(setting("register.redirect_page"));
                } else if ($this->isMobile() && setting("register.redirect_page_mobile") != "select") {
                    return \Redirect::to(setting("register.redirect_page_mobile"));
                }
            }
            
			#For facebook pixels
			\Session::put('FACEBOOK_ACCOUNT_FULL_REGISTERED', true);

			$model = $this->getCommonUserViewModel("users/apply", $data);
            if (\Input::get("reapply")) {

                /** @var ScholarshipRepository $scholarshipsRepository */
                $scholarshipsRepository = $this->em->getRepository(Scholarship::class);
                $scholarships = $scholarshipsRepository->findAutomaticScholarships(\Auth::user());
                if ($scholarships->isEmpty()) {
                    return \Redirect::to(setting(Setting::SETTING_OFFER_WALL_AFTER_EMPTY_SELECT));
                }

                $model->scholarships = $scholarships;
                $model->pretick = 1;
                $model->reapply = 1;
            } else {
                $model->pretick = setting("scholarships.pretick");
            }

            $model->hideCheckboxes = features()->getContentSet()->isSelectHideCheckboxes() &&
                (strpos(\URL::previous(), 'register3') !== false);

            $response = \Response::make($model->send(), 200);

            // for mobile native apps
            if ($oneTimeToken = \Cache::pull('one-time-token-'.$account->getAccountId())) {
                // IOS can fetch it from headers
                $headers = [
                    'x-one-time-auth-token' => $oneTimeToken
                ];
                $response->withHeaders($headers);

                // android can only fetch it from cookies
                $cookie = cookie('x-one-time-auth-token', $oneTimeToken, 1);
                $response->withCookie($cookie);
            };

            return $response;
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}
	}

	/**
	 * Post Summary Profile Action
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function postAccountAction(Request $request) {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();
            $account = \Auth::user();
            $oldEmail = $account->getEmail();
            $newEmail = $input["email"];

            $messages = [
                'email.email' => 'Email not valid!',
                'email.required' => 'Email not valid!',
                'email.unique' => 'The account already exists',
                'email.not_regex' => 'Email address is invalid!',
                'password.min' => 'Password is too short, enter min 6 characters',
                'password.confirmed' => 'Passwords do not match',
            ];

            $rules = [
                'email' => 'email|required|not_regex:/(.*)application-inbox\.com$/i',
                'password' => 'confirmed|min:6',
            ];

            $validator = \Validator::make($request->all(), $rules, $messages);

            $validator->sometimes('email', 'unique:App\Entity\Account', function ($input) use ($oldEmail){
                return $input->email !== $oldEmail;
            });

            if ($validator->fails()) {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors!");
                $model->setData($validator->getMessageBag()->messages());
            }
            else {
                /**
                 * @var \App\Entity\Account $account
                 */

                $model->setStatus(JsonModel::STATUS_OK);
                $model->setMessage("Account successfully saved!");

                if(!empty($input["password"])){
                    $this->changePasswordAndSendTransEmail($account, $input);
                }

                if($oldEmail !== $newEmail){
                    $this->changeEmailAndSendTransEmail($account, $oldEmail, $newEmail);
                }

				$model->setData(array("apply_url" => $this->getApplyUrl()));
			}
		}
		catch(\Exception $e) {
			$this->handleException($e);
            $model->setMessage("Please fix errors!");
            $model->setStatus(JsonModel::STATUS_ERROR);
		}

		return $model->send();
	}

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function postRecurrenceAction(Request $request)
    {
        $recurringApplication = $request->get('recurring_application', \App\Entity\Profile::RECURRENT_APPLY_DISABLED);

	    /** @var \App\Entity\Account $account */
        $account = \Auth::user();
        $profile = $account->getProfile();
        $profile->setRecurringApplication($recurringApplication);

        $this->em->flush();

        return $this->jsonSuccessResponse(['recurring_application' => $profile->getRecurringApplication()]);
    }

	/**
     * Reset Password Action
     *
     * @access public
     * @return response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function resetPasswordAction(){
        try{
        	$model = $this->getCommonUserViewModel("users/reset-password");
        	$token = Input::get("token", null);

        	if ($token) {

                if (!$this->forgotPassword->isTokenActive($token)){
                    $model->responseType = "danger";
                    $model->message = "Reset token has expired!";
                }
                else{
                    $model->responseType = "success";
                    $model->token = $token;
                }
            }
            else {
                $model->responseType = "danger";
                $model->message ="Reset token must be supplied!";
            }

            return $model->send();
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }

    /**
     * Post Reset Password Action
     *
     * @access public
     * @return response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function postResetPasswordAction(){
        $input = $this->getAllInput();
        $errors = array();
        $model = new JsonModel();

        /** @var AccountRepository $accountRepository */
        $accountRepository = \EntityManager::getRepository(\App\Entity\Account::class);
        $account = $accountRepository->findByEmail($input['email']);

        if(empty($input["token"])){
            $errors["token"] = "Token is empty!";
        }

        if(empty($input["email"])) {
            $errors["email"] = "Email is empty!";
        }
        else if(filter_var($input["email"], FILTER_VALIDATE_EMAIL) === false) {
            $errors["email"] = "Email not valid!";
        }else if(!$account){
            $errors["email"] = "Email address is not registered!";
        }

        if(empty($input["password"])) {
            $errors["password"] = "Password can not be empty!";
        }else if(strlen($input["password"]) < 6) {
            $errors["password"] = "Password too short. Minimum 6 characters!";
        } else {
            if ($input["password"] != $input["retype_password"]) {
                $errors["password"] = "Passwords are not the same!";
            }
        }

        if(empty($errors)) {
            $result = $this->forgotPassword->changePassword($account, $input['token'], $input['password']);

            if ($result) {
                $model->setStatus(JsonModel::STATUS_OK);
                $model->setMessage("Password successfully saved!");
                $model->setData(array("url" => url("/")));
            } else {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $errors["email"] = 'Wrong email or token';
                $model->setData($errors);
            }

        }
        else {
            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setData($errors);
        }

        return $model->send();
    }


    /**
     * Mailbox Action
     *
     * @access public
     * @return response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function mailboxAction() {
        try {
            \Session::flash("payment_return", "mailbox");
            $file = "layout-vue";
            $data = array(
                "folder" => Input::get("folder", "inbox"),
            );

            $model = $this->getCommonUserViewModel($file, $data);
            return $model->send();
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }

    /**
     * @throws \Exception
     */
	// Sends Mail Notification After Profile Update
	private function sendAccountUpdateMail() {
        $this->transactionEmailService->sendCommonEmail($this->getLoggedUser(), TransactionalEmailService::ACCOUNT_UPDATE, [
            "url" => url("")."/my-account",
        ]);
	}


    // Only SELECT
    private function getApplyUrl() {
    	return "select";
    }

    /**
     * @param         $id
     *
     * @return string
     */
    public function cancelMembership($id)
    {
        /** @var Subscription $subscription */
        $subscription = $this->em->getRepository(Subscription::class)->findById($id);

        $this->authorize('cancel', $subscription);

        $this->remotePaymentManager->cancelSubscription($subscription);

        return 'true';
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelSubscription($id)
    {
        /** @var Subscription $subscription */
        $subscription = $this->em->find(Subscription::class, $id);

        if (!$subscription) {
            \Log::warning("Attempt to cancel not existing subscription [ $id ]");
            return \Redirect::to(setting(Setting::SETTING_REDIRECT_AFTER_SUBSCRIPTION_CANCEL));
        }

        $this->authorize('cancel', $subscription);
        $this->pm->cancelSubscription($subscription);

        return \Redirect::to(setting(Setting::SETTING_REDIRECT_AFTER_SUBSCRIPTION_CANCEL));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function plansPageAction()
    {
        try {
            return view('layout-vue');
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }
    /**
     * method that fires update account event after each profile information update
     *
     * @param $accountId
     * return void
     */
    private function fireUpdateAccountEvent(int $accountId)
    {
        \Event::dispatch(new UpdateAccountEvent($accountId));
    }

    /**
     * @param $accountId
     *
     * @return array
     */
    private function getAssignedFiles($accountId)
    {
        $assignedFiles = [];

        /** @var EssayFiles[] $essayFiles */
        $essayFiles = \EntityManager::getRepository(EssayFiles::class)->createQueryBuilder('ef')
            ->join('ef.file', 'af')
            ->where('af.account = :accountId')
            ->setParameter('accountId', $accountId)
            ->getQuery()->getResult();

        foreach ($essayFiles as $essayFile) {
            $key = sprintf('%s_%s',
                $essayFile->getEssay()->getEssayId(),
                $essayFile->getEssay()->getScholarship()->getScholarshipId()
            );

            $assignedFiles[$key][] = $essayFile->getFile()->getId();
        }

        return $assignedFiles;
    }

    /**
     * @param \App\Entity\Account $account
     * @param                     $input
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function changePasswordAndSendTransEmail(\App\Entity\Account $account, $input)
    {
        $this->as->changePassword($account, $input["password"]);
        $this->em->persist($account);
        $this->em->flush();

        // Send Notification Mail for password change
        $this->transactionEmailService->sendCommonEmail($account,
            TransactionalEmailService::CHANGE_PASSWORD, [
                "password" => $input["password"],
            ]);

        \Event::dispatch(new ChangePasswordEvent($account->getAccountId()));
    }


    /**
     * @param \App\Entity\Account $account
     * @param string              $oldEmail
     * @param string              $newEmail
     *
     * @return \App\Entity\Account
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function changeEmailAndSendTransEmail(\App\Entity\Account $account, string $oldEmail, string $newEmail)
    {
        $this->as->changeEmail($account, $newEmail);
        $this->sendAccountUpdateMail();
        $accountId = $account->getAccountId();
        \Event::dispatch(new ChangeEmailEvent($accountId, $oldEmail));

        return $account;
    }
}
