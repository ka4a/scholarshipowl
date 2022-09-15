<?php namespace App\Entity\Resource;

use App\Entity\RequirementInput;
use ScholarshipOwl\Data\AbstractResource;

class RequirementInputResource extends AbstractResource
{
    /**
     * @var RequirementInput
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->entity->getId(),
            'scholarshipId' => $this->entity->getScholarship()->getScholarshipId(),
            'name' => $this->entity->getRequirementName()->getName(),
            'type' => $this->entity->getType(),
            'title' => $this->entity->getTitle(),
            'permanentTag' => $this->entity->getPermanentTag(),
            'description' => $this->entity->getDescription(),
            'isOptional' => $this->entity->isOptional(),
        ];
    }
}
