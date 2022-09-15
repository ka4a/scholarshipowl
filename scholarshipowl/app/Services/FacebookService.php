<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\SocialAccount;
use Doctrine\ORM\EntityManager;
use Facebook\Exceptions\FacebookResponseException;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookService
{
    const SCHOLARSHIPOWL_APP = 1;
    const APPLYME_APP = 2;

    /**
     * @var LaravelFacebookSdk
     */
    protected $sdk;

    /**
     * @var EntityManager
     */
    protected $em;

    protected $url = '/me?fields=id,first_name,last_name,name,email,gender,birthday,location,link';

    /**
     * FacebookService constructor.
     *
     * @param LaravelFacebookSdk $sdk
     * @param EntityManager $em
     */
    public function __construct(LaravelFacebookSdk $sdk, EntityManager $em)
    {
        $this->sdk = $sdk;
        $this->em = $em;
    }

    /**
     * @param $app
     */
    public function setApp($app)
    {
        if ($app == self::APPLYME_APP) {
            $this->sdk = $this->sdk->newInstance(config('laravel-facebook-sdk.applyme_facebook_config'));
        }
    }


    /**
     * @param string $facebookToken
     * @param string|null $link
     * @return \Facebook\GraphNodes\GraphUser
     * @throws FacebookResponseException
     */
    public function getFacebookGraphUser(string $facebookToken, string $link = null)
    {
        $this->sdk->setDefaultAccessToken($facebookToken);
        // Get basic info on the user from Facebook.
        $response = $this->sdk->get($link ?? $this->url);
        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        $facebookUser = $response->getGraphUser();

        return $facebookUser;
    }

    /**
     * @param Account $account
     *
     * @return null|string
     */
    public function getAccountFBLink(Account $account)
    {
        if ($fb = $this->getFBAccount($account)) {
            return $fb->getLink();
        }

        return null;
    }

    /**
     * @param Account $account
     *
     * @return null|SocialAccount
     */
    public function getFBAccount(Account $account)
    {
        return $this->em->getRepository(SocialAccount::class)
            ->findOneBy([
                'provider' => SocialAccount::FACEBOOK,
                'account' => $account,
            ]);
    }
}
