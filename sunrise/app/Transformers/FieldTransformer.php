<?php namespace App\Transformers;

use App\Entities\Field;
use League\Fractal\TransformerAbstract;

class FieldTransformer extends TransformerAbstract
{
    /**
     * @param Field $field
     * @return array
     */
    public function transform(Field $field)
    {
        return [
            'id' => $field->getId(),
            'name' => $field->getName(),
            'type' => $field->getType(),
            'options' => $field->getOptions(),
        ];
    }
}
