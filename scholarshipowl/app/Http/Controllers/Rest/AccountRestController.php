<?php
# CrEaTeD bY FaI8T IlYa
# 2016
namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Domain;
use App\Entity\FeatureSet;
use App\Entity\Marketing\SubmissionSources;
use App\Entity\Profile;
use App\Entity\Repository\AccountRepository;
use App\Entity\Resource\AccountResource;
use App\Entity\Repository\EntityRepository;
use App\Entity\SocialAccount;
use App\Entity\State;
use App\Entity\Traits\Dictionary;
use App\Http\Controllers\RestController;
use App\Http\Middleware\Authenticate;
use App\Jobs\UpdateSubmissions;
use App\Rest\Requests\AccountRegisterRequest;
use App\Rest\Requests\RestRequest;
use App\Services\Account\Exception\SocialAccountException;
use App\Services\Account\SocialAccountService;
use App\Services\HasOffersService;
use App\Services\FacebookService;
use App\Services\Marketing\SubmissionService;
use App\Services\OptionsManager;
use App\Services\PasswordService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use ScholarshipOwl\Data\ResourceInterface;
use App\Services\Account\AccountService;
use ScholarshipOwl\Data\Entity\Account\Profile as ProfileService;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use Tymon\JWTAuth\Exceptions\JWTException;


/**
 * Class AccountRestController
 * @package App\Http\Controllers\Rest
 */
class AccountRestController extends RestController
{
    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var HasOffersService
     */
    protected $ho;

    /**
     * @var OptionsManager
     */
    protected $om;

    /**
     * @var SubmissionService
     */
    protected $ss;

    /**
     * AccountRestController constructor.
     *
     * @param EntityManager     $em
     * @param AccountService    $accountService
     * @param HasOffersService  $ho
     * @param OptionsManager    $om
     * @param SubmissionService $ss
     */
    public function __construct(
        EntityManager $em,
        AccountService $accountService,
        HasOffersService $ho,
        OptionsManager $om,
        SubmissionService $ss
    )
    {
        parent::__construct($em);

        $this->accountService = $accountService;
        $this->ho = $ho;
        $this->om = $om;
        $this->ss = $ss;
    }

    /**
     * @return AccountRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(Account::class);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('ac');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getBaseIndexCountQuery(Request $request)
    {
        $qb = $this->getBaseIndexQuery($request);

        return $qb->select($qb->expr()->count('ac.accountId'));
    }

    /**
     * @return mixed
     */
    protected function getResource()
    {
        return new AccountResource();
    }

    /**
    * @param AccountRegisterRequest $request
    *
    * @return array
    * @throws \Exception
    */
    public function register(AccountRegisterRequest $request)
    {
        $account = $this->accountService->register(
            $request->get('firstName'),
            $request->get('lastName'),
            $request->get('email'),
            $request->get('phone'),
            $request->country()
        );

        $reference = \EntityManager::getReference(FeatureSet::class, FeatureSet::config()->getId());
        $account->setFset($reference);

        $account->getProfile()->setCitizenship($request->citizenship());
        $account->getProfile()->setStudyCountries($request->studyCountries());
        $account->getProfile()->setAgreeCall($request->isAgreeCall());

        $session = $this->getRegistrationData();

        if (empty($session['birthday_date']) &&
            !empty($session['birthday_day']) &&
            !empty($session['birthday_month']) &&
            !empty($session['birthday_year'])) {
            $account->getProfile()->setDateOfBirth(
                Carbon::create(
                    $session['birthday_year'],
                    $session['birthday_month'],
                    $session['birthday_day']
                )
            );
        } elseif (!empty($session['birthday_date'])) {
            $account->getProfile()->setDateOfBirth(new \DateTime($session['birthday_date']));
        }

        if (!empty($session['gender'])) {
            $account->getProfile()->setGender($session['gender']);
        }

        if (!empty($session['school_level_id'])) {
            $account->getProfile()->setSchoolLevel($session['school_level_id']);
        }

        if (!empty($session['degree_id'])) {
            $account->getProfile()->setDegree($session['degree_id']);
        }

        $this->em->flush();

        \Session::put('ACCOUNT_REGISTRATION', true);

        \Auth::loginUsingId($account->getAccountId());

        if ($request->coregs()) {
            $source = is_mobile() ? SubmissionSources::MOBILE : SubmissionSources::DESKTOP;

            $this->ss->addSubmissions($request->coregs(), $account, $request->getClientIp(), $source);
        }

        $this->ho->saveMarketingSystemAccount($request, $account->getAccountId());

        dispatch(new UpdateSubmissions($account));

        if ($redirect = $request->redirect()) {
            return redirect($redirect);
        }


        // Generate cookies with auth token which might be used by server later to re-authorize a user
        // if session has expired on one of the registration steps
        /** @var \App\Services\PubSub\AccountService $accountService */
        $accountService = app(\App\Services\PubSub\AccountService::class);
        $token = $accountService->setRegenerateLoginToken(true)
            ->populateMergeFields(
                [$account],
                [\App\Services\PubSub\AccountService::FIELD_LOGIN_TOKEN]
            )[$account->getAccountId()]['login_token'];
        $cookie = cookie(Authenticate::RE_AUTH_TOKEN, $token, 60 * 24 * 60);

        return $this->jsonResponse($account)->withCookie($cookie);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if (!\Domain::isScholarshipOwl()) {
            $this->accountService->setFacebookApp(FacebookService::APPLYME_APP);
        }
        try {
            $account = $this->accountService->registerAccount($request->all());

            return $this->jsonResponse($account, ['token' => \JWTAuth::fromUser($account)]);
        } catch (FacebookResponseException $e) {
            return response()->json(['status' => $e->getHttpStatusCode(), 'error' => $e->getMessage()], $e->getHttpStatusCode());
        }
    }

    /**
     * @param Request $request
     * @param int $accountId
     * @return JsonResponse
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, int $accountId)
    {
        /** @var Account $account */
        $this->authorize('show', $account = $this->findById($accountId));
        $this->accountService->updateProfile($account, $request->all());

        return $this->jsonResponse($account);
    }

    /**
     * @return JsonResponse
     */
    public function getRegisterData()
    {
        // Put `Undecided` to the bottom of array
        $degreeType = InfoServiceFactory::get("DegreeType")->getAll(false);
        $undecided = $degreeType[1];
        unset($degreeType[1]);
        $degreeType[1] = $undecided;
        return $this->jsonSuccessResponse('success', [
            "genders"      => Profile::genders(),
            "citizenships" => Citizenship::options(['country' => Country::USA]),
            "ethnicities"  => InfoServiceFactory::get("Ethnicity")->getAll(false),
            "gpas"         => array("N/A" => "N/A") + Profile::gpas(),
            "degrees"      => InfoServiceFactory::get("Degree")->getAll(false),
            "degreeTypes"  => $degreeType,
            "careerGoals"  => InfoServiceFactory::get("CareerGoal")->getAll(false),
            "schoolLevels" => InfoServiceFactory::getArrayData("SchoolLevel"),
            "studyOnline"  => Profile::studyOnlineOptions(),
            "states"       => InfoServiceFactory::get("State")->getAll(false)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function checkRegistrationEmail(Request $request)
    {
        $validator = Validator::make($request->toArray(),
            ['email'     => 'email|required|unique:App\Entity\Account,email,NULL,account_id,domain,'
            . \Domain::get()->getId()]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->jsonSuccessResponse(true);
    }

    /**
     * Redirect a user to FB oauth server to be later redirected to SOWL url specified in redirect parameter
     *
     * @param Request $request
     * @param LaravelFacebookSdk $serviceFb
     * @return \Illuminate\Http\RedirectResponse
     */
    public function linkFacebookAccount(Request $request, LaravelFacebookSdk $serviceFb)
    {
        /** @var Account $account */
        $account = \Auth::user();
        \Cache::put("link-facebook-redirect-url-{$account->getAccountId()}", $request->get('redirect'), 60);

        if ($account->getSocialAccount()) {
            $error = 'Your account already linked to Facebook';
            return redirect()->to($this->buildRedirectUrl($account, compact('error')));
        }

        return redirect()->to(
            $serviceFb->getLoginUrl([], route('rest::v1.callbackFacebook'))
        );
    }

    /**
     * Called by Facebook after user gave their consent on FB oauth server
     *
     * @param LaravelFacebookSdk $serviceFb
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function callbackFacebook(LaravelFacebookSdk $serviceFb)
    {
        /** @var Account $account */
        $account = \Auth::user();

        // Obtain an access token.
        $token = $serviceFb->getAccessTokenFromRedirect(route('rest::v1.callbackFacebook'));

        // Access token will be null if the user denied the request
        // or if someone just hit this URL outside of the OAuth flow.
        if (!$token) {
            $error = $serviceFb->getRedirectLoginHelper()->getError();
            if (!$error) {
                $error = 'Failed to access Facebook account';
            }

            return redirect()->to($this->buildRedirectUrl($account, compact('error')));
        }

        if (!$token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauthClient = $serviceFb->getOAuth2Client();
            // Extend the access token.
            $token = $oauthClient->getLongLivedAccessToken($token);
        }

        $serviceFb->setDefaultAccessToken($token);

        // Get basic info on the user from Facebook.
        $response = $serviceFb->get("/me?fields=id");
        $graphUser = $response->getGraphUser();

        /** @var SocialAccountService $serviceSocial */
        $serviceSocial = app(SocialAccountService::class);

        try {
            $socialAccount = $serviceSocial->createFaceBookSocialAccount($graphUser, $token);
        } catch (SocialAccountException $e) {
            $error = $e->getMessage();

            return redirect()->to($this->buildRedirectUrl($account, compact('error')));
        }

        return redirect()->to($this->buildRedirectUrl($account));
    }

    /**
     * Unlink FB account from SOWL account, flush FB permissions
     *
     * @param LaravelFacebookSdk $serviceFb
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function unlinkFacebookAccount(LaravelFacebookSdk $serviceFb)
    {
        /** @var Account $account */
        $account = \Auth::user();

        /** @var SocialAccount $socialAccount */
        $socialAccount = $account->getSocialAccount();

        if (!$socialAccount) {
            return  $this->jsonSuccessResponse([]);
        }

        $serviceFb->setDefaultAccessToken($socialAccount->getToken());

        try {
            $response = $serviceFb->delete("/me/permissions");
        } catch(\Exception $e) {
            \Log::error($e);
            $error = 'Failed to flush FB permissions while unlinking an account';

            return $this->jsonErrorResponse($error, JsonResponse::HTTP_FAILED_DEPENDENCY);
        }

        $status = $response->getDecodedBody();
        if ($status['success']) {
            \EntityManager::remove($socialAccount);
            \EntityManager::flush();
        } else {
            $error = 'Failed to flush FB permissions while unlinking an account';
            \Log::error($error.': FB response status is not successful');

            return $this->jsonErrorResponse($error, JsonResponse::HTTP_FAILED_DEPENDENCY);
        }

        return  $this->jsonSuccessResponse([]);
    }

    /**
     * @param Account $account
     * @param array $params
     * @return string
     */
    private function buildRedirectUrl(Account $account, $params = [])
    {
        $redirectUrl = urldecode(
            \Cache::pull("link-facebook-redirect-url-{$account->getAccountId()}", 'my-account')
        );

        $urlParts = explode('#', $redirectUrl);
        $anchor = isset($urlParts[1]) ? '#' . $urlParts[1] : '';

        $url = $urlParts[0];

        if ($params) {
            $url .= strpos($url, '?') === false ? '?' : '';
            $url .= http_build_query($params);
        }

        $url .= $anchor;

        return $url;
    }
}

