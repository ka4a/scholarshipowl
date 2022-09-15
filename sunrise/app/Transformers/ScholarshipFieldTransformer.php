<?php namespace App\Transformers;

use App\Entities\ScholarshipField;
use League\Fractal\TransformerAbstract;

class ScholarshipFieldTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'field',
    ];

    /**
     * @param ScholarshipField $field
     * @return array
     */
    public function transform(ScholarshipField $field)
    {
        return [
            'id' => $field->getId(),
            'eligibilityType' => $field->getEligibilityType(),
            'eligibilityValue' => $field->getEligibilityValue(),
            'optional' => $field->isOptional(),
        ];
    }

    /**
     * @param ScholarshipField $field
     * @return \League\Fractal\Resource\Item
     */
    public function includeField(ScholarshipField $field)
    {
        return $this->item($field->getField(), new FieldTransformer(), $field->getField()->getResourceKey());
    }
}
