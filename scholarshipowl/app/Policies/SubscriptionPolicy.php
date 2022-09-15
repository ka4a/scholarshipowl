<?php namespace App\Policies;

use App\Entity\Account;
use App\Entity\Subscription;
use App\Rest\AbstractRestAccountPolicy;

class SubscriptionPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string       $action
     * @param Account      $account
     * @param Subscription $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $entity->getAccount() === $account;
    }

    /**
     * @return bool
     */
    public function export()
    {
        return true;
    }

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
     * @param $account
     *
     * @return bool
     */
    public function index($account)
    {
        return true;
    }

    /**
     * @param Account      $account
     * @param Subscription $subscription
     *
     * @return bool
     */
    public function cancel($account, $subscription)
    {
        return $subscription->getAccount() === $account;
    }
}
