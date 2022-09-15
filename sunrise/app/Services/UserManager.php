<?php namespace App\Services;

use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\User;
use App\Entities\UserSocial;
use App\Entities\UserTutorial;
use Doctrine\ORM\EntityManager;
use Laravel\Socialite\Two\User as SocialUser;
use Illuminate\Support\Facades\Hash;

class UserManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * UserManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registration(string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(Hash::make($password));

        $organisation = new Organisation();
        $user->addOrganisationRoles(
            $organisation->getRoles()
                ->filter(function(OrganisationRole $role) { return $role->isOwner(); })
                ->first()
        );

        $tutorial = new UserTutorial();
        $tutorial->setUser($user);

        $this->em->persist($user);
        $this->em->persist($organisation);
        $this->em->persist($tutorial);

        $this->em->flush([$user, $organisation, $tutorial]);

        return $user;
    }

    /**
     * Update current social user.
     *
     * @param string $provider
     * @param SocialUser $socialUser
     * @return UserSocial
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSocialUser(string $provider, SocialUser $socialUser): UserSocial
    {
        $userSocial = $this->em->getRepository(UserSocial::class)
            ->findOneBy(['externalId' => $socialUser->getId(), 'provider' => $provider]);

        if (is_null($userSocial)) {
            $userSocial = new UserSocial();
            $userSocial->setExternalId($socialUser->getId());
            $userSocial->setProvider($provider);
            $this->em->persist($userSocial);
        }

        $userSocial->setToken($socialUser->token);

        if ($socialUser->refreshToken) {
            $userSocial->setRefreshToken($socialUser->refreshToken);
        }

        if ($userSocial->getUser() === null) {

            $user = $this->em->getRepository(User::class)
                ->findOneBy(['email' => $socialUser->getEmail()]);

            if (is_null($user)) {
                $user = $this->registration($socialUser->getEmail(), str_random());
            }

            $userSocial->setUser($user);

        }

        if ($userSocial->getUser()->getName() === null) {
            $userSocial->getUser()->setName($socialUser->getName());
        }

        if ($userSocial->getUser()->getPicture() === null) {
            $userSocial->getUser()->setPicture($socialUser->getAvatar());
        }

        $this->em->flush();

        return $userSocial;
    }
}
