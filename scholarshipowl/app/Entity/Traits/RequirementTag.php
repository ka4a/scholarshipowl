<?php namespace App\Entity\Traits;

trait RequirementTag
{
    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getId();

    /**
     * @return string
     */
    public function getTag()
    {
        return sprintf('%s-%s', $this->getType(), $this->getId());
    }
}
