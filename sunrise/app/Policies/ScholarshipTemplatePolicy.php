<?php namespace App\Policies;

use App\Entities\ScholarshipTemplate;
use App\Entities\User;

class ScholarshipTemplatePolicy
{
    /**
     * @param User $user
     * @return bool
     */
    public function restCreate($user)
    {
        return true;
    }

    /**
     * @param User $user
     * @param ScholarshipTemplate $template
     * @return bool
     */
    public function restUpdate($user, $template)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole($template->getOrganisation()->getOwnerRole());
        }

        return false;
    }

    /**
     * @param User  $user
     * @param ScholarshipTemplate $template
     * @return bool
     */
    public function restShow($user, $template)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole($template->getOrganisation()->getOwnerRole());
        }

        return false;
    }

    /**
     * @param User                  $user
     * @param ScholarshipTemplate   $template
     *
     * @return bool
     */
    public function restDelete($user, $template)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole($template->getOrganisation()->getOwnerRole());
        }

        return false;
    }
}
