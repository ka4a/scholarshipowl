<?php namespace App\Policies;

use App\Entity\Account;
use App\Entity\Profile;
use App\Rest\AbstractRestAccountPolicy;

class ProfilePolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string              $action
     * @param \App\Entity\Account $account
     * @param Profile             $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return in_array($action, ['show', 'update']) && $entity->getAccount() === $account;
    }

    /**
     * @param Account $account
     *
     * @return bool
     */
    protected function isStoreAllowed(Account $account) : bool
    {
        return false;
    }
}
