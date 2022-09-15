<?php namespace App\Policies;

use App\Entity\OnesignalAccount;
use App\Rest\AbstractRestAccountPolicy;

class OnesignalAccountPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string                $action
     * @param \App\Entity\Account   $account
     * @param OnesignalAccount      $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $account === $entity->getAccount();
    }
}
