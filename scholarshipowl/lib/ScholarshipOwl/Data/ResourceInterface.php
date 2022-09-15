<?php namespace ScholarshipOwl\Data;

interface ResourceInterface
{
    /**
     * Convert entity into array
     *
     * @return array
     */
    public function toArray() : array;

    /**
     * @param                   $entity
     * @param ResourceInterface $resource
     *
     * @return array
     */
    public static function entityToArray($entity, ResourceInterface $resource) : array;

    /**
     * Set entity on resource
     *
     * @param $entity
     *
     * @return $this
     */
    public function setEntity($entity);
}
