<?php namespace App\Transformers;

use App\Entities\Requirement;
use League\Fractal\TransformerAbstract;

class RequirementTransformer extends TransformerAbstract
{
    /**
     * @param Requirement $requirement
     * @return array
     */
    public function transform(Requirement $requirement)
    {
        return [
            'id' => $requirement->getId(),
            'type' => $requirement->getType(),
            'name' => $requirement->getName()
        ];
    }
}
