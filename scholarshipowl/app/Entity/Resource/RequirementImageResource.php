<?php namespace App\Entity\Resource;

use App\Entity\RequirementImage;
use ScholarshipOwl\Data\AbstractResource;

class RequirementImageResource extends AbstractResource
{
    /**
     * @var RequirementImage
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
            'minWidth' => $this->entity->getMinWidth(),
            'maxWidth' => $this->entity->getMaxWidth(),
            'minHeight' => $this->entity->getMinHeight(),
            'maxHeight' => $this->entity->getMaxHeight(),
            'createdAt' => $this->entity->getCreatedAt(),
            'updatedAt' => $this->entity->getUpdatedAt(),
            'isOptional' => $this->entity->isOptional(),
        ];
    }
}
