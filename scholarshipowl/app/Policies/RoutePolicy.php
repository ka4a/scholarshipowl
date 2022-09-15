<?php namespace App\Policies;

use App\Contracts\HasPermission;
use Illuminate\Routing\Route;

class RoutePolicy
{

    const PERMISSION_PREFIX = 'route::';

    /**
     * Always allow permission
     */
    const PERMISSION_ALLOW = self::PERMISSION_PREFIX.'ALLOW';

    /**
     * @var array
     */
    protected static $availablePermissions;

    /**
     * Get all available route permissions.
     *
     * @return array
     */
    public static function getAvailablePermissions()
    {
        if (static::$availablePermissions === null) {
            $permissions = [];

            foreach (\Route::getRoutes() as $route) {
                if ($routePermissions = static::getRoutePermissions($route)) {
                    foreach ($routePermissions as $permission => $description) {
                        if (!isset($permissions[$permission]) && $permission !== static::PERMISSION_ALLOW) {
                            $permissions[$permission] = $description;
                        }
                    }
                }
            }

            static::$availablePermissions = $permissions;
        }

        return static::$availablePermissions;
    }

    /**
     * @param Route $route
     *
     * @return array|bool
     */
    public static function getRoutePermissions(Route $route)
    {
        $permissions = false;

        if ($actionPermission = ($route->getAction()['permission'] ?? false)) {
            $permissions = [];
            foreach ((array) $actionPermission as $permission => $description) {
                $permission = is_string($permission) ? $permission : $description;
                $permissions[static::addPrefix($permission)] = $description;
            }
        }

        return $permissions;
    }

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public static function isAlwaysAllow(array $permissions)
    {
        return in_array(static::PERMISSION_ALLOW, $permissions);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public static function addPrefix($value)
    {
        return static::PERMISSION_PREFIX.$value;
    }

    /**
     * Check if user can access the route.
     *
     * @param HasPermission $admin
     * @param Route         $route
     *
     * @return bool
     */
    public function access(HasPermission $admin, Route $route)
    {
        if ($permissions = $this->getRoutePermissions($route)) {
            $permissions = array_keys($permissions);

            return $this->isAlwaysAllow($permissions) || $admin->hasPermissionTo($permissions, true);
        }

        return false;
    }
}
