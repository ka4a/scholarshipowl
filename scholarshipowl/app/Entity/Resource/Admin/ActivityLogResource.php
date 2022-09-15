<?php namespace App\Entity\Resource\Admin;

use App\Entity\Admin\AdminActivityLog;
use ScholarshipOwl\Data\AbstractResource;

class ActivityLogResource extends AbstractResource
{
    /**
     * @var AdminActivityLog
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->entity->getId(),
            'adminId' => $this->entity->getAdminId(),
            'adminName' => $this->entity->getAdminName(),
            'route' => $this->entity->getRoute(),
            'data' => $this->entity->getData(),
            'createdAt' => $this->entity->getCreatedAt(),
        ];
    }
}
