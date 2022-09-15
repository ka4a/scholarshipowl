<?php namespace App\Policies;

use App\Entity\ApplicationImage;
use App\Rest\AbstractRestAccountPolicy;

class ApplicationSpecialEligibilityPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string              $action
     * @param \App\Entity\Account $account
     * @param ApplicationImage    $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $entity->getAccount() === $account;
    }
}
