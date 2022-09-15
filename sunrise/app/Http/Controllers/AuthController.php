<?php namespace App\Http\Controllers;

use App\Entities\Passport\OauthClient;
use App\Entities\User;
use App\Entities\UserSocial;
use App\Services\UserManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\SocialiteManager;
use Pz\Doctrine\Rest\RestResponse;

class AuthController extends Controller
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserManager
     */
    protected $um;

    /**
     * AuthController constructor.
     *
     * @param EntityManager $em
     * @param UserManager $um
     */
    public function __construct(EntityManager $em, UserManager $um)
    {
        $this->em = $em;
        $this->um = $um;
    }

    /**
     * Create new user and then redirect request to Passport password grant action.
     *
     * @param Request $request
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function registration(Request $request)
    {
        $this->validate($request, [
            'email'             => 'required|email|max:255|unique:'.User::class.',email',
            'password'          => 'required|min:6|max:255',
        ]);

        $this->um->registration(
            $request->get('email'),
            $request->get('password')
        );

        $client = OauthClient::password();

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->getId(),
            'client_secret' => $client->getSecret(),
            'username'      => $request->get('email'),
            'password'      => $request->get('password'),
            'scope'         => null,
        ]);

        return Route::dispatch(Request::create('oauth/token', 'POST'));
    }

    /**
     * Redirect request to Passport password grant action.
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'             => 'required',
            'password'          => 'required',
        ]);

        $client = OauthClient::password();

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->getId(),
            'client_secret' => $client->getSecret(),
            'username'      => $request->get('email'),
            'password'      => $request->get('password'),
            'scope'         => null,
        ]);

        return Route::dispatch(Request::create('oauth/token', 'POST'));
    }

    /**
     * Revoke all passport tokens.
     *
     * @param Request $request
     * @return RestResponse
     */
    public function logout(Request $request)
    {
        /** @var User $user */
        if ($user = $request->user()) {
            $user->token()->revoke();
        }

        return RestResponse::create();
    }

    /**
     * Auth redirect path for google.
     *
     * @param Request $request
     * @return \Psr\Http\Message\StreamInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function googleAuth(Request $request)
    {
        $data = $this->validate($request, [
            'redirectUri' => 'required|url',
            'code' => 'required',
        ]);

        /** @var SocialiteManager $social */
        $social = app(Factory::class);

        $socialUser = $social->driver(UserSocial::GOOGLE_PROVIDER)
            ->redirectUrl($data['redirectUri'])
            ->stateless()
            ->user();

        $userSocial = $this->um->updateSocialUser(UserSocial::GOOGLE_PROVIDER, $socialUser);

        $client = OauthClient::personal();

        $request->request->add([
            'grant_type'    => 'personal_access',
            'client_id'     => $client->getId(),
            'client_secret' => $client->getSecret(),
            'user_id'       => $userSocial->getUser()->getId(),
            'scope'         => '*',
        ]);

        return Route::dispatch(Request::create('oauth/token', 'POST'));
    }
}
