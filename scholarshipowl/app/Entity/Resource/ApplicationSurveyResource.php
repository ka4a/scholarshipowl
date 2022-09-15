<?php namespace App\Entity\Resource;

use App\Entity\ApplicationSurvey;
use App\Entity\ApplicationText;

class ApplicationSurveyResource extends AbstractApplicationResource
{
    /**
     * @var ApplicationSurvey
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
            'answers' => $this->entity->getAnswers(),
            'created_at' => $this->entity->getCreatedAt(),
            'updated_at' => $this->entity->getUpdatedAt(),
        ]);
    }
}
