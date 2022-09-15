<?php namespace App\Entity\Resource;

use ScholarshipOwl\Data\AbstractResource;

class AccountFileResource extends AbstractResource
{
    /**
     * @var \App\Entity\AccountFile
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id'         => $this->entity->getId(),
            'path'       => $this->entity->getPath(),
            'filename'   => $this->entity->getFileName(),
            'accountId' => $this->entity->getAccount()->getAccountId(),
            'category'   => $this->entity->getCategory()->getName(),
            'publicUrl' => $this->entity->getPublicUrl(),
            'realname' => $this->entity->getRealName(),
        ];
    }
}
