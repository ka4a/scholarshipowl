<?php namespace App\Entity\Resource;

use App\Entity\ApplicationSpecialEligibility;

class ApplicationSpecialEligibilityResource extends AbstractApplicationResource
{
    /**
     * @var ApplicationSpecialEligibility
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
            'requirementId' => $this->entity->getRequirement()->getId(),
            'val' => $this->entity->getVal(),
            'created_at' => $this->entity->getCreatedAt(),
            'updated_at' => $this->entity->getUpdatedAt(),
        ]);
    }
}
