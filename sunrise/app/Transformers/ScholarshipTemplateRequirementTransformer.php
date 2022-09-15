<?php namespace App\Transformers;

use App\Entities\ScholarshipTemplateRequirement;
use League\Fractal\TransformerAbstract;

class ScholarshipTemplateRequirementTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'requirement',
    ];

    protected $availableIncludes = [
        'template',
    ];

    /**
     * @param ScholarshipTemplateRequirement $requirement
     * @return array
     */
    public function transform(ScholarshipTemplateRequirement $requirement)
    {
        return [
            'id' => $requirement->getId(),
            'title' => $requirement->getTitle(),
            'description' => $requirement->getDescription(),
            'config' => $requirement->getConfig(),
        ];
    }

    /**
     * @param ScholarshipTemplateRequirement $requirement
     * @return \League\Fractal\Resource\Item
     */
    public function includeRequirement(ScholarshipTemplateRequirement $requirement)
    {
        return $this->item(
            $requirement->getRequirement(),
            new RequirementTransformer(),
            $requirement->getRequirement()->getResourceKey()
        );
    }

    /**
     * @param ScholarshipTemplateRequirement $requirement
     * @return \League\Fractal\Resource\Item
     */
    public function includeTemplate(ScholarshipTemplateRequirement $requirement)
    {
        return $this->item(
            $requirement->getTemplate(),
            new ScholarshipTemplateTransformer(),
            $requirement->getTemplate()->getResourceKey()
        );
    }
}
