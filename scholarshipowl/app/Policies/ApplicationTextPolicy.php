<?php namespace App\Policies;

use App\Entity\ApplicationText;
use App\Rest\AbstractRestAccountPolicy;

class ApplicationTextPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string              $action
     * @param \App\Entity\Account $account
     * @param ApplicationText     $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $entity->getAccount() === $account;
    }
}
