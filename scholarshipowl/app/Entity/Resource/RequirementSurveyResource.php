<?php namespace App\Entity\Resource;

use App\Entity\RequirementInput;
use App\Entity\RequirementSurvey;
use ScholarshipOwl\Data\AbstractResource;

class RequirementSurveyResource extends AbstractResource
{
    /**
     * @var RequirementSurvey
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
            'description' => $this->entity->getDescription(),
            'survey' => $this->entity->getSurveyWithId(),
            'isOptional' => $this->entity->isOptional(),
        ];
    }
}
