<?php namespace App\Transformers;

use App\Entities\State;
use League\Fractal\TransformerAbstract;

class StateTransformer extends TransformerAbstract
{
    /**
     * @param State $state
     * @return array
     */
    public function transform(State $state)
    {
        return [
            'id' => $state->getId(),
            'name' => $state->getName(),
            'abbreviation' => $state->getAbbreviation(),
        ];
    }
}
