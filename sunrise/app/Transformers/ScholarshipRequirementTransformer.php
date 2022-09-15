<?php namespace App\Transformers;

use App\Entities\ScholarshipRequirement;
use League\Fractal\TransformerAbstract;

class ScholarshipRequirementTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'requirement',
    ];

    protected $availableIncludes = [
        'scholarship',
    ];

    /**
     * @param ScholarshipRequirement $requirement
     * @return array
     */
    public function transform(ScholarshipRequirement $requirement)
    {
        return [
            'id' => $requirement->getId(),
            'title' => $requirement->getTitle(),
            'description' => $requirement->getDescription(),
            'config' => $requirement->getConfig(),
        ];
    }

    /**
     * @param ScholarshipRequirement $requirement
     * @return \League\Fractal\Resource\Item
     */
    public function includeRequirement(ScholarshipRequirement $requirement)
    {
        return $this->item(
            $requirement->getRequirement(),
            new RequirementTransformer(),
            $requirement->getRequirement()->getResourceKey()
        );
    }

    /**
     * @param ScholarshipRequirement $requirement
     * @return \League\Fractal\Resource\Item
     */
    public function includeScholarship(ScholarshipRequirement $requirement)
    {
        return $this->item(
            $requirement->getScholarship(),
            new ScholarshipTransformer(),
            $requirement->getScholarship()->getResourceKey()
        );
    }
}
