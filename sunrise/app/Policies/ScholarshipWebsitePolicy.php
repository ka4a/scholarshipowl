<?php namespace App\Policies;

use App\Entities\Organisation;
use App\Entities\User;

class ScholarshipWebsitePolicy
{
    /**
     * @param User|Organisation $user
     * @return bool
     */
    public function restCreate($user)
    {
        if ($user instanceof Organisation) {
            return true;
        }

        return false;
    }
}
