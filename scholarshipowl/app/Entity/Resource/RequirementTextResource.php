<?php namespace App\Entity\Resource;

use App\Entity\RequirementText;
use ScholarshipOwl\Data\AbstractResource;

class RequirementTextResource extends AbstractResource
{
    /**
     * @var RequirementText
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
            'sendType' => $this->entity->getSendType(),
            'attachmentType' => $this->entity->getAttachmentType(),
            'attachmentFormat' => $this->entity->getAttachmentFormat(),
            'allowFile' => $this->entity->getAllowFile(),
            'fileExtension' => $this->entity->getFileExtension(),
            'maxFileSize' => $this->entity->getMaxFileSize(),
            'minWords' => $this->entity->getMinWords(),
            'maxWords' => $this->entity->getMaxWords(),
            'minCharacters' => $this->entity->getMinCharacters(),
            'maxCharacters' => $this->entity->getMaxCharacters(),
            'createdAt' => $this->entity->getCreatedAt(),
            'updatedAt' => $this->entity->getUpdatedAt(),
            'isOptional' => $this->entity->isOptional(),
        ];
    }
}
