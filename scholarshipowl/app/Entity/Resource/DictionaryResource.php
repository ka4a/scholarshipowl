<?php namespace App\Entity\Resource;

use App\Contracts\DictionaryContract;
use ScholarshipOwl\Data\AbstractResource;

class DictionaryResource extends AbstractResource
{
    /**
     * @var DictionaryContract
     */
    protected $entity;

    /**
     * @var array
     */
    protected $fields = [
        'id'    => null,
        'name'  => null,
    ];

    /**
     * DictionaryResource constructor.
     *
     * @param null|DictionaryContract $entity
     */
    public function __construct(DictionaryContract $entity = null)
    {
        $this->entity = $entity;
    }

    /**
     * @return boolean
     */
    public function isObject()
    {
        return is_object($this->entity);
    }
}
