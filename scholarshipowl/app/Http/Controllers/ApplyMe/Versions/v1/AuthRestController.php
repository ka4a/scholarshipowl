<?php namespace App\Http\Controllers\ApplyMe\Versions\v1;

use App\Entity\Account;
use App\Entity\AccountStatus;
use App\Entity\Resource\AccountResource;
use App\Http\Controllers\Controller;
use App\Services\DomainService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\Account\AccountService;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Doctrine\ORM\EntityManager;
use Hash;

class AuthRestController extends Controller
{
    use JsonResponses;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var DomainService
     */
    protected $domainService;
    protected $accountRepo;
    protected $facebookUrl = 'https://graph.facebook.com/me?access_token=';
    protected $curl = null;

    /**
     * AuthRestController constructor.
     *
     * @param EntityManager  $em
     * @param AccountService $accountService
     * @param DomainService  $domainService
     */
    public function __construct(EntityManager $em, AccountService $accountService, DomainService $domainService)
    {
        $this->em = $em;
        $this->accountService = $accountService;
        $this->domainService  = $domainService;
        $this->initRepos();
    }

    protected function initRepos()
    {
        $this->accountRepo = $this->em->getRepository(Account::class);
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
            'accountStatus' => AccountStatus::ACTIVE
        ];

        if (\Auth::attempt($credentials, $request->get('remember', false))) {
            $account = new AccountResource(\Auth::user());
            return $this->jsonDataResponse($account->toArray());
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

    /*
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function authenticateFacebook(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required'
        ]);

        if (!$fbToken = $request->header('fb-access-token')) {
            return $this->jsonErrorResponse('Access Token required.', 400);
        }
        // Init curl
        $ch = $this->curlInit($fbToken);
        // Send request to validate token
        $output = $this->curlSend();

        // If token is invalid
        if (!$output) {
            return $this->jsonErrorResponse('Error authorizing user via token.', 401);
        }

        /** @var Account $account */
        $account = $this->accountRepo->findOneBy([
            'email'  => $request->input('email'),
            'domain' => $this->domainService->get()->getId()
        ]);

        if ($account === null) {
            $account = $this->accountService->registerAccount($request->all(), AccountService::VALIDATE_REGISTER_FACEBOOK);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidateToken()
    {
        try {
            \JWTAuth::parseToken()->invalidate(true);
        } catch (JWTException $e) {
            return $this->jsonErrorResponse($e->getMessage());
        }

        return $this->jsonSuccessResponse();
    }

    /**
     * @param string $fbToken
     */
    protected function curlInit(string $fbToken)
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->facebookUrl . $fbToken);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * @return bool
     */
    protected function curlSend(): bool
    {
        curl_exec($this->curl);
        if (curl_getinfo($this->curl, CURLINFO_HTTP_CODE) !== 200) {
            curl_close($this->curl);

            return false;
        } else {
            curl_close($this->curl);

            return true;
        }
    }
}
