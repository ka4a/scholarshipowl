<?php
# CrEaTeD bY FaI8T IlYa
# 2016

namespace App\Policies;

use App\Entity\Account;
use App\Entity\AccountType;
use App\Rest\AbstractRestAccountPolicy;

class AccountPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param $action
     * @param Account $account
     * @param Account $entity
     * @return bool
     */
    protected function isAllowedAction($action, $account, $entity) : bool
    {
        return $account->getAccountId() == $entity ->getAccountId();
    }

    /**
     * @param Account $auth
     * @param Account $account
     *
     * @return bool
     */
    public function access($auth, $account)
    {
        return $auth === $account;
    }
}
