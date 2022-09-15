<?php

namespace App\Policies;

use App\Entities\User;
use App\Permission;
use Pz\LaravelDoctrine\Rest\Traits\WithRestAbilities;

class RolePolicy
{
    use WithRestAbilities;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function defaultRestAccess($user)
    {
        return $user->hasPermissionTo(Permission::ACL) && $user->hasPermissionTo(Permission::ACL_ROLES);
    }
}
