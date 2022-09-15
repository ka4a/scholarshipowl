<?php

/**
 * Auto-generated file
 */

declare(strict_types=1);

namespace App\Policies;

use App\Entities\User;
use App\Entities\UserToken;
use Pz\LaravelDoctrine\Rest\Traits\WithRestAbilities;

class UserTokenPolicy
{
	use WithRestAbilities;

    /**
     * @param User $user
     * @return bool
     */
	public function restCreate($user)
    {
        return $user instanceof User;
    }

    /**
     * @param User      $user
     * @param UserToken $entity
     * @return bool
     */
    public function restUpdate($user, $entity)
    {
        if ($user instanceof User) {
            return $entity->getUser() === $user;
        };
        return false;
    }

    /**
     * @param User      $user
     * @param UserToken $entity
     * @return bool
     */
    public function restDelete($user, $entity)
    {
        if ($user instanceof User) {
            return $entity->getUser() === $user;
        };
        return false;
    }
}
