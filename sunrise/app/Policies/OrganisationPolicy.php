<?php namespace App\Policies;

use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\User;

class OrganisationPolicy
{
    /**
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    public function showOwners($user, $organisation)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole($organisation->getOwnerRole());
        }

        return false;
    }

    /**
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    public function relatedWinners($user, $organisation)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole($organisation->getOwnerRole());
        }

        return false;
    }

    /**
     * @param User|Organisation $user
     * @param Organisation $organisation
     * @return bool
     */
    public function restShow($user, $organisation)
    {
        if ($user instanceof Organisation) {
            return $user === $organisation;
        }

        if ($user instanceof User) {
            return $user->belongsToOrganisation($organisation);
        }

        return false;
    }

    /**
     * @param User|Organisation $user
     * @param Organisation $organisation
     * @return bool
     */
    public function restUpdate($user, $organisation)
    {
        if ($user instanceof Organisation) {
            return $user === $organisation;
        }

        if ($user instanceof User) {
            return !empty(
                $user->getOrganisationRoles()
                    ->filter(function(OrganisationRole $role) use ($organisation) {
                        return $role->isOwner() && $role->getOrganisation() === $organisation;
                    })
            );
        }

        return false;
    }

    /**
     * @param $user
     * @param $organisation
     * @return bool
     */
    public function relatedScholarships($user, $organisation)
    {
        if ($user instanceof Organisation) {
            return $user === $organisation;
        }

        if ($user instanceof User) {
            return $user->belongsToOrganisation($organisation);
        }

        return false;
    }

    /**
     * Allow create scholarship if user in organisation.
     *
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    public function createScholarship($user, $organisation)
    {
        if ($user instanceof Organisation) {
            return $user === $organisation;
        }

        if ($user instanceof User && $organisation instanceof Organisation) {
            return $user->belongsToOrganisation($organisation);
        }

        return false;
    }
}
