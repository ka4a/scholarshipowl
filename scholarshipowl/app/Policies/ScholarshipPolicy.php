<?php namespace App\Policies;

use App\Entity\Account;
use App\Entity\Scholarship;
use App\Rest\AbstractRestAccountPolicy;

class ScholarshipPolicy extends AbstractRestAccountPolicy
{
    /**
     * @param string  $action
     * @param Account $account
     * @param object  $entity
     *
     * @return bool
     */
    public function isAllowedAction($action, $account, $entity) : bool
    {
        return $action === 'show';
    }
}
