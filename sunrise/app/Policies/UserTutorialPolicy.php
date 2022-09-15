<?php namespace App\Policies;

use App\Entities\User;
use App\Entities\UserTutorial;
use Pz\LaravelDoctrine\Rest\Traits\WithRestAbilities;

/**
 * Class UserTutorialPolicy
 */
class UserTutorialPolicy
{
    use WithRestAbilities;

    /**
     * @param User $user
     * @param UserTutorial $entity
     *
     * @return bool
     */
    public function restShow($user, $entity)
    {
        return $entity->getUser() === $user;
    }

    /**
     * @param User $user
     * @param UserTutorial $entity
     *
     * @return bool
     */
    public function restUpdate($user, $entity)
    {
        return $entity->getUser() === $user;
    }
}
