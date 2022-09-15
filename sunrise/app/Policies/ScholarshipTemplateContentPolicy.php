<?php namespace App\Policies;

use App\Entities\ScholarshipTemplateContent;
use App\Entities\User;

class ScholarshipTemplateContentPolicy
{
    /**
     * @param User  $user
     * @param ScholarshipTemplateContent $content
     * @return bool
     */
    public function restShow($user, $content)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole($content->getTemplate()->getOrganisation()->getOwnerRole());
        }

        return false;
    }

    /**
     * @param User  $user
     * @param ScholarshipTemplateContent $content
     * @return bool
     */
    public function restUpdate($user, $content)
    {
        if ($user instanceof User) {
            return $user->hasOrganisationRole($content->getTemplate()->getOrganisation()->getOwnerRole());
        }

        return false;
    }
}
