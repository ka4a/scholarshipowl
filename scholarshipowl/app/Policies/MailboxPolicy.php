<?php

namespace App\Policies;

use App\Entity\Account;
use App\Services\Mailbox\Email;
use App\Rest\AbstractRestAccountPolicy;

class MailboxPolicy extends AbstractRestAccountPolicy
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
     * @param $action
     * @param Account $account
     * @param Email $entity
     * @return bool
     */
    protected function isAllowedAction($action, $account, $entity) : bool
    {
        if ($entity instanceof Email) {
            return strtolower($account->getUsername()) === $entity->getMailbox();
        }

        return $account === $entity->getAccount();
    }
}
