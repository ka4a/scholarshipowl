<?php namespace App\Entity\Resource;

use App\Entity\Marketing\CoregPlugin;
use ScholarshipOwl\Data\AbstractResource;

class CoregsResource extends AbstractResource
{
    /**
     * @var CoregPlugin
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->extra + [
            'id' => $this->entity->getId(),
            'name' => $this->entity->getName(),
            'position' => $this->entity->getDisplayPosition(),
            'extra' => [
                'isVisible' => $this->entity->getVisible(),
                'text' => $this->entity->getText(),
                'monthlyCap' => $this->entity->getMonthlyCap(),
                'html' => $this->entity->getHtml(),
                'js' => $this->entity->getJs(),
                'extraFields' => json_decode($this->entity->getExtra())
            ]
        ];
    }
}
