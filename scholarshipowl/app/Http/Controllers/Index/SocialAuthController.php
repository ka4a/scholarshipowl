<?php namespace App\Http\Controllers\Index;

use App\Entity\SocialAccount;
use App\Http\Controllers\Rest\AuthRestController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Account\SocialAccountService;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class SocialAuthController extends Controller
{
    /**
     * @var LaravelFacebookSdk
     */
    private $fb;

    /**
     * @var SocialAccountService
     */
    private $service;

    /**
     * SocialAuthController constructor.
     */
    public function __construct()
    {
        $this->fb = app(LaravelFacebookSdk::class);
        $this->service = app(SocialAccountService::class);
    }

    public function redirect()
    {
        return redirect()->to($this->fb->getLoginUrl());
    }

    public function callback()
    {
        // Obtain an access token.
        $token = $this->fb->getAccessTokenFromRedirect();

        // Access token will be null if the user denied the request
        // or if someone just hit this URL outside of the OAuth flow.
        if (!$token) {
            // Get the redirect helper
            $helper = $this->fb->getRedirectLoginHelper();

            if (!$helper->getError()) {
                return redirect()->to("/");
            }

            return redirect()->to("/");
        }

        if (!$token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauthClient = $this->fb->getOAuth2Client();

            // Extend the access token.
            $token = $oauthClient->getLongLivedAccessToken($token);
        }

        return $this->loginOrRegisterWithFacebook($token);
    }

    /**
     * @param $token
     *
     * @return mixed
     */
    public function connect(Request $request, $token)
    {
        $default = $request->session()->get(AuthRestController::SESSION_LOGIN_REDIRECT, route('my-account'));
        $redirect = $request->get('_return', $default);
        return $this->loginOrRegisterWithFacebook($token, $redirect);
    }

    /**
     * @param string $token
     * @param string $redirect
     *
     * @return mixed
     */
    protected function loginOrRegisterWithFacebook(string $token, string $redirect = null)
    {
        try{
            $this->fb->setDefaultAccessToken($token);

            // Save for later
            \Session::put("fb_user_access_token", (string)$token);

            // Get basic info on the user from Facebook.
            $response = $this->fb->get("/me?fields=id,first_name,last_name,email,gender,birthday,location,link");

            // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
            $facebookUser = $response->getGraphUser();

            // Create the user if it does not exist or update the existing entry.
            // This will only work if you"ve added the SyncableGraphNodeTrait to your User model.
            $result = $this->service->getOrCreateAccount($facebookUser);

            // Log the user into Laravel
            if ($result["isNew"]) {
                return redirect()->to("/register#");
            }

            if ($result["account"]) {
                \Auth::login($result['account']);

                return redirect()->to($redirect ? $redirect :
                    \Session::get(AuthRestController::SESSION_LOGIN_REDIRECT, route('my-account'))
                );
            }

            return redirect()->to("/my-account#")
                ->withErrors(["error" => "Facebook already linked to another account."]);
        } catch (\Exception $e) {
            \Log::error($e);

            return redirect()->to("/");
        }
    }

    public function disconnect()
    {
        /** @var SocialAccount $socialAccount */
        $socialAccount = \Auth::user()->getSocialAccount();

        $token = \Session::get("fb_user_access_token", $socialAccount->getToken());

        $this->fb->setDefaultAccessToken($token);

        try{
            $response = $this->fb->delete("/me/permissions");
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            \Log::error('Graph returned an error: ' . $e->getMessage());
            return redirect()->to("/my-account#");
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            \Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            return redirect()->to("/my-account#");
        }


        $status = $response->getDecodedBody();

        if($status["success"]){
            \EntityManager::remove($socialAccount);
            \EntityManager::flush();
        }

        return redirect()->to("/my-account#")
            ->with("message", ["msg" => "You have successfully disconnected your Facebook account"]);
    }
}
