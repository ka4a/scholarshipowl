<?php namespace App\Transformers;

use App\Contracts\DictionaryEntityContract;
use League\Fractal\TransformerAbstract;

class DictionaryTransformer extends TransformerAbstract
{
    /**
     * @param DictionaryEntityContract $entity
     * @return array
     */
    public function transform(DictionaryEntityContract $entity)
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
        ];
    }
}
