<?php namespace App\Entity\Resource;

use App\Entity\OnesignalAccount;
use ScholarshipOwl\Data\AbstractResource;

class OneSignalAccountResource extends AbstractResource
{
    /**
     * @var OnesignalAccount
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'account_id' => $this->entity->getAccount()->getAccountId(),
            'user_id' => $this->entity->getUserId(),
            'app' => $this->entity->getApp(),
        ];
    }
}
