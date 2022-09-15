<?php namespace App\Entity\Resource;

use App\Entity\SocialAccount;
use ScholarshipOwl\Data\AbstractResource;

class SocialAccountResource extends AbstractResource
{
    /**
     * @var SocialAccount
     */
    protected $entity;

    public function __construct(SocialAccount $socialAccount)
    {
        $this->entity = $socialAccount;
        return $socialAccount;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'accountId' => $this->entity->getAccount()->getAccountId(),
            'userProviderId' => $this->entity->getProviderUserId(),
            'provider' => $this->entity->getProvider(),
            'token'  => $this->entity->getToken(),
            'link' => $this->entity->getLink()
        ];
    }
}
