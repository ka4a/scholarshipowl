<?php namespace App\Policies;

use App\Entity\Account;
use App\Entity\ApplicationFile;
use App\Rest\AbstractRestAccountPolicy;

class ApplicationFilePolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string           $action
     * @param Account          $account
     * @param ApplicationFile  $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $entity->getAccount() === $account;
    }
}
