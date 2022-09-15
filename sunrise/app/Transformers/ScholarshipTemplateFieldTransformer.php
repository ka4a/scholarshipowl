<?php namespace App\Transformers;

use App\Entities\ScholarshipTemplateField;
use League\Fractal\TransformerAbstract;

class ScholarshipTemplateFieldTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'field',
    ];

    /**
     * @param ScholarshipTemplateField $field
     * @return array
     */
    public function transform(ScholarshipTemplateField $field)
    {
        return [
            'id' => $field->getId(),
            'eligibilityType' => $field->getEligibilityType(),
            'eligibilityValue' => $field->getEligibilityValue(),
            'optional' => $field->isOptional(),
        ];
    }

    /**
     * @param ScholarshipTemplateField $field
     * @return \League\Fractal\Resource\Item
     */
    public function includeField(ScholarshipTemplateField $field)
    {
        return $this->item($field->getField(), new FieldTransformer(), $field->getField()->getResourceKey());
    }
}
