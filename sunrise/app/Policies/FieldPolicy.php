<?php namespace App\Policies;

class FieldPolicy
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
