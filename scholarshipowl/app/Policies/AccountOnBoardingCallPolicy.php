<?php

namespace App\Policies;

use App\Contracts\HasPermission;
use App\Entity\AccountOnBoardingCall;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountOnBoardingCallPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user can view onboarding calls.
     *
     * @param HasPermission         $admin
     *
     * @return bool
     */
    public function view(HasPermission $admin)
    {
        return $admin->hasPermissionTo("account::onboarding-call.view", true);
    }

    /**
     * Check if user can update onboarding calls.
     *
     * @param HasPermission         $admin
     *
     * @return bool
     */
    public function update(HasPermission $admin)
    {
        return $admin->hasPermissionTo("account::onboarding-call.update", true);
    }
}
