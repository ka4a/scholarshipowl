<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\AccountStatus;
use App\Entity\Domain;
use App\Entity\Resource\AccountResource;
use App\Entity\SocialAccount;
use App\Http\Controllers\Controller;
use App\Services\Account\AccountLoginTokenService;
use App\Services\Account\SocialAccountService;
use App\Services\DomainService;
use App\Services\FacebookService;
use App\Services\PasswordService;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use ScholarshipOwl\Minify\Providers\BaseProvider;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\Account\AccountService;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Doctrine\ORM\EntityManager;
use Hash;

class AuthRestController extends Controller
{
    use JsonResponses;

    const SESSION_LOGIN_REDIRECT = "login_redirect";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var FacebookService $facebookService
     */
    protected $facebookService;

    /**
     * @var SocialAccountService $socialAccountService
     */
    protected $socialAccountService;

    /**
     * @var DomainService
     */
    protected $domainService;

    protected $accountRepo;
    protected $socialAccountRepo;
    protected $facebookUrl = '/me?fields=id,first_name,last_name,name,email,gender,birthday,location,link';

    /** @var LaravelFacebookSdk $fb */
    protected $fb;

    /**
     * AuthRestController constructor.
     *
     * @param EntityManager  $em
     * @param AccountService $accountService
     * @param DomainService  $domainService
     * @param FacebookService $facebookService
     * @param SocialAccountService $socialAccountService
     */
    public function __construct(
        EntityManager $em,
        AccountService $accountService,
        DomainService $domainService,
        FacebookService $facebookService,
        SocialAccountService $socialAccountService
    )
    {
        $this->em = $em;
        $this->accountService = $accountService;
        $this->domainService  = $domainService;
        $this->facebookService = $facebookService;
        $this->socialAccountService = $socialAccountService;
        $this->initRepos();
    }

    protected function initRepos()
    {
        $this->accountRepo = $this->em->getRepository(Account::class);
        $this->socialAccountRepo = $this->em->getRepository(SocialAccount::class);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function session(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'email'         => $request->get('email'),
            'password'      => $request->get('password'),
            'accountStatus' => AccountStatus::ACTIVE,
            'domain'        => Domain::SCHOLARSHIPOWL,
        ];

        $defaultRedirectUrl = $request->session()->get(static::SESSION_LOGIN_REDIRECT, route('scholarships'));
        $loginRedirect = $request->session()->pull('url.intended', $defaultRedirectUrl);

        if (\Auth::attempt($credentials, $request->get('remember', false))) {
            return $this->jsonDataResponse(AccountResource::entityToArray(\Auth::user()), [
                'redirect' => $loginRedirect,
            ]);
        }

        return $this->jsonErrorResponse([
            'email'     => ['Wrong email or password!'],
            'password'  => ['Wrong email or password!'],
        ], 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email'    => 'email|required|exists:App\Entity\Account,email,domain,' . $this->domainService->get()->getId(),
            'password' => 'required'
        ]);

        /** @var Account $account */
        $account = $this->accountRepo->findOneBy([
            'email'  => $request->input('email'),
            'domain' => $this->domainService->get()->getId()
        ]);

        /* Check if password is ok */
        if (!Hash::check($request->input('password'), $account->getPassword())) {
            return $this->jsonErrorResponse('Invalid credentials.', JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            if (!$token = \JWTAuth::fromUser($account)) {
                return $this->jsonErrorResponse('Invalid credentials.', JsonResponse::HTTP_BAD_REQUEST);
            }
        } catch (JWTException $e) {
            return $this->jsonErrorResponse('Could not create token.');
        }

        return $this->jsonSuccessResponse([
            'accountId' => $account->getAccountId(),
            'token'     => $token
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticateFacebook(Request $request)
    {
        $this->validate($request, [
            'facebookToken' => 'required'
        ]);

        if (!$this->domainService->isScholarshipOwl()) {
            $this->facebookService->setApp(FacebookService::APPLYME_APP);
        }

        try {
            $facebookUser = $this->facebookService->getFacebookGraphUser($request->input('facebookToken'), $this->facebookUrl);
            $account = $this->socialAccountService->getOrCreateAccountFromApi($facebookUser, $request->input('facebookToken'));
            if (!$account) {
                $account = $this->accountService->registerFacebookAccount($facebookUser, $request->input('facebookToken'));
            }
        } catch (\Exception $e) {
            if ($e instanceof FacebookResponseException) {
                return $this->jsonErrorResponse($e->getMessage(), $e->getHttpStatusCode());
            }

            return $this->jsonErrorResponse($e->getMessage(), JsonResponse::HTTP_FAILED_DEPENDENCY);
        }

        if (!$token = \JWTAuth::fromUser($account)) {
            return $this->jsonErrorResponse('Error authorizing user.', 401);
        }

        return $this->jsonSuccessResponse([
            'accountId' => $account->getAccountId(),
            'token'     => $token
        ]);
    }

    /**
     * @param string $token Login Token
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function authenticateByMagicLink($token, Request $request)
    {
        /** @var AccountLoginTokenService $service */
        $service = app(AccountLoginTokenService::class);

        if ($token && $accountLoginToken = $service->verifyLoginToken($token, 1)) {
            // even if token is_used we allow to use it one more time during 5 minuteы to
            // avoid issues when token being used through web and app at the same time
            \Cache::store('databaseCustom')->add("once-used-magic-token-{$token}", 1, 5);
            \Auth::loginUsingId($accountLoginToken->getAccount()->getAccountId());
            $service->expireLoginToken($accountLoginToken);
            $redirectUrl = urldecode($request->get('redirect', 'my-account'));

            return \Redirect::to($redirectUrl);
        } else {
            return \Redirect::to(route('homepage'))->withErrors([
                'Magic Link invalid or expired'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Account|bool|JsonResponse
     * TODO: Remove it after all users will have social accounts
     */
    protected function createSocialAccount(Request $request)
    {
        $this->validate($request, [
            'facebookToken' => 'required'
        ]);

        if (!$this->domainService->isScholarshipOwl()) {
            $this->facebookService->setApp(FacebookService::APPLYME_APP);
        }

        try {
            $facebookUser = $this->facebookService->getFacebookGraphUser($request->input('facebookToken'), $this->facebookUrl);
            $account = \Auth::user();
            if ($account instanceof Account) {
                /** @var SocialAccount $socialAccount */
                $socialAccount = $this->socialAccountRepo->findOneBy([
                    "provider" => SocialAccount::FACEBOOK,
                    "providerUserId" => $facebookUser->getId()
                ]);

                if (!$socialAccount) {
                    $socialAccount = new SocialAccount($facebookUser->getId());
                    $socialAccount->setToken($request->input('facebookToken'));
                    $account->setSocialAccount($socialAccount);

                    $this->em->persist($socialAccount);
                    $this->em->flush();
                }
            }

            $accountResource = new AccountResource($account);
            return $this->jsonSuccessResponse($accountResource->toArray());
        } catch (FacebookResponseException $e) {
            return $this->jsonErrorResponse($e->getMessage(), $e->getHttpStatusCode());
        }
    }
}
