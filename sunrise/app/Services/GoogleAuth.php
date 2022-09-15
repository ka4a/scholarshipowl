<?php namespace App\Services;

use App\Entities\User;
use App\Repositories\UserRepository;

use Google_Client as Client;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\RedirectResponse;

class GoogleAuth
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var UserManager
     */
    protected $um;

    /**
     * GoogleAuth constructor.
     *
     * @param Client $client
     * @param EntityManager $em
     * @param UserManager $um
     */
    public function __construct(Client $client, EntityManager $em, UserManager $um)
    {
        $this->client = $client;
        $this->em = $em;
        $this->um = $um;
        $this->users = $em->getRepository(User::class);
    }

    /**
     * @param string $code
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function authenticate($code)
    {
        /**
         * access_token - Token
         * token_type
         * expires_in
         * id_token
         * created
         */
        $credentials = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($credentials['error'])) {
            throw new \RuntimeException(sprintf(
                'Failed google auth [%s] %s',
                $credentials['error'],
                $credentials['error_description']
            ));
        }

        // $googleUser = $this->client->getOAuth2Service()->getUsername();
        if ($this->client->getAccessToken()) {
            $service = new \Google_Service_Oauth2($this->client);

            /** @var \Google_Service_Oauth2_Userinfoplus $user */
            $userinfo = $service->userinfo->get();

            /** @var $user User */
            if (null === ($user = $this->users->findByEmail($userinfo->getEmail()))) {
                $user = $this->um->registration($userinfo->getEmail(), str_random());
            }

            if ($user->getName() === null) {
                $user->setName($userinfo->getName());
            }

            if ($user->getPicture() === null) {
                $user->setPicture($userinfo->getPicture());
            }

            $this->em->flush($user);

            return $user;
        }

        throw new \RuntimeException('Can\'t get google auth access key!');
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setClientRedirectUrl($url)
    {
        $this->client->setRedirectUri($url);
        return $this;
    }

    /**
     * @return RedirectResponse
     */
    public function redirectAuthUrl()
    {
        return redirect($this->client->createAuthUrl());
    }
}
