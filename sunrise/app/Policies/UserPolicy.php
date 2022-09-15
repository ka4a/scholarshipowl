<?php namespace App\Policies;

use App\Entities\User;
use App\Permission;
use Pz\LaravelDoctrine\Rest\Traits\WithRestAbilities;

class UserPolicy
{
    use WithRestAbilities;

    /**
     * Allow to see user only to itself.
     *
     * @param User $user
     * @param User $entity
     * @return bool
     */
    public function restShow($user, $entity)
    {
        return $user === $entity;
    }

    /**
     * Allow to see update it self.
     *
     * @param User $user
     * @param User $entity
     * @return bool
     */
    public function restUpdate($user, $entity)
    {
        return $user === $user;
    }
}
