<?php namespace App\Entity\Resource;

use App\Entity\RequirementSpecialEligibility;
use ScholarshipOwl\Data\AbstractResource;

class RequirementSpecialEligibilityResource extends AbstractResource
{
    /**
     * @var RequirementSpecialEligibility
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
            'text' => $this->entity->getText(),
            'isOptional' => $this->entity->isOptional(),
        ];
    }
}
