<?php namespace App\Transformers;

use App\Entities\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @param Role $role
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id'            => (int)    $role->getId(),
            'name'          => (string) $role->getName(),
            'permissions'   => (array)  $role->getPermissions(),
        ];
    }
}
