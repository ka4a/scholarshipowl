<?php namespace App\Policies;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Rest\AbstractRestAccountPolicy;

class AccountFilePolicy extends AbstractRestAccountPolicy
{
    /**
     * @param $account
     *
     * @return bool
     */
    public function isIndexAllowed(Account $account) : bool
    {
        return true;
    }

    /**
     * @param             $action
     * @param Account     $account
     * @param AccountFile $entity
     *
     * @return bool
     */
    protected function isAllowedAction($action, $account, $entity) : bool
    {
        return $entity->getAccount() === $account;
    }
}
