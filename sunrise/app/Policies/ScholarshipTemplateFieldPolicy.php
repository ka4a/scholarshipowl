<?php namespace App\Policies;

use App\Entities\ScholarshipTemplateField;
use App\Entities\User;

class ScholarshipTemplateFieldPolicy
{
    public function restCreate()
    {
        return true;
    }

    /**
     * @param User $user
     * @param ScholarshipTemplateField $field
     * @return bool
     */
    public function restUpdate($user, $field)
    {
        return $this->restDelete($user, $field);
    }

    /**
     * @param User $user
     * @param ScholarshipTemplateField $field
     * @return bool
     */
    public function restDelete($user, $field)
    {
        return $user->hasOrganisationRole($field->getTemplate()->getOrganisation()->getOwnerRole());
    }
}
