<?php namespace App\Policies;

use App\Entity\Account;
use App\Entity\EssayFiles;
use App\Rest\AbstractRestAccountPolicy;

class EssayFilesPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param            $action
     * @param Account    $account
     * @param EssayFiles $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $entity->getFile() ? $entity->getFile()->getAccount() === $account : false;
    }
}
