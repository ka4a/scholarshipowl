<?php namespace App\Repositories;

use App\Entities\User;
use Doctrine\ORM\EntityRepository;
use Pz\Doctrine\Rest\RestRepository;

class UserRepository extends RestRepository
{
    /**
     * @param string $email
     *
     * @return null|User
     */
    public function findByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }
}
