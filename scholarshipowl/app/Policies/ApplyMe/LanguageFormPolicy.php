<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Policies\ApplyMe;

use App\Entity\Account;
use App\Entity\ApplyMe\ApplyMeLanguageForm;
use App\Rest\AbstractRestAccountPolicy;

class LanguageFormPolicy extends AbstractRestAccountPolicy
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
     * @param ApplyMeLanguageForm $entity
     * @return bool
     */
    protected function isAllowedAction($action, $account, $entity) : bool
    {
        return true;
    }
}