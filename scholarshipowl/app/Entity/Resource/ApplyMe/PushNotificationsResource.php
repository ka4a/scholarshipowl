<?php namespace App\Entity\Resource\ApplyMe;
# CrEaTeD bY FaI8T IlYa      
# 2017  

use App\Entity\PushNotifications;
use ScholarshipOwl\Data\AbstractResource;

class PushNotificationsResource extends AbstractResource
{
    /** @var PushNotifications $entity */
    protected $entity;

    /**
     * PushNotificationsResource constructor.
     * @param PushNotifications|null $pushNotifications
     */
    function __construct(PushNotifications $pushNotifications = null)
    {
        $this->entity = $pushNotifications;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'        => $this->entity->getId(),
            'slug'      => $this->entity->getSlug(),
            'is_active' => $this->entity->getIsActive()
        ];
    }

}