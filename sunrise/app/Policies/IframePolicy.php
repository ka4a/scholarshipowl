<?php

/**
 * Auto-generated file
 */

declare(strict_types=1);

namespace App\Policies;

use App\Entities\Iframe;
use App\Entities\User;
use Pz\LaravelDoctrine\Rest\Traits\WithRestAbilities;

class IframePolicy
{
	use WithRestAbilities;

    /**
     * @param $user
     * @return bool
     */
	public function restCreate($user)
    {
        return $user instanceof User;
    }

    /**
     * @param $user
     * @param $entity
     * @return bool
     */
    public function restShow($user, $entity)
    {
        return $this->canEditIframe($user, $entity);
    }

    /**
     * @param $user
     * @param $entity
     * @return bool
     */
    public function restUpdate($user, $entity)
    {
        return $this->canEditIframe($user, $entity);
    }

    /**
     * @param $user
     * @param $entity
     * @return bool
     */
    public function restDelete($user, $entity)
    {
        return $this->canEditIframe($user, $entity);
    }

    /**
     * @param User      $user
     * @param Iframe    $iframe
     * @return bool
     */
    protected function canEditIframe($user, $iframe)
    {
        if ($user instanceof User) {
            $organisation = $iframe->getTemplate()->getOrganisation();
            return $user->hasOrganisationRole($organisation->getOwnerRole());
        }
        return false;
    }
}
