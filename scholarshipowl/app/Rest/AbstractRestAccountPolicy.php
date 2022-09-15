<?php namespace App\Rest;

use App\Entity\Account;

abstract class AbstractRestAccountPolicy
{
    /**
     * @param string  $action
     * @param Account $account
     * @param object  $entity
     *
     * @return bool
     */
    abstract protected function isAllowedAction($action, $account, $entity) : bool;

    /**
     * @param $account
     *
     * @return bool
     */
    public function index($account)
    {
        return $this->isAccount($account) && $this->isIndexAllowed($account);
    }

    /**
     * @param $account
     * @param $entity
     *
     * @return bool
     */
    public function store($account)
    {
        return $this->isAccount($account) && $this->isStoreAllowed($account);
    }

    /**
     * @param $account
     * @param $entity
     *
     * @return bool
     */
    public function show($account, $entity = null)
    {
        return $this->isAccount($account) && $this->isAllowedAction('show', $account, $entity);
    }

    /**
     * @param $account
     * @param $entity
     *
     * @return bool
     */
    public function update($account, $entity = null)
    {
        return $this->isAccount($account) && $this->isAllowedAction('update', $account, $entity);
    }

    /**
     * @param $account
     * @param $entity
     *
     * @return bool
     */
    public function destroy($account, $entity = null)
    {
        return $this->isAccount($account) && $this->isAllowedAction('destroy', $account, $entity);
    }

    /**
     * @param $account
     *
     * @return bool
     */
    protected function isAccount($account)
    {
        return $account instanceof Account;
    }

    /**
     * @param Account $account
     *
     * @return bool
     */
    protected function isStoreAllowed(Account $account) : bool
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
        return false;
    }
}
