<?php namespace App\Bridge\Passport;

use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Hashing\Hasher;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Hasher
     */
    protected $hasher;

    /**
     * @var \App\Repositories\UserRepository
     */
    protected $repository;

    /**
     * UserRepository constructor.
     *
     * @param EntityManager $em
     * @param Hasher $hasher
     */
    public function __construct(EntityManager $em, Hasher $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
        $this->repository = $em->getRepository(User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        if ($user = $this->repository->findByEmail($username)) {
            if ($this->hasher->check($password, $user->getAuthPassword())) {
                return $user;
            }
        }

        return null;
    }
}
