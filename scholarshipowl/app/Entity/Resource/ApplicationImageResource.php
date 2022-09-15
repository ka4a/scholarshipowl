<?php namespace App\Entity\Resource;

class ApplicationImageResource extends AbstractApplicationResource
{
    /**
     * @var \App\Entity\ApplicationImage
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
            'requirementImageId' => $this->entity->getRequirement()->getId(),
            'fromCamera' => $this->entity->getFromCamera(),
        ]);
    }

}
