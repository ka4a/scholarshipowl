<?php namespace App\Entity\Resource;

class ApplicationInputResource extends AbstractApplicationResource
{
    /**
     * @var \App\Entity\ApplicationInput
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
            'requirementInputId' => $this->entity->getRequirement()->getId(),
            'text' => $this->entity->getText()
        ]);
    }
}
