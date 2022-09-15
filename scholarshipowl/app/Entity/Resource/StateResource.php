<?php namespace App\Entity\Resource;

use App\Entity\State;

class StateResource extends DictionaryResource
{
    /**
     * @var State
     */
    protected $entity;

    /**
     * @var array
     */
    protected $fields = [
        'id'            => null,
        'name'          => null,
        'abbreviation'  => null,
    ];
}
