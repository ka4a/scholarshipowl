<?php namespace App\Entity\Resource;

use App\Entity\ApplicationFile;

class ApplicationFileResource extends AbstractApplicationResource
{
    /**
     * @var ApplicationFile
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
            'accountFile' => AccountFileResource::entityToArray($this->entity->getAccountFile()),
            'requirementFileId' => $this->entity->getRequirement()->getId(),
        ]);
    }
}
