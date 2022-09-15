<?php namespace App\Entity\Resource;

use App\Entity\RequirementFile;
use ScholarshipOwl\Data\AbstractResource;

class RequirementFileResource extends AbstractResource
{
    /**
     * @var RequirementFile
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
            'fileExtension' => $this->entity->getFileExtension(),
            'maxFileSize' => $this->entity->getMaxFileSize(),
            'createdAt' => $this->entity->getCreatedAt(),
            'updatedAt' => $this->entity->getUpdatedAt(),
            'isOptional' => $this->entity->isOptional(),
        ];
    }
}
