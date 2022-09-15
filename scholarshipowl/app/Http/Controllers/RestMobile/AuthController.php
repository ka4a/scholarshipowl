<?php namespace App\Http\Controllers\RestMobile;

use App\Entity\Account;
use App\Entity\AccountStatus;
use App\Entity\Domain;
use App\Entity\Repository\AccountRepository;
use App\Entity\Resource\AccountResource;
use App\Entity\SocialAccount;
use App\Http\Controllers\Controller;
use App\Services\Account\AccountLoginTokenService;
use App\Services\DomainService;
use App\Services\FacebookService;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\Account\AccountService;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Doctrine\ORM\EntityManager;
use Hash;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
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
     * @var DomainService
     */
    protected $domainService;

    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var string
     */
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
     */
    public function __construct(
        EntityManager $em,
        AccountService $accountService,
        DomainService $domainService,
        FacebookService $facebookService
    )
    {
        $this->em = $em;
        $this->accountService = $accountService;
        $this->domainService  = $domainService;
        $this->facebookService = $facebookService;
        $this->initRepos();
    }

    protected function initRepos()
    {
        $this->accountRepository = $this->em->getRepository(Account::class);
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

        $loginRedirect = $request->session()->get(static::SESSION_LOGIN_REDIRECT, route('scholarships'));
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
            'email'    => 'email|required|exists:App\Entity\Account,email',
            'password' => 'required'
        ]);

        /** @var Account $account */
        $account = $this->accountRepository->findOneBy([
            'email'  => $request->input('email')
        ]);

        /* Check if password is ok */
        if (!Hash::check($request->input('password'), $account->getPassword())) {
            return $this->jsonErrorResponse('Invalid credentials.', JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            if (!$token = \JWTAuth::fromUser($account)) {
                return $this->jsonErrorResponse('Failed to create JWT token.', JsonResponse::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->jsonErrorResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->jsonSuccessResponse([
            'accountId' => (int)$account->getAccountId(),
            'token' => $token
        ]);
    }

    /**
     * Exchange one-time token to JWT token
     *
     * @see one-time token generated in ProfileRestController:update()
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticateByOneTimeToken(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string'
        ]);

        $accountId = \Cache::pull($request->get('token'));

        if (!$accountId) {
            return $this->jsonErrorResponse('One-time token not valid or expired.', JsonResponse::HTTP_UNAUTHORIZED);
        }

        /** @var Account $account */
        $account = $this->accountRepository->find($accountId);

        try {
            if (!$token = \JWTAuth::fromUser($account)) {
                return $this->jsonErrorResponse('Could not create token.', JsonResponse::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return $this->jsonErrorResponse('Could not create token.', JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->jsonSuccessResponse([
            'accountId' => (int)$accountId,
            'token' => $token
        ]);
    }

    /**
     * Authenticates a user by JWT token, creates session and makes redirect to specified url.
     *
     * @param Request $request
     * @param JWTAuth $auth
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticateAndRedirect(Request $request, JWTAuth $auth)
    {
        if (!$auth->parser()->setRequest($request)->hasToken()) {
            throw new UnauthorizedHttpException('jwt-auth', 'Token not provided.');
        }

        try {
            if (!$auth->parseToken()->authenticate()) {
                throw new UnauthorizedHttpException('jwt-auth', 'User not found.');
            }
        } catch (JWTException $e) {
           throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        }

        Auth::login(Auth::user());

        return redirect()->to($request->get('redirect', '/'));
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

        try {
            $graphUser = $this->facebookService->getFacebookGraphUser(
                $request->input('facebookToken'), $this->facebookUrl
            );

            /** @var Account $account */
            $account = $this->accountRepository->findOneBy([
                'email' => $graphUser->getEmail(),
                'domain'=> Domain::SCHOLARSHIPOWL
            ]);

            if (!$account) {
                /** @var SocialAccount $socialAccount */
                $socialAccount = \EntityManager::getRepository(SocialAccount::class)->findOneBy([
                    "provider"      => SocialAccount::FACEBOOK,
                    "providerUserId" => $graphUser->getId()
                ]);

                if ($socialAccount) {
                    $account = $socialAccount->getAccount();
                }

                if (!$account) {
                    return $this->jsonErrorResponse(
                        'Account matching facebook credentials is not found on SOWL side.',
                        JsonResponse::HTTP_FAILED_DEPENDENCY
                    );
                }
            }

            if (!$token = \JWTAuth::fromUser($account)) {
                return $this->jsonErrorResponse('Failed to create JWT token.', JsonResponse::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            if ($e instanceof FacebookResponseException) {
                return $this->jsonErrorResponse($e->getMessage(), $e->getHttpStatusCode());
            }

            return $this->jsonErrorResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->jsonSuccessResponse([
            'accountId' => (int)$account->getAccountId(),
            'token' => $token
        ]);
    }

    /**
     * @param  string $token Login Token
     * @return JsonResponse
     */
    public function authenticateByMagicLink(Request $request)
    {
        $token = $request->get('token');

        /** @var AccountLoginTokenService $service */
        $service = app(AccountLoginTokenService::class);

        if ($token) {
            // we can not fetch token with Doctrine, because account record is fetched by relation has extra properties
            // which make JWT payload invalid
            $tokenRecord = \DB::selectOne('
                select * from account_login_token
                where token = :token and created_at > :expirationDate',
                [
                    'token' => $token,
                    'expirationDate' => Carbon::now()->subDays(1)
                ]
            );

            // even if token is_used we allow to use it one more time during 1 minute to
            // avoid issues when token being used through web and app at the same time
            $isTokenOk = false;
            if ($tokenRecord && $tokenRecord->is_used == 1) {
                if (\Cache::store('databaseCustom')->pull("once-used-magic-token-{$token}")) {
                    $isTokenOk = true;
                }
            } else if ($tokenRecord && $tokenRecord->is_used == 0) {
                $isTokenOk = true;
            }

            if ($isTokenOk) {
                /** @var Account $account */
                $account = $this->accountRepository->findOneBy([
                    'accountId' => $tokenRecord->account_id
                ]);

                try {
                    if (!$jwtToken = \JWTAuth::fromUser($account)) {
                        return $this->jsonErrorResponse('Failed to create JWT token.', JsonResponse::HTTP_BAD_REQUEST);
                    }
                } catch (\Exception $e) {
                    return $this->jsonErrorResponse('Failed to create JWT token.', JsonResponse::HTTP_BAD_REQUEST);
                }

                $accountLoginToken = $service->verifyLoginToken($token);
                if ($accountLoginToken) {
                    $service->expireLoginToken($accountLoginToken);
                }

                return $this->jsonSuccessResponse([
                    'accountId' => (int)$account->getAccountId(),
                    'token' => $jwtToken
                ]);
            } else {
                return $this->jsonErrorResponse('Token invalid or expired.', JsonResponse::HTTP_UNAUTHORIZED);
            }
        } else {
            return $this->jsonErrorResponse('Token invalid or expired.', JsonResponse::HTTP_UNAUTHORIZED);
        }
    }
}
