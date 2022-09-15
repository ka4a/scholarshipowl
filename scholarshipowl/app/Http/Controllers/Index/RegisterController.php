<?php

namespace App\Http\Controllers\Index;

use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Domain;
use App\Entity\EligibilityCache;
use App\Entity\Highschool;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\State;
use App\Events\Account\Register3AccountEvent;
use App\Events\Account\Register3VerifyAccountEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Jobs\UpdateSubmissions;
use App\Services\Account\SocialAccountService;
use App\Services\HasOffersService;
use App\Services\Marketing\CoregService;
use App\Services\Marketing\SubmissionService;
use App\Services\OptionsManager;
use App\Services\PasswordService;
use App\Services\PubSub\TransactionalEmailService;
use Carbon\Carbon;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Account\AccountType;
use ScholarshipOwl\Data\Entity\Account\AccountStatus;
use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Entity\Info\MilitaryAffiliation;
use ScholarshipOwl\Data\Service\Account\ProfileService;
use App\Services\Account\Exception\EmailAlreadyRegisteredException;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Data\Service\Info\MilitaryAffiliationService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;
use ScholarshipOwl\Util\Mailer;
use Event;

/**
 * Register Controller for registration process
 *
 * @author Marko Prelic <markomys@gmail.com>
 */
class RegisterController extends BaseController
{
    use ValidatesRequests;

    // @TODO: Check with Ken for logic, instead of having fixed urls
    private $landingPages = array(
        "scholarship-eligibility-test",
        "apply-to-hundreds-of-scholarships",
        "scholarship-eligibility-test-animation",
        "apply-to-hundreds-of-scholarships-animation",
        "get-paid-while-studying",
        "how-to-get-scholarships"
    );

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var HasOffersService
     */
    protected $hasoffers;

    /**
     * @var SocialAccountService
     */
    protected $social;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * @var CoregService
     */
    protected $coregService;

    /**
     * @var SubmissionService
     */
    protected $submissionService;

    /**
     * @var \App\Services\Account\AccountService
     */
    protected $as;

    /**
     * RegisterController constructor.
     *
     * @param EntityManager $em
     * @param HasOffersService $ho
     * @param SocialAccountService $sa
     * @param CoregService $cs
     * @param SubmissionService $ss
     */
    public function __construct(
        EntityManager $em,
        HasOffersService $ho,
        SocialAccountService $sa,
        CoregService $cs,
        SubmissionService $ss,
        \App\Services\Account\AccountService $as
    ) {
        parent::__construct();
        $this->hasoffers = $ho;
        $this->em = $em;
        $this->social = $sa;
        $this->scholarships = $em->getRepository(Scholarship::class);
        $this->coregService = $cs;
        $this->submissionService = $ss;
        $this->as = $as;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function registerAction()
    {
        try {
            return view('layout-vue');
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }


    /**
     * Landing Page Action (Similar functionality as register)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function landingPageAction()
    {
        $page = substr(\Request::path(), 3);
        $model = new ViewModel();
        $hasBirthday = true;

        try {
            if (empty($page) || !in_array($page, $this->landingPages)) {
                return $this->redirect("/");
            }

            $model->setFile("landing/$page");
            $session = $this->getRegistrationData();
            $model->session = $session;
            $model->social = false;
            $model->register_step = 1;
            $model->isMobile = $this->isMobile();

            $model->coregs = $this->coregService->getCoregsByPath("register", \Auth::user());
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $model->send();
    }


    /**
     * Landing Page VM Action (From VM)
     */
    public function landingPageVMAction(Request $request)
    {
        $this->setRegistrationData();
        $password =  PasswordService::generatePassword();

        $account = $this->as->register(
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('email'),
            $request->get('phone'),
            Country::USA,
            $password
        );
        $accountId = $account->getAccountId();
        $profile = $account->getProfile();
        $profile->setStudyCountry1(Country::convert(Country::USA));
        $this->em->persist($profile);
        $this->em->flush();

        \Auth::loginUsingId($accountId);

        $this->hasoffers->saveMarketingSystemAccount($request, $account->getAccountId());

        return $this->redirect("register2");
    }

    /**
     * Register Payment Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan.krkotic@gmail.com>
     */
    public function registerPaymentAction()
    {
        try {
            $file = "register/payment";

            if ($this->isMobile()) {
                return \Redirect::to("upgrade-mobile");
            }

            $this->registerHasOffers("select");

            $data = array(
                "social" => false
            );

            $model = $this->getCommonUserViewModel($file, $data);
            return $model->send();
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }

    /**
     * Register Finish Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function registerFinishAction()
    {
        try {
            $file = "register/register-finish";
            $data = array(
                "session" => $this->getRegistrationData(),
                "social" => false,
            );

            $model = $this->getCommonUserViewModel($file, $data);
            return $model->send();
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }


    /**
     * Post Eligibility Action
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postEligibilityAction(Request $request)
    {
        $model = new JsonModel();

        try {
            $input = $this->getAllInput();
            $errors = array();

            $birthdayMonth = $input["birthday_month"] ?? 0;
            $birthdayDay = $input["birthday_day"] ?? 0;
            $birthdayYear = $input["birthday_year"] ?? 0;

            if (empty($birthdayYear) || ($birthdayYear < 1900) || ($birthdayYear > date("Y"))) {
                $errors["birthday_year"] = "Please enter correct birth year!";
            }

            if (empty($birthdayMonth) || ($birthdayMonth < 1) || ($birthdayMonth > 12)) {
                $errors["birthday_month"] = "Please enter correct birth month!";
            }

            if (empty($birthdayMonth) || ($birthdayDay < 1) || ($birthdayDay > 31)) {
                $errors["birthday_day"] = "Please enter correct birth day!";
            }

			if (!isset($errors['date_of_birth'])) {
			    $age = $birthdayYear ? (int)(date("Y") - $birthdayYear) : 0;

			    if ($age < 14) {
				    $errors["date_of_birth"] = "Must be at least 14 years old!";
                }
                else if ($age == 14) {
                    $month = date("m");
                    $day = date("j");

                    if((int) $birthdayMonth > $month) {
                        $errors["date_of_birth"] = "Must be at least 14 years old!";
                    }
                    else if((int) $birthdayMonth == $month) {
                        if((int) $birthdayDay > $day) {
                            $errors["date_of_birth"] = "Must be at least 14 years old!";
                        }
                    }
                }
			}

            if (empty($input["gender"])) {
                $errors["gender"] = "Please select your gender!";
            }

            if (empty($input["school_level_id"])) {
                $errors["school_level_id"] = "Please select current school level!";
            }

            if (empty($input["degree_id"])) {
                $errors["degree_id"] = "Please select field of study!";
            }

            $this->setRegistrationData();

            if (empty($errors)) {

                if (!$request->ajax()) {
                    return redirect($input["_return"]);
                }

                $model->setStatus(JsonModel::STATUS_REDIRECT);
                $model->setData($input["_return"]);
            } else {

                if (!$request->ajax()) {
                    return back()->withErrors($errors);
                }

                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors!");
                $model->setData($errors);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $model->send();
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function register(Request $request)
    {
        if (\Auth::user()) {
            return ['status' => JsonModel::STATUS_REDIRECT, 'data' => route('register2')];
        }

        $domain = \Domain::get()->getId();
        $emailRule = sprintf('required|email|unique:%s,email,NULL,account_id,domain,%s|not_regex:/(.*)application-inbox\.com$/i', \App\Entity\Account::class,
            $domain);

        $this->validate($request, [
            'agree_terms' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => $emailRule,
            'phone' => ['required', 'max:16', 'regex:/^\+[0-9]{10,20}$/'],
            'country_code' => 'required|size:2|exists:App\Entity\Country,abbreviation',
            'study_country' => 'required_without:want_to_study|array|max:5',
            'study_country.*.id' => 'required_without:want_to_study|exists:App\Entity\Country,id',
        ], [
            'agree_terms.required' => 'You must agree with the terms and conditions before you continue!',
            'first_name.required' => 'Please enter first name!',
            'last_name.required' => 'Please enter last name!',
            'email.required' => 'Please enter email!',
            'email.email' => 'Email address is invalid!',
            'email.not_regex' => 'Email address is invalid!',
            'email.unique' => 'The account already exists',
            'phone.required' => 'Please enter phone!',
            'phone.max' => 'Phone is too long!',
            'phone.regex' => 'Min phone length 10 digits!',
            'country_code.required' => 'Invalid phone number!',
            'country_code.exists' => 'Invalid phone number!',
            'study_country.required_without' => 'Please enter at least one country!',
            'study_country.max' => 'You can only select 5 items!',
            'study_country.*.id.exists' => 'Please enter valid country!',
        ]);

        $citizenship = Citizenship::findByCountryCode($request->get('country_code'));
        $this->setRegistrationData(['citizenship_id' => $citizenship ? $citizenship->getId() : null]);
        $password = PasswordService::generatePassword();

        try {
            $country = Country::findByCountryCode($request->get('country_code'));
        } catch (\Exception $e) {
            $country = null;
        }

        $account = $this->as->register(
            $request->get('first_name'),
            $request->get('last_name'),
            $request->get('email'),
            $request->get('phone'),
            $country ? $country->getId() : Country::USA,
            $password
        );

        if ($request->has('want_to_study')) {
            $profile = $account->getProfile();
            $profile->setStudyCountry1(Country::convert(Country::USA));
            $this->em->persist($profile);
            $this->em->flush();
        }

        $accountId = $account->getAccountId();

        \Session::put('ACCOUNT_REGISTRATION', true);

        \Auth::loginUsingId($accountId);

        $this->social->linkAccounts();
        $this->hasoffers->saveMarketingSystemAccount($request, $accountId);

        //  Add submissions
        if ($request->has("coregs")) {
            $this->submissionService->addSubmissions($request->get("coregs"), $accountId,
                \Request::getClientIp());
        }

        if ($request->get('_widget_redirect')) {
            return redirect($request->get('_return', 'register2'));
        }

        return ['status' => JsonModel::STATUS_REDIRECT, 'data' => $request->get('_return', 'register2')];
    }


    /**
     * Post Register Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function postRegisterAction(Request $request)
    {
        $model = new JsonModel();

        if (\Auth::user()) {
            $model->setStatus(JsonModel::STATUS_REDIRECT);
            $model->setData(route('register', ['step' => 2]));
            return $model->send();
        }

        try {
            $input = $this->getAllInput();

            $messages = [
                'first_name.required' => 'Please enter first name!',
                'last_name.required' => 'Please enter last name!',
                'email.required' => 'Please enter email!',
                'email.email' => 'Email address is invalid!',
                'email.not_regex' => 'Email address is invalid!',
                'phone.regex' => 'Min phone length 10 digits!',
                'phone.required' => 'Please enter phone!',
                'agree_terms.required' => 'You must agree with the terms and conditions before you continue!',
            ];

            $rules = [
                'first_name' => 'required',
                'last_name'  => 'required',
                'email'      => 'email|required|unique:App\Entity\Account,email,NULL,account_id,domain,'. \Domain::get()->getId(). '|not_regex:/(.*)application-inbox\.com$/i' ,
                'phone'      => 'required|regex:/\((\d{3})\)\s*(\d{3})\s*-\s*(\d{4})/',
                'agree_terms' =>'required'
            ];

            $validator = \Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors!");
                $model->setData($validator->getMessageBag()->messages());
            } else {
                $this->setRegistrationData();
                try {
                    $account = $this->as->register(
                        $request->get('first_name'),
                        $request->get('last_name'),
                        $request->get('email'),
                        $request->get('phone'),
                        Country::USA
                    );

                    $profile = $account->getProfile();
                    $profile->setStudyCountry1(Country::convert(Country::USA));
                    $this->em->persist($profile);
                    $this->em->flush();

                    $accountId = $account->getAccountId();
                    \session::put('ACCOUNT_REGISTRATION', true);

                    \Auth::loginUsingId($accountId);

                    //  Add submissions
                    if (isset($input["coregs"])) {
                        $this->submissionService->addSubmissions($input["coregs"],
                            $accountId, $request->getClientIp());
                    }

                    dispatch(new UpdateSubmissions($accountId));

                    $model->setStatus(JsonModel::STATUS_REDIRECT);
                    $model->setData($input["_return"]);

                    $this->social->linkAccounts();
                    $this->hasoffers->saveMarketingSystemAccount($request, $accountId);
                } catch (UniqueConstraintViolationException $exc) {
                    $model->setStatus(JsonModel::STATUS_ERROR);
                    $model->setMessage("Please fix errors!");
                    $model->setData(["email" => "The account already exists"]);
                }
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
        if ($request->get('_widget_redirect')) {
            return redirect($model->getStatus() === JsonModel::STATUS_REDIRECT ? $model->getData() : route('register2'));
        }
        return $model->send();
    }

    /**
     * @return string
     */
    protected function getRedirectUrl()
    {
        if (\Route::getCurrentRoute()->getName() === 'register.post') {
            return route('register');
        }

        return parent::getRedirectUrl();
    }

    protected function prepareAccountData($accountsData) {
        $accountDataToChange = [
            'first_name' => 'firstname',
            'last_name' => 'lastname'
        ];

        foreach ($accountDataToChange as $oldKey => $newKey){
            if( ! array_key_exists( $oldKey, $accountsData ) )
                return $accountsData;

            $keys = array_keys($accountsData);
            $keys[ array_search( $oldKey, $keys ) ] = $newKey;

            $accountsData = array_combine($keys, $accountsData);
        }

        return $accountsData;
    }
}
