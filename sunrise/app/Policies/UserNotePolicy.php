<?php namespace App\Policies;

use App\Entities\User;
use Pz\LaravelDoctrine\Rest\Traits\WithRestAbilities;

class UserNotePolicy
{
    use WithRestAbilities;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function defaultRestAccess(/** @scrutinizer ignore-unused */$user)
    {
        return true;
    }
}
