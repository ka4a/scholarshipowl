<?php namespace App\Policies;

use App\Entity\SocialAccount;
use App\Rest\AbstractRestAccountPolicy;

class SocialAccountPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string              $action
     * @param \App\Entity\Account $account
     * @param SocialAccount       $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $action === 'show' && $account === $entity->getAccount();
    }
}
