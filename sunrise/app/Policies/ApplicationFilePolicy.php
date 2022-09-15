<?php namespace App\Policies;

use App\Entities\ApplicationFile;
use App\Entities\Organisation;
use App\Entities\User;

class ApplicationFilePolicy
{
    /**
     * @param User|Organisation $user
     * @return bool
     */
    public function restCreate($user)
    {
        return true;
    }

    /**
     * @param User              $user
     * @param ApplicationFile   $entity
     * @return bool
     */
    public function restShow($user, $entity)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole(
                $entity->getApplication()->getScholarship()->getTemplate()->getOrganisation()->getOwnerRole()
            );
        }

        return false;
    }
}
