<?php namespace App\Policies;

use App\Entity\{Account, AccountType};
use App\Entity\ApplicationEssay;
use App\Rest\AbstractRestAccountPolicy;

class ApplicationEssayPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param                  $action
     * @param Account          $account
     * @param ApplicationEssay $entity
     *
     * @return bool
     */
    protected function isAllowedAction($action, $account, $entity) : bool
    {
        return $account ->getAccountType() ->getId() == AccountType::ADMINISTRATOR ? true :
            $account ->getAccountId() == $entity -> getAccount() ->getAccountId() ? true : false;
    }
}
