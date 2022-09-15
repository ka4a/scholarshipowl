<?php namespace App\Http\Controllers\Admin;

use App\Entity\AccountFile;
use App\Entity\AccountOnBoardingCall;
use App\Entity\Domain;
use App\Entity\Exception\EntityNotFound;
use App\Entity\PaymentMethod;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\EntityRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\Resource;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\Transaction;
use App\Events\Account\ChangeEmailEvent;
use App\Events\Account\ChangePasswordEvent;
use App\Events\Account\CreateAccountEvent;
use App\Events\Account\DeleteAccountEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Services\Account\AccountApplicationService;
use App\Services\Account\AccountLoginTokenService;
use App\Services\Marketing\SubmissionService;
use App\Services\PaymentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use ScholarshipOwl\Data\Entity\Account\AccountType;
use ScholarshipOwl\Data\Entity\Account\AccountStatus;
use ScholarshipOwl\Data\Entity\Account\Conversation;
use ScholarshipOwl\Data\Entity\Account\LoginHistory;
use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Service\Account\ConversationService;
use App\Services\Account\Exception\EmailAlreadyRegisteredException;
use ScholarshipOwl\Data\Service\Account\ProfileService;
use ScholarshipOwl\Data\Service\Account\ReferralService;
use ScholarshipOwl\Data\Service\Account\SearchService as AccountSearchService;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Data\Service\Info\MilitaryAffiliationService;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;
use ScholarshipOwl\Data\Service\Mission\MissionAccountService;
use ScholarshipOwl\Data\Service\Payment\ApplicationService;
use ScholarshipOwl\Data\Service\Payment\StatisticService as PaymentStatisticService;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Scholarship\ScholarshipService;
use ScholarshipOwl\Domain\Repository\SubscriptionRepository;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;
use ScholarshipOwl\Util\Storage;

/**
 * Account Controller for accounts
 *
 * @author Marko Prelic <markomys@gmail.com>
 */
class AccountController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PaymentManager
     */
    protected $pm;

    /**
     * @var AccountLoginTokenService
     */
    protected $loginTokenService;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * @var AccountRepository
     */
    protected $accounts;

    /**
     * @var EntityRepository
     */
    protected $history;

    /**
     * @var \App\Entity\Repository\SubscriptionRepository
     */
    protected $subscriptions;

    /**
     * @var \App\Services\Account\AccountService
     */
    protected $accountService;


    /**
     * AccountController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(
        EntityManager $em,
        PaymentManager $pm,
        AccountLoginTokenService $loginTokenService,
        \App\Services\Account\AccountService $accountService
    )
    {
        parent::__construct();

        $this->em = $em;
        $this->pm = $pm;
        $this->accounts = $em->getRepository(\App\Entity\Account::class);
        $this->scholarships = $em->getRepository(Scholarship::class);
        $this->history = $em->getRepository(\App\Entity\Log\LoginHistory::class);
        $this->subscriptions = $em->getRepository(Subscription::class);
        $this->loginTokenService = $loginTokenService;
        $this->accountService = $accountService;
    }

    /**
     * Accounts Index Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function indexAction()
    {
        $model = new ViewModel("admin/accounts/index");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
            ),
            "title" => "Accounts",
            "active" => "accounts"
        );

        $model->setData($data);
        return $model->send();
    }


    /**
     * Register Account Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function registerAction()
    {
        $model = new ViewModel("admin/accounts/register");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Register Account" => "/admin/accounts/register"
            ),
            "title" => "Register Account",
            "active" => "accounts",
            "options" => array(
                'domains' => Domain::options(),
                "account_types" => array("" => "--- Select ---") + AccountType::getAccountTypes(),
                "account_statuses" => array("" => "--- Select ---") + AccountStatus::getAccountStatuses()
            )
        );

        $model->setData($data);
        return $model->send();
    }


    /**
     * Post Register Account Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function postRegisterAction()
    {
        $model = new JsonModel();

        try {
            $input = $this->getAllInput();
            $errors = array();

            $messages = [
                'first_name.required' => 'Please enter first name!',
                'last_name.required' => 'Please enter last name!',
                'email.required' => 'Please enter email!',
                'email.email' => 'Email address is invalid!',
            ];

            $rules = [
                'first_name' => 'required',
                'last_name'  => 'required',
                'account_type_id'  => 'required',
                'account_status_id'  => 'required',
                'email'      => 'email|required|unique:App\Entity\Account,email,NULL,account_id,domain,'. \Domain::get()->getId().'|not_regex:/(.*)application-inbox\.com$/i',
            ];

            $validator = \Validator::make($input, $rules, $messages);

            if (empty($input["password"])) {
                $errors["password"] = "Password is empty !";
            } else if (strlen($input["password"]) < 6) {
                $errors["password"] = "Password too short. Minimum 6 characters !";
            }

            if (empty($input["retype_password"])) {
                $errors["retype_password"] = "Retype password is empty !";
            }

            if ($input["password"] != $input["retype_password"]) {
                $errors["password"] = "Passwords not the same !";
                $errors["retype_password"] = "Passwords not the same !";
            }

            if ($validator->fails()) {
                $errors = $validator->getMessageBag()->messages();
            }

            if (empty($errors)) {
                try {
                    unset($input["retype_password"]);
;                    $account = $this->accountService->register(
                        $input['first_name'],
                        $input['last_name'],
                        $input['email'],
                        null
                    );
                    $account->setDomain(Domain::SCHOLARSHIPOWL);
                    $account->setAccountStatus($input['account_status_id']);
                    $account->setAccountType($input['account_type_id']);
                    $account->setPassword(\Hash::make($input["password"]));

                    $this->em->persist($account);
                    $this->em->flush();

                    $accountId = $account->getAccountId();

                    $model->setStatus(JsonModel::STATUS_REDIRECT);
                    $model->setMessage("Account registered !");
                    $model->setData("/admin/accounts/edit?id=$accountId");
                } catch (EmailAlreadyRegisteredException $exc) {
                    $model->setStatus(JsonModel::STATUS_ERROR);
                    $model->setMessage("Please fix errors !");
                    $model->setData(array("email" => "Email already registered !"));
                }
            } else {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors !");
                $model->setData($errors);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);

            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setMessage("System error !");
        }

        return $model->send();
    }


    /**
     * Accounts Search Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function searchAction()
    {
        $model = new ViewModel("admin/accounts/search");

        $militaryAffiliationService = new MilitaryAffiliationService();

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Search Accounts" => "/admin/accounts/search"
            ),
            "title" => "Search Accounts",
            "active" => "accounts",
            "accounts" => array(),
            "count" => 0,
            "applications_count" => array(),
            "subscriptions" => array(),
            "search" => array(
                'domain' => Domain::SCHOLARSHIPOWL,
                "email" => "",
                "username" => "",
                "account_status_id" => "",
                "account_type_id" => "",
                "account_pro" => "",
                "first_name" => "",
                "last_name" => "",
                "phone" => "",
                "created_date_from" => "",
                "created_date_to" => "",
                "school_level_id" => array(),
                "degree_id" => array(),
                "degree_type_id" => array(),
                "gpa" => array(),
                "enrollment_year" => array(),
                "enrollment_month" => array(),
                "highschool" => "",
                "university" => "",
                "graduation_year" => array(),
                "graduation_month" => array(),
                "highschool_graduation_year" => array(),
                "highschool_graduation_month" => array(),
                "date_of_birth_from" => "",
                "date_of_birth_to" => "",
                "gender" => "",
                "citizenship_id" => array(),
                "ethnicity_id" => array(),
                "is_subscribed" => "",
                "country_id" => array(),
                "state_id" => array(),
                "city" => "",
                "address" => "",
                "zip" => "",
                "career_goal_id" => array(),
                "study_online" => array(),
                "has_active_subscription" => "",
                "package_id" => array(),
                "login_ip" => "",
                "login_action" => "",
                "login_date_from" => "",
                "login_date_to" => "",
                "military_affiliation_id" => "",
                'paid' => '',
                'agree_call' => '',
                'profile_type' => '',
            ),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/accounts/search",
                "url_params" => array()
            ),
            "options" => array(
                'domains' => Domain::options(),
                "genders" => array("" => "--- Select ---") + \App\Entity\Profile::genders(),
                "citizenships" => InfoServiceFactory::getArrayData("Citizenship"),
                "ethnicities" => InfoServiceFactory::getArrayData("Ethnicity"),
                "subscriptions" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
                "paid_subscriptions" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
                "school_levels" => InfoServiceFactory::getArrayData("SchoolLevel"),
                "degrees" => InfoServiceFactory::getArrayData("Degree"),
                "degree_types" => InfoServiceFactory::getArrayData("DegreeType"),
                "gpas" => \App\Entity\Profile::gpas(),
                "enrollment_years" => \App\Entity\Profile::getFutureYearsArray(),
                "enrollment_months" => \App\Entity\Profile::getMonthsArray(),
                "graduation_years" => \App\Entity\Profile::getFutureYearsArray(),
                "graduation_months" => \App\Entity\Profile::getMonthsArray(),
                "countries" => InfoServiceFactory::getArrayData("Country"),
                "states" => InfoServiceFactory::getArrayData("State"),
                "career_goals" => InfoServiceFactory::getArrayData("CareerGoal"),
                "study_online" => \App\Entity\Profile::studyOnlineOptions(),
                "account_types" => AccountType::getAccountTypes(),
                "account_statuses" => AccountStatus::getAccountStatuses(),
                "account_pro" => array("" => "", "1" => "Yes", "0" => "No"),
                "profile_types" => array("null" => "Null") + \App\Entity\Profile::getProfileTypes(),
                "packages" => array(),
                "login_actions" => LoginHistory::getLoginActions(),
                "military_affiliations" => array("" => "--- Select ---") + $militaryAffiliationService->getMilitaryAffiliations()["data"],
                "agree_call" => ['' => '--- Select ---', '1' => 'Yes', '0' => 'No'],
            )
        );

        $accountSearchService = new AccountSearchService();
        $applicationService = new ApplicationService();
        $packageService = new PackageService();
        $paymentStatisticService = new PaymentStatisticService();

        $display = 25;
        $pagination = $this->getPagination($display);

        $input = $this->getAllInput();
        if (isset($input["phone"])) {
            $input["phone"] = intval(preg_replace('/[^0-9]+/', '', $input["phone"]), 10);
        }
        unset($input["page"]);
        foreach ($input as $key => $value) {
            $data["search"][$key] = $value;
        }

        $packages = $packageService->getPackages();
        foreach ($packages as $packageId => $package) {
            $data["options"]["packages"][$packageId] = $package->getName();
        }

        $searchResult = $accountSearchService->searchAccounts($data["search"], $pagination["limit"]);

        $accountIds = array_keys($searchResult['data']);
        $accounts = \EntityManager::getRepository(\App\Entity\Account::class)
            ->findBy(['accountId' => $accountIds], ['accountId' => 'DESC']);

        // Let doctrine put profiles to memory to use it later on when profile relation will be invoked
        \EntityManager::getRepository(\App\Entity\Profile::class)->findBy(['account' => $accountIds]);

        foreach ($accounts as $k => $account) {
            $accounts[$k] = new Resource($account, true);
        }

        /** @var \App\Entity\Repository\SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = $this->em->getRepository(Subscription::class);
        $data["subscriptions"] = $subscriptionRepository->findActiveSubscriptions($accountIds);

        $data["accounts"] = $accounts;
        $data["count"] = $searchResult["count"];
        $data["pagination"]["page"] = $pagination["page"];
        $data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
        $data["pagination"]["url_params"] = $data["search"];

        $model->setData($data);
        return $model->send();
    }


    /**
     * View Account Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function viewAction()
    {
        $data = [
            "user" => $this->getLoggedUser(),
            "breadcrumb" => [
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Search Accounts" => "/admin/accounts/search"
            ],
            "title" => "View Account",
            "active" => "accounts",
            "account" => null,
            "subscriptions" => null,
            "referrals" => [],
            "loginHistory" => [],
            "files" => [],
            "marketing" => null,
            "onboardingCalls" => [],
        ];

        $referralService = new ReferralService();
        $marketingSystemService = new MarketingSystemService();

        $id = $this->getQueryParam("id");

        if (!empty($id)) {
            /** @var \App\Entity\Account $accountEntity */
            try {
                $accountEntity = \EntityManager::findById(\App\Entity\Account::class, $id);
            } catch (EntityNotFound $exception) {
                    return redirect()->route('admin::accounts.search')
                        ->with('error', "Account with id [ ${id} ] not found");
            }

            /** @var \App\Entity\Account $account */
            $account = new Resource($accountEntity);
            $referrals = $referralService->getAccountReferrals($id);
            $historyRepository = \EntityManager::getRepository(\App\Entity\Log\LoginHistory::class)->findBy(
                ['account' => $account->getAccountId()],
                ['loginHistoryId' => 'DESC']
            );
            $loginHistory = Resource::getResourceCollection($historyRepository);
            $data["transactions"] = \EntityManager::getRepository(Transaction::class)->findBy(
                ['account' => \EntityManager::find(\App\Entity\Account::class, $id)],
                ['createdDate' => 'DESC']
            );
            $data["title"] = $account->getProfile()->getFirstName() . ' ' . $account->getProfile()->getLastName();
            $data["account"] = $account;
            $data['accountLoginToken'] = $this->loginTokenService->getLatestToken($accountEntity);
            $data["subscriptions"] = $accountEntity->getSubscriptions();
            $data["referrals"] = $referrals;
            $data["breadcrumb"]["View Profile"] = route('admin::accounts.view', ['id' => $account->getAccountId()]);
            $data["loginHistory"] = $loginHistory;
            $data["marketing"] = $marketingSystemService->getMarketingSystemAccount($id, true);
            $onboardingCalls = \EntityManager::getRepository(AccountOnBoardingCall::class)->findBy(
                ['account' => $accountEntity]
            );
            if ($onboardingCalls) {
                $data["onboardingCalls"] = $onboardingCalls[0];
            }
            $data["supercollegeEligibility"] = $accountEntity->getSuperCollegeScholarshipMatches()->count();
            $data["files"] = \EntityManager::getRepository(AccountFile::class)->findBy(['account' => $accountEntity]);
        } else {
            return redirect()->route('admin::accounts.search')
                ->with('error', "Account id not provided");
        }

        return view('admin.accounts.view', $data);
    }


    /**
     * Edit Account Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function editAction(Request $request)
    {
        $this->validate($request, ['id' => 'required']);
		$militaryAffiliationService = new MilitaryAffiliationService();


		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Accounts" => "/admin/accounts",
				"Search Accounts" => "/admin/accounts/search"
			),
			"title" => "Edit Account",
			"active" => "accounts",
			"can_edit_account" => false,
			"options" => array(
				"genders" => array("" => "--- Select ---") + \App\Entity\Profile::genders(),
				"citizenships" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("Citizenship"),
				"ethnicities" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("Ethnicity"),
				"subscriptions" => array("" => "--- Select ---") + array("1" => "Yes", "0" => "No"),
				"school_levels" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("SchoolLevel"),
				"degrees" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("Degree"),
				"degree_types" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("DegreeType"),
				"gpas" => \App\Entity\Profile::gpas(),
				"enrollment_years" => array("" => "--- Select ---") + \App\Entity\Profile::getFutureYearsArray(),
				"enrollment_months" => array("" => "--- Select ---") + \App\Entity\Profile::getMonthsArray(),
				"graduation_years" => array("" => "--- Select ---") + \App\Entity\Profile::getFutureYearsArray(),
				"graduation_months" => array("" => "--- Select ---") + \App\Entity\Profile::getMonthsArray(),
				"countries" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("Country"),
				"states" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("State"),
				"career_goals" => array("" => "--- Select ---") + InfoServiceFactory::getArrayData("CareerGoal"),
				"study_online" => array("" => "--- Select ---") + \App\Entity\Profile::studyOnlineOptions(),
				"account_types" => AccountType::getAccountTypes(),
				"account_statuses" => AccountStatus::getAccountStatuses(),
				"military_affiliations" => array("" => "--- Select ---") + $militaryAffiliationService->getMilitaryAffiliations()["data"],
                "profile_type" => [
                    "" => "--- Select ---",
                    \App\Entity\Profile::PROFILE_TYPE_STUDENT => "Student",
                    \App\Entity\Profile::PROFILE_TYPE_PARENT => "Parent"
                ],
                "onboarding_calls" => array("" => "--- Select ---") + array("1" => "Yes", "0" => "No"),
                "enrolled" => array("" => "--- Select ---") + array("1" => "Yes", "0" => "No"),
                'recurrence_settings' => [
                    \App\Entity\Profile::RECURRENT_APPLY_DISABLED => 'Disabled',
                    \App\Entity\Profile::RECURRENT_APPLY_ON_DEADLINE => 'On Deadline',
                ],
            )
        );

        $id = $request->get('id');

        /** @var \App\Entity\Account $account */
        try {
            $account = new Resource(\EntityManager::findById(\App\Entity\Account::class, $id));
        } catch (EntityNotFound $exception) {
                return redirect()->route('admin::accounts.search')
                    ->with('error', "Account with id [ ${id} ] not found");
        }

        $accountTypeId = $account->getAccountType()->getId();
        $loggedId = $this->getLoggedUser()->getAccountId();

        $data["title"] = $account->getProfile()->getFirstName() . ' ' . $account->getProfile()->getLastName();
        $data["account"] = $account;
        $onboardingCalls = \EntityManager::getRepository(AccountOnBoardingCall::class)->findBy(
            ['account' => $account->getAccountId()]
        );
        if ($onboardingCalls) {
            $data["onboardingCalls"] = $onboardingCalls[0];
        }

        $data["can_edit_account"] = ($accountTypeId == AccountType::USER) || ($id == $loggedId);
        $data["breadcrumb"]["Edit Profile"] = route('admin::accounts.view', ['id' => $account->getAccountId()]);

        return view('admin.accounts.edit', $data);
    }

    /**
     * Account delete
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAction()
    {
        $return = "/admin/accounts/search";
        $page = "1";
        $params = "";

        $id = $this->getQueryParam("id");

        $page = $this->getQueryParam("page", "1");
        $params = $this->getQueryParam("params", "");

        $account = $this->accountService->deleteAccount($id);

        // Since account nt is "soft deleted" we probably do not want storage cleanup
        // Storage::cleanPath(Storage::getMailboxPath($id));

        $this->logInfo('DELETED_ACCOUNT [' . $account->getAccountId() . '][' . $account->getEmail() . ']');

        $return .= "?page=" . $page;
        if (!empty($params)) {
            $return .= "&" . base64_decode($params);
        }

        return $this->redirect($return);
    }


    /**
     * Account Impersonation Action
     *
     * @access public
     * @return Response
     *
     * @author Frank Castillo <frank.castillo@yahoo.com>
     */
    public function impersonateAction()
    {
        \Session::put('impersonatorAccountId', $this->getLoggedUser()->getAdminId());
        \Auth::guard('web')->loginUsingId($this->getQueryParam("id"));

        return $this->redirect("my-account");
    }


    /**
     * Account Applications Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function applicationsAction()
    {
        $model = new ViewModel("admin/accounts/applications");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Search Accounts" => "/admin/accounts/search"
            ),
            "title" => "Account Applications",
            "active" => "accounts",
            "applications" => array()
        );

        try {

            $id = $this->getQueryParam("id");
            /**
             * @var \App\Entity\Account $account
             */
            $account = $this->em->getRepository(\App\Entity\Account::class)->findOneBy(['accountId' => $id]);
            if (!empty($id)) {
                /**
                 * @var AccountApplicationService $appSer
                 */
                $appSer = app(AccountApplicationService::class);
                $applications = $appSer->getApplications($account);

                $data["title"] = $account->getProfile()->getFullName();
                $data["applications"] = $applications;
                $data["breadcrumb"][$account->getProfile()->getFullName()] = "/admin/accounts/view?id=$id";
                $data["breadcrumb"]["Applications"] = "/admin/accounts/applications?id=$id";
            } else {
                throw new \Exception("Account id not provided !");
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }


    /**
     * Account Subscriptions Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function subscriptionsAction(Request $request, $id)
    {
        $account = $this->em->getRepository(\App\Entity\Account::class)->findOneBy(['accountId' => $id]);

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'subscription_id' => 'required|exists:' . Subscription::class . ',subscriptionId',
                'subscription_acquired_type' => 'required',
                'active_until' => 'date',
            ]);

            $subscription = $this->subscriptions->findById($request->get('subscription_id'));
            $subscription->setSubscriptionAcquiredType($request->get('subscription_acquired_type'));
            if ($request->get('active_until')) {
                $subscription->setActiveUntil(new \DateTime($request->get('active_until')));
            }

            $this->em->flush($subscription);

            return redirect(route('admin::accounts.subscriptions', $id));
        }

        $model = new ViewModel("admin/accounts/subscriptions");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Search Accounts" => "/admin/accounts/search"
            ),
            "title" => "Account Subscriptions",
            "active" => "accounts",
            'accountId' => '',
            'packages' => array(),
            "subscriptions" => array(),
            "transactions" => array()
        );

        /** @var Subscription[] $subscriptions */
        $subscriptions = $this->em->getRepository(Subscription::class)->findBy(['account' => $id]);

        $packageService = new PackageService();
        $packages = $packageService->getPackages();

        $hasActive = "";

        foreach ($subscriptions as $subscription) {
            if ($subscription->getSubscriptionStatus()->getId() == SubscriptionStatus::ACTIVE) {
                $hasActive = "1";
                break;
            }
        }
        $data["subscriptions"] = $subscriptions;


        $data["title"] = $account->getProfile()->getFullName();
        $data['accountId'] = $id;

        $data["packages"] = $packages;
        $data["hasActive"] = $hasActive;
        $data["breadcrumb"][$account->getProfile()->getFullName()] = "/admin/accounts/view?id=$id";
        $data["breadcrumb"]["Subscriptions"] = "/admin/accounts/subscriptions?id=$id";

        $model->setData($data);
        return $model->send();
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelSubscriptionAction($id)
    {
        $subscription = $this->subscriptions->findById($id);
        $account = $subscription->getAccount();

        $this->pm->cancelSubscription($subscription);

        return $this->redirect(route('admin::accounts.subscriptions', $account->getAccountId()));
    }


    /** Account conversations Action
     *
     * @access public
     * @return Response
     *
     * @author Branislav Jovanovic <branej@gmail.com>
     */
    public function conversationsAction()
    {
        $model = new ViewModel("admin/accounts/conversations/index");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Search Accounts" => "/admin/accounts/search"
            ),
            "title" => "Contact conversations",
            "active" => "accounts",
            "conversations" => array(),
            'options' => array(
                "statuses" => Conversation::getStatuses(),
                "potentials" => Conversation::getPotentials(),
            ),
        );

        try {
            $conversationService = new ConversationService();
            $id = $this->getQueryParam("id");

            if (!empty($id)) {
                $conversations = $conversationService->getAccountConversations($id);
                $profile = $this->em->getRepository(\App\Entity\Profile::class)->findOneBy(['account' => $id]);

                $data["title"] = $profile->getFullName();
                $data["account_id"] = $id;
                $data["conversations"] = $conversations;
                $data["breadcrumb"][$profile->getFullName()] = "/admin/accounts/view?id=$id";
                $data["breadcrumb"]["Conversations"] = "/admin/accounts/conversations?id=$id";
            } else {
                throw new \Exception("Account id not provided !");
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
        $model->setData($data);
        return $model->send();
    }

    /**
     * Add Conversation Action
     *
     * @access public
     * @return Response
     *
     * @author Branislav Jovanovic <branej@gmail.com>
     */
    public function addConversationAction()
    {
        $model = new ViewModel("admin/accounts/conversations/form");

        try {
            $aid = $this->getQueryParam("aid");
            $profile = $this->em->getRepository(\App\Entity\Profile::class)->findOneBy(['account' => $aid]);

            $data = array(
                "user" => $this->getLoggedUser(),
                "breadcrumb" => array(
                    "Dashboard" => "/admin/dashboard",
                    "Accounts" => "/admin/accounts",
                    "Search Accounts" => "/admin/accounts/search",
                    $profile->getFullName() => "/admin/accounts/view?id=$aid",
                    "Conversations" => "/admin/accounts/conversations?id=$aid",
                    "Add Conversation" => "/admin/accounts/conversations/add?aid=$aid"
                ),
                "title" => $profile->getFullName(),
                "active" => "accounts",
                "options" => array(
                    "statuses" => array("" => "--- Select ---") + Conversation::getStatuses(),
                    "potentials" => array("" => "--- Select ---") + Conversation::getPotentials()
                ),
                'data' => array(
                    'title' => 'Save Conversation',
                    'action' => 'add',
                    'action_msg' => 'Add',
                    'conversation_id' => '',
                    'account_id' => $aid,
                    'status' => '',
                    'potential' => '',
                    'comment' => ''
                ),
            );
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
        $model->setData($data);
        return $model->send();
    }

    /**
     * Post Add Conversation Action
     *
     * @access public
     * @return Response
     *
     * @author Branislav Jovanovic <branej@gmail.com>
     */
    public function postAddConversationAction()
    {
        $model = new JsonModel();

        try {
            $service = new ConversationService();
            $input = $this->getAllInput();
            $errors = array();

            $conversation = new Conversation();
            $statuses = Conversation::getStatuses();
            $potentials = Conversation::getPotentials();

            if (empty($input["status"])) {
                $errors["status"] = "Select conversation status !";
            } else if (!array_key_exists($input['status'], $statuses)) {
                $errors['status'] = 'Select valid conversation status !';
            }

            if (empty($input["potential"])) {
                $errors["potential"] = "Potential is not set !";
            } else if (!array_key_exists($input['potential'], $potentials)) {
                $errors['potential'] = 'Select valid conversation potential !';
            }

            if (empty($errors)) {
                $conversation->populate($input);
                $account = $this->em->getRepository(\App\Entity\Account::class)
                    ->findOneBy(['accountId' => $input['account_id']]);
                $service->registerConversation($conversation, $account);
                $model->setStatus(JsonModel::STATUS_REDIRECT);
                $model->setMessage("Conversation added !");
                $model->setData("/admin/accounts/conversations/?id=" . $input['account_id']);
            } else {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors !");
                $model->setData($errors);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);

            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setMessage("System error !");
        }

        return $model->send();
    }

    /**
     * Edit Conversation Action
     *
     * @access public
     * @return Response
     *
     * @author Branislav Jovanovic <branej@gmail.com>
     */
    public function editConversationAction()
    {
        $model = new ViewModel("admin/accounts/conversations/form");

        try {
            $id = $this->getQueryParam("id");

            $service = new ConversationService();
            $conversation = $service->getConversation($id);

            $profile = $this->em->getRepository(\App\Entity\Profile::class)->findOneBy(['account' => $aid]);

            $statuses = Conversation::getStatuses();
            $potentials = Conversation::getPotentials();

            $data = array(
                "user" => $this->getLoggedUser(),
                "breadcrumb" => array(
                    "Dashboard" => "/admin/dashboard",
                    "Accounts" => "/admin/accounts",
                    "Search Accounts" => "/admin/accounts/search",
                    $profile->getFullName() => "/admin/accounts/view?id=$id",
                    "Conversations" => "/admin/accounts/conversations?id=" . $conversation->getAccountId(),
                    "Edit Conversation" => "/admin/accounts/conversations/edit?id=$id"
                ),
                "title" => "Edit Conversation",
                "active" => "accounts",
                "options" => array(
                    "statuses" => array("" => "--- Select ---") + $statuses,
                    "potentials" => array("" => "--- Select ---") + $potentials,
                ),
                'data' => array(
                    'title' => 'Save Conversation',
                    'action' => 'edit',
                    'action_msg' => 'Update',
                    'conversation_id' => $conversation->getConversationId(),
                    'account_id' => $conversation->getAccountId(),
                    'status' => $conversation->getStatus(),
                    'potential' => $conversation->getPotential(),
                    'comment' => $conversation->getComment(),
                ),
            );
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
        $model->setData($data);
        return $model->send();
    }

    /**
     * Post Edit Account Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function postEditAction(\App\Services\Account\AccountService $as)
    {
        $model = new JsonModel();

        try {
            $profileService = new ProfileService();

            $input = $this->getAllInput();

            $action = $input["_action"];
            $errors = array();
            unset($input["_action"]);
            unset($input["_token"]);

            $profile = new Profile();
            $profile->populate($input);


            if ($action == "profile") {
                if (empty($input["first_name"])) {
                    $errors["first_name"] = "First name is empty!";
                }

                if (empty($input["last_name"])) {
                    $errors["last_name"] = "Last name is empty!";
                }

                if (empty($errors)) {
                    $profileService->setBasicProfile($profile);
                    $model->setStatus(JsonModel::STATUS_OK);
                    $model->setMessage("Profile information saved!");

                    // Fire Event
                    $this->fireUpdateAccountEvent($profile->getAccountId(), request()->header('Referer'));
                } else {
                    $model->setStatus(JsonModel::STATUS_ERROR);
                    $model->setMessage("Please fix errors!");
                    $model->setData($errors);
                }
            } else if ($action == "education") {
                $profileService->setEducationProfile($profile);
                $model->setMessage("Education saved!");

                // Fire Event
                $this->fireUpdateAccountEvent($profile->getAccountId(), request()->header('Referer'));
            } else if ($action == "interests") {
                $profileService->setInterestsProfile($profile);
                $model->setMessage("Interests saved!");

                // Fire Event
                $this->fireUpdateAccountEvent($profile->getAccountId(), request()->header('Referer'));
            } else if ($action == "location") {
                if ($input["zip"]) {
                    if (!is_numeric($input["zip"])) {
                        $errors["zip"] = "Zip code must be 5 digits!";
                    } else if (strlen($input["zip"]) < 5) {
                        $errors["zip"] = "Zip code is too short!";
                    } else if (strlen($input["zip"]) > 5) {
                        $errors["zip"] = "Zip code is too long!";
                    }
                }

                if (empty($errors)) {
                    $profileService->setLocationProfile($profile);
                    $model->setStatus(JsonModel::STATUS_OK);
                    $model->setMessage("Location saved!");

                    // Fire Event
                    $this->fireUpdateAccountEvent($profile->getAccountId(), request()->header('Referer'));
                } else {
                    $model->setStatus(JsonModel::STATUS_ERROR);
                    $model->setMessage("Please fix errors!");
                    $model->setData($errors);
                }
            } else if ($action == "account") {
                /** @var \App\Entity\Account $account */
                $account = \EntityManager::getRepository(\App\Entity\Account::class)->find($input['account_id']);

                if (empty($input["email"])) {
                    $errors["email"] = "Email is empty!";
                } else if (filter_var($input["email"], FILTER_VALIDATE_EMAIL) === false) {
                    $errors["email"] = "Email not valid!";
                } else if(preg_match('/(.*)application-inbox\.com$/i', ($input["email"]), $matches)) {
                    $errors["email"] = "Email not valid!";
                }

                if (empty($input["account_status_id"])) {
                    $errors["account_status_id"] = "Account status is empty!";
                }

                if (empty($input["account_type_id"])) {
                    $errors["account_type_id"] = "Account type is empty!";
                }

                if (!empty($input["password"]) || !empty($input["retype_password"])) {
                    if (empty($input["password"])) {
                        $errors["password"] = "Password not filled in!";
                    } else if (strlen($input["password"]) < 6) {
                        $errors["password"] = "Password too short. Minimum 6 characters!";
                    } else {
                        if ($input["password"] != $input["retype_password"]) {
                            $errors["password"] = "Passwords not the same!";
                            $errors["retype_password"] = "Passwords not the same!";
                        }
                    }
                }

                if (empty($errors)) {
                    if (!empty($input["password"])) {
                        $as->updatePassword($account, $input["password"]);
                        \Event::dispatch(new ChangePasswordEvent($account->getAccountId()));
                    }

                    if ($account->getEmail() !== $input["email"]) {
                        $prevEmail = $account->getEmail();

                        \EntityManager::getFilters()->disable('soft-deleteable');
                        $takenAccount = \EntityManager::getRepository(\App\Entity\Account::class)
                            ->findOneBy(['email' => $input['email'], 'domain' => $account->getDomain()->getId()]);
                        \EntityManager::getFilters()->enable('soft-deleteable');

                        if ($takenAccount) {
                            $model->setStatus(JsonModel::STATUS_ERROR);
                            $model->setMessage("Please fix errors!");
                            $model->setData(array("email" => "Email already taken!"));

                            return $model->send();
                        }

                        $account->setEmail($input["email"]);
                        \Event::dispatch(new ChangeEmailEvent($account,$prevEmail));
                    }

                    $account->setAccountStatus($input["account_status_id"]);
                    $account->getProfile()->setRecurringApplication($input["recurring_application"]);
                    $account->setSellInformation($input['sell_information']);

                    //remove all pending submissions for opted-out users
                    if($input['sell_information']) {
                        /**
                         * @var SubmissionService $submissionService
                         */
                        $submissionService = app(SubmissionService::class);
                        try {
                            $submissionService->removePendingSubmissionByAccountId($account);
                        } catch (\Exception $e) {
                            $this->handleException($e);
                        }
                    }

                    $this->em->flush();

                    $model->setStatus(JsonModel::STATUS_OK);
                    $model->setMessage("Account settings saved!");
                } else {
                    $model->setStatus(JsonModel::STATUS_ERROR);
                    $model->setMessage("Please fix errors!");
                    $model->setData($errors);
                }
            } else if ($action == "onboarding_calls") {

                /** @var \App\Entity\Account $account */
                $account = \EntityManager::findById(\App\Entity\Account::class, $input["account_id"]);

                if (null === ($accountOnBoardingCall = $account->getAccountOnBoardingCall()[0])) {
                    $account->setAccountOnBoardingCall($accountOnBoardingCall = new AccountOnBoardingCall());
                }

                $accountOnBoardingCall->setCall1((bool)$input["call1"]);
                $accountOnBoardingCall->setCall2((bool)$input["call2"]);
                $accountOnBoardingCall->setCall3((bool)$input["call3"]);
                $accountOnBoardingCall->setCall4((bool)$input["call4"]);
                $accountOnBoardingCall->setCall5((bool)$input["call5"]);

                \EntityManager::flush();

                // Fire Event
                $this->fireUpdateAccountEvent($profile->getAccountId(), request()->header('Referer'));

                $model->setMessage("Onboarding Calls saved!");
            }
        } catch (\Exception $e) {
            $this->handleException($e);

            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setMessage("System error!");
        }

        return $model->send();
    }
    /**
     * Post Edit Conversation Action
     *
     * @access public
     * @return Response
     *
     * @author Branislav Jovanovic <branej@gmail.com>
     */
    public function postEditConversationAction()
    {
        $model = new JsonModel();

        try {
            $service = new ConversationService();

            $input = $this->getAllInput();
            $errors = array();

            $statuses = Conversation::getStatuses();
            $potentials = Conversation::getPotentials();

            if (empty($input['conversation_id'])) {
                throw new \Exception('No conversation id provided !');
            } else {
                $conversation = $service->getConversation($input['conversation_id']);
            }

            if (empty($input["status"])) {
                $errors["status"] = "Select conversation status !";
            } else if (!array_key_exists($input['status'], $statuses)) {
                $errors['status'] = 'Select valid conversation status !';
            }

            if (empty($input["potential"])) {
                $errors["potential"] = "Potential is not set !";
            } else if (!array_key_exists($input['potential'], $potentials)) {
                $errors['potential'] = 'Select valid conversation potential !';
            }

            if (empty($errors)) {
                $conversation->populate($input);
                $service->setConversationInfo($conversation);

                $model->setStatus(JsonModel::STATUS_REDIRECT);
                $model->setMessage("Conversation " . $conversation->getConversationId() . " updated!");
                $model->setData("/admin/accounts/conversations/?id=" . $conversation->getAccountId());
            } else {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors !");
                $model->setData($errors);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);

            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setMessage("System error !");
        }

        return $model->send();
    }

    /**
     * Delete Conversation Action
     *
     * @access public
     * @return response
     *
     * @author Branislav Jovanovic <branej@gmail.com>
     */
    public function deleteConversationAction()
    {
        try {
            $service = new ConversationService();
            $id = $this->getQueryParam("id");
            $account_id = $service->getConversation($id)->getAccountId();

            $service->unregisterConversation($id);
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $this->redirect('/admin/accounts/conversations?id=' . $account_id);
    }


    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function loginHistoryAction(Request $request)
    {
        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Search Accounts" => "/admin/accounts/search"
            ),
            "title" => "Contact login history",
            "active" => "accounts",
            "history" => array(),
        );

        /** @var \App\Entity\Account $account */
        $account = $this->accounts->findById($id = $request->get('id'));

        /** @var \App\Entity\Log\LoginHistory[] $loginHistory */
        $loginHistory = $this->history->findBy(['account' => $account], ['loginHistoryId' => 'DESC']);

        /** @var \App\Entity\Account $account */
        $account = new Resource($account);
        $fullName = $account->getProfile()->getFirstName() . ' ' . $account->getProfile()->getLastName();
        $data["title"] = $fullName . "'s Login History";
        $data["account_id"] = $id;
        $data["history"] = Resource::getResourceCollection($loginHistory);
        $data["breadcrumb"][$fullName] = route('admin::accounts.view', ['id' => $id]);
        $data["breadcrumb"]["Login History"] = route('admin::accounts.loginHistory', ['id' => $id]);

        return view('admin.accounts.loginhistory', $data);
    }

    /**
     * Eligibility Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function eligibilityAction(Request $request)
    {
        $model = new ViewModel("admin/accounts/eligibility");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Accounts" => "/admin/accounts",
                "Search Accounts" => "/admin/accounts/search"
            ),
            "title" => "Account Eligibility",
            "active" => "accounts",
            "scholarships" => array(),
        );

        $id = $request->get('id');

        /** @var \App\Entity\Account $account */
        $account = $this->accounts->findById($id);

        $scholarshipService = new ScholarshipService();
        $scholarships = $scholarshipService->getScholarshipsInfo(
            $this->scholarships->findEligibleNotAppliedScholarshipsIds($account)
        );

        $data["title"] = $account->getProfile()->getFullName();
        $data["scholarships"] = $scholarships;
        $data["breadcrumb"][$account->getProfile()->getFullName()] = "/admin/accounts/view?id=$id";
        $data["breadcrumb"]["Eligibility"] = "/admin/accounts/eligibility?id=$id";

        $model->setData($data);
        return $model->send();
    }

    /**
     * Post Add Subscription Action
     *
     * @access public
     * @return Response
     *
     * @author Branislav Jovanovic <branej@gmail.com>
     */
    public function postAddSubscriptionAction()
    {
        $model = new JsonModel();

        try {

            $input = $this->getAllInput();
            $errors = array();

            if (empty($input['packageId'])) {
                throw new \Exception('No package id provided !');
            }

            if (empty($input['accountId'])) {
                throw new \Exception('No account id provided !');
            }

            \PaymentManager::applyPackageOnAccount(
                \EntityManager::findById(\App\Entity\Account::class, $input['accountId']),
                \EntityManager::findById(\App\Entity\Package::class, $input['packageId']),
                \App\Entity\SubscriptionAcquiredType::FREEBIE
            );


            $model->setStatus(JsonModel::STATUS_REDIRECT);
            $model->setMessage("Added subscription for package " . $input['packageId']);
        } catch (\Exception $exc) {
            $this->handleException($exc);

            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setMessage("System error !");
        }

        return $model->send();
    }

    /**
     * method that fires update account event after each profile information update
     *
     * @param $accountId
     * return void
     */
    private function fireUpdateAccountEvent(int $accountId, $referer = null)
    {
        \Event::dispatch(new UpdateAccountEvent($accountId, $referer));
    }
}

