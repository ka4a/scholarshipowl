<?php namespace App\Policies;

use App\Entity\{Account, Application, AccountType};
use App\Rest\AbstractRestAccountPolicy;

class ApplicationPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param Account $account
     *
     * @return bool
     */
    protected function isIndexAllowed(Account $account) : bool
    {
        return true;
    }


    /**
     * @param string      $action
     * @param Account     $account
     * @param Application $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $entity->getAccount() === $account;
    }
}
