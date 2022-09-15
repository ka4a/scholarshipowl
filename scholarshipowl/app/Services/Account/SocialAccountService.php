<?php

namespace App\Services\Account;

use App\Entity\Account;
use App\Entity\Domain;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\EntityRepository;
use App\Entity\SocialAccount;
use App\Services\Account\Exception\SocialAccountException;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphUser;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Dompdf\Exception;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class SocialAccountService
{
    const SESSION_PREFIX = "REGISTER";

    /**
     * @var LaravelFacebookSdk
     */
    private $facebook;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $social;

    /**
     * @var AccountRepository
     */
    private $account;

    /**
     * SocialAccountService constructor.
     *
     * @param LaravelFacebookSdk    $facebook
     * @param EntityManager         $em
     */
    public function __construct(LaravelFacebookSdk $facebook, EntityManager $em)
    {
        $this->facebook = $facebook;
        $this->em = $em;
        $this->social = $em->getRepository(SocialAccount::class);
        $this->account = $em->getRepository(Account::class);
    }

    /**
     * @param Account        $account
     * @param GraphUser|null $graphUser
     *
     * @return SocialAccount
     */
    public function linkAccounts(Account $account = null, GraphUser $graphUser = null)
    {
        if ($account === null && !(($account = \Auth::user()) instanceof Account)) {
            throw new \InvalidArgumentException('Missing account parameter!');
        }

        if ($account->getSocialAccount()) {
            throw new \RuntimeException('User already connected to social account!');
        }

        if (!($token = \Session::get('fb_user_access_token'))) {
            return null;
        }

        if ($graphUser === null) {
            $this->facebook->setDefaultAccessToken($token);
            $response = $this->facebook->get('/me?fields=id,first_name,last_name,email,gender,birthday,location,link');
            $graphUser = $response->getGraphUser();
        }

        if ($this->social->findOneBy(['provider' => 'facebook', 'providerUserId' => $graphUser->getId()])) {
            throw new \RuntimeException('Social account already connected to some account!');
        }

        $socialAccount = new SocialAccount($graphUser->getId());
        $socialAccount->setToken(\Session::get('fb_user_access_token'));

        $account->setSocialAccount($socialAccount);

        $this->em->persist($socialAccount);
        $this->em->flush();

        return $socialAccount;
    }


    /**
     * Get or create account for users coming from social networks
     *
     * @param \Facebook\GraphNodes\GraphUser $graphUser
     *
     * @return array
     */
    public function getOrCreateAccount(GraphUser $graphUser)
    {
        /** @var SocialAccount $socialAccount */
        $socialAccount = $this->social->findOneBy([
            "provider"      => SocialAccount::FACEBOOK,
            "providerUserId" => $graphUser->getId()
        ]);

        /** @var Account $account */
        if (($account = \Auth::user()) instanceof Account) {
            if (!$socialAccount) {
                $socialAccount = new SocialAccount($graphUser->getId());
                $socialAccount->setToken(\Session::get("fb_user_access_token"));

                $account->setSocialAccount($socialAccount);

                $this->em->persist($socialAccount);
                $this->em->flush();

                return [
                    'isNew' => false,
                    'account' => $account,
                ];
            } elseif ($account !== $socialAccount->getAccount()) {
                return ['isNew' => false, 'account' => null];
            }
        }

        if ($socialAccount) {
            return ['isNew' => false, 'account' => $socialAccount->getAccount()];
        }

        $this->setRegistrationData($graphUser);

        return ['isNew' => true, 'account' => null];
    }

    /**
     * Create social account linked to Facebook if not exists
     *
     * @param GraphUser $graphUser
     * @param string $fbToken
     * @return SocialAccount
     * @throws SocialAccountException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws SocialAccountException
     */
    public function createFaceBookSocialAccount(GraphUser $graphUser, string $fbToken)
    {
        /** @var Account $account */
        $account = \Auth::user();
        if (!$account) {
            throw new SocialAccountException('You must be logged in to link a social account');
        }

        if ($account->getSocialAccount()) {
            throw new SocialAccountException('Your account already linked to Facebook');
        }

        /** @var SocialAccount $socialAccount */
        $socialAccount = $this->social->findOneBy([
            "provider" => SocialAccount::FACEBOOK,
            "providerUserId" => $graphUser->getId()
        ]);

        if ($socialAccount) {
            if ($account && $account->getAccountId() != $socialAccount->getAccount()->getAccountId()) {
                throw new SocialAccountException('Your Facebook account is already linked to another ScholarshipOwl account');
            }

            return $socialAccount;
        }

        $socialAccount = new SocialAccount($graphUser->getId());
        $socialAccount->setToken($fbToken);
        $account->setSocialAccount($socialAccount);

        $this->em->persist($socialAccount);
        $this->em->flush();

        return $socialAccount;
    }


    /**
     * @param GraphUser $graphUser
     * @param string $facebookToken
     * @return Account|bool
     * @throws AccessDeniedException
     */
    public function getOrCreateAccountFromApi(GraphUser $graphUser, string $facebookToken, int $domain = Domain::APPLYME)
    {
        /** @var SocialAccount $socialAccount */
        $socialAccount = $this->social->findOneBy([
            "provider" => SocialAccount::FACEBOOK,
            "providerUserId" => $graphUser->getId()
        ]);

        if (!$socialAccount) {
            if(null == $graphUser->getEmail()) {
                throw new AccessDeniedException('Access denied', 403);
            }

            /** @var Account $account */
            if ($account = $this->account->findByEmail($graphUser->getEmail(), $domain)) {
                $socialAccount = $account->getSocialAccount();
                // Some extra case
                if ($socialAccount == null) {
                    $socialAccount = new SocialAccount($graphUser->getId());
                    $socialAccount->setToken($facebookToken);
                    $account->setSocialAccount($socialAccount);

                    $this->em->persist($socialAccount);
                    $this->em->flush();
                } else {
                    $socialAccount->setProviderUserId($graphUser->getId());
                    $socialAccount->setLink();
                    $socialAccount->setToken($facebookToken);
                    $this->em->flush();
                }

                return $account;
            } else {
                return false;
            }
        } else {
            return $socialAccount->getAccount();
        }
    }

    /**
     * Set user info to session for field prepopulation
     *
     * @param GraphUser $user
     */
    private function setRegistrationData(GraphUser $user)
    {
        foreach ($user->all() as $key => $value) {
            if ($key == "location") {
                $key = "city";
                $value = $value->getField("name");
            }

            if ($key == "birthday") {
                $dt = Carbon::createFromTimestamp($value->getTimestamp());
                $key = sprintf("%s.%s", self::SESSION_PREFIX, "birthday_month");
                \Session::put($key, $dt->month);
                $key = sprintf("%s.%s", self::SESSION_PREFIX, "birthday_day");
                \Session::put($key, $dt->day);
                $key = sprintf("%s.%s", self::SESSION_PREFIX, "birthday_year");
                \Session::put($key, $dt->year);
                continue;
            }

            \Session::put(sprintf("%s.%s", self::SESSION_PREFIX, $key), $value);
        }
    }
}
