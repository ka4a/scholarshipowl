<?php namespace App\Entity\Resource;

use App\Entity\Banner;
use ScholarshipOwl\Data\AbstractResource;

class BannerResource extends AbstractResource
{

    /**
     * @var Banner
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'url' => $this->entity->getUrl(),
            'type' => $this->entity->getType(),
            'image' => $this->entity->getImage()->getPublicUrl(),
        ];
    }
}
