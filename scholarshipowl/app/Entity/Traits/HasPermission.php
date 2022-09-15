<?php namespace App\Entity\Traits;

use App\Entity\Admin\AdminRolePermission;

trait HasPermission
{

    /**
     * @return array
     */
    abstract public function getPermissions();

    /**
     * Check is admin has permissions
     *
     * @param array|string $check
     * @param bool         $all
     *
     * @return bool
     */
    public function hasPermissionTo($check, $all = false)
    {
        if (is_array($check)) {
            foreach ($check as $checkPermission) {
                $hasPermission = $this->hasPermissionTo($checkPermission, $all);

                if (!$hasPermission && $all) {
                    return false;
                } elseif ($hasPermission && !$all) {
                    return true;
                }
            }

            return $all;
        } else {
            foreach($this->getPermissions() as $permission) {
                if ($permission instanceof AdminRolePermission && $permission->getPermission() === $check) {
                    return true;
                }
            }

            return false;
        }
    }
}
