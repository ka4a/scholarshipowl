<?php namespace App\Policies;

use App\Entities\User;

class SettingsPolicy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function restIndex($user)
    {
        return $user->isRoot();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function restShow($user, /** @scrutinizer ignore-unused */ $entity)
    {
        return $user->isRoot();
    }

    /**
     * @param User $user
     * @param object $entity
     *
     * @return bool
     */
    public function restUpdate($user, /** @scrutinizer ignore-unused */ $entity)
    {
        return $user->isRoot();
    }
}
