<?php namespace App\Contracts;

interface HasPermission
{

    /**
     * @param string $check
     * @param bool   $all
     *
     * @return bool
     */
    public function hasPermissionTo($check, $all = false);
}
