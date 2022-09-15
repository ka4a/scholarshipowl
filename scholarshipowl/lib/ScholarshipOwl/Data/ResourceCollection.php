<?php namespace ScholarshipOwl\Data;

use Doctrine\Common\Collections\ArrayCollection;

class ResourceCollection
{
    /**
     * @var ArrayCollection
     */
    public $elements;

    /**
     * @var ResourceInterface
     */
    protected $resource;

    /**
     * @param ResourceInterface $resource
     * @param                   $collection
     *
     * @return array
     */
    public static function collectionToArray(ResourceInterface $resource, $collection)
    {
        if (!is_array($collection) && !$collection instanceof \Traversable) {
            throw new \InvalidArgumentException("Provided collection is not traversable!");
        }

        $array = [];
        foreach ($collection as $entity) {
            $array[] = $resource->setEntity($entity)->toArray();
        }

        return $array;
    }

    /**
     * ResourceCollection constructor.
     *
     * @param \Traversable|array $elements
     * @param ResourceInterface  $resource
     */
    public function __construct(ResourceInterface $resource, $elements = [])
    {
        $this->elements = is_array($elements) ? new ArrayCollection($elements) : $elements;
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return static::collectionToArray($this->resource, $this->elements);
    }
}
