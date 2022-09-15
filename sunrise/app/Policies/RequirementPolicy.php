<?php namespace App\Policies;

class RequirementPolicy
{
    /**
     * @return bool
     */
    public function restIndex()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function restShow()
    {
        return true;
    }
}
