<?php namespace App\Entity\Resource;

use App\Entity\MarketingSystemAccountData;
use App\Entity\Subscription;
use ScholarshipOwl\Data\AbstractResource;

class SubscriptionResource extends AbstractResource
{
    /**
     * @var Subscription
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        $isFreemium = $this->entity->isFreemium();

        return $this->extra + [
            'subscriptionId' => $this->entity->getSubscriptionId(),
            'subscriptionStatus' => $this->entity->getSubscriptionStatus()->getName(),
            'remoteStatus' => $this->entity->getRemoteStatus(),
            'package' => $this->entity->getPackage()->getPackageId(),
            'subscriptionAcquiredType' => $this->entity->getSubscriptionAcquiredType()->getName(),

            'accountId' => $this->entity->getAccount()->getAccountId(),

            'inFreemium' => $isFreemium,
            'price' => $this->entity->getPrice(),
            'name' => $this->entity->getName(),

            'startDate' => $this->entity->getStartDate(),

            //return null date if it is freemium subscription
            'endDate' => $isFreemium? null : $this->entity->getEndDate(),
            'terminatedAt' => $this->entity->getTerminatedAt(),
            'renewalDate' => $isFreemium? null :$this->entity->getRenewalDate(),
            'activeUntil' => $this->entity->getActiveUntil(),

            'recurrentCount' => $this->entity->getRecurrentCount(),

            'freeTrial' => $this->entity->getFreeTrial(),
            'freeTrialEndDate' => $this->entity->getFreeTrialEndDate(),

            'paymentMethod' => $this->entity->getPaymentMethod() ? $this->entity->getPaymentMethod()->getName() : null,
        ];
    }
}
