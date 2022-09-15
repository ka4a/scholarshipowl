<?php namespace App\Transformers;

use App\Entities\OrganisationRole;
use League\Fractal\TransformerAbstract;

class OrganisationRoleTransformer extends TransformerAbstract
{
    /**
     * @param OrganisationRole $role
     * @return array
     */
    public function transform(OrganisationRole $role)
    {
        return [
            'id' => $role->getId(),
            'name' => $role->getName(),
            'permissions' => $role->getPermissions(),
        ];
    }
}
