<?php namespace App\Entity\Resource;

use App\Entity\ApplicationText;

class ApplicationTextResource extends AbstractApplicationResource
{
    /**
     * @var ApplicationText
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->applyScholarship([
            'id' => $this->entity->getId(),
            'accountId' => $this->entity->getAccount()->getAccountId(),
            'requirementTextId' => $this->entity->getRequirement()->getId(),
            'accountFile' => $this->entity->getRequirement()->getAllowFile() && $this->entity->getAccountFile() ?
                AccountFileResource::entityToArray($this->entity->getAccountFile()) : null,
            'text' => $this->entity->getText(),
            'created_at' => $this->entity->getCreatedAt(),
            'updated_at' => $this->entity->getUpdatedAt(),
        ]);
    }
}
