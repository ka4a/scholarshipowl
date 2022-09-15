<?php namespace App\Entity\Resource\ApplyMe;
# CrEaTeD bY FaI8T IlYa      
# 2017  

use App\Entity\ApplyMe\ApplymePayments;
use ScholarshipOwl\Data\AbstractResource;

class PaymentsResource extends AbstractResource
{
    /** @var ApplymePayments $entity */
    protected $entity;

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'            => $this->entity->getId(),
            'sum'           => $this->entity->getSum(),
            'response'      => $this->entity->getResponse(),
            'status'        => $this->entity->getStatus(),
            'paymentMethod' => $this->entity->getPaymentMethod(),
            'data'          => $this->entity->getData() ?? null,
            'updatedAt'     => $this->entity->getUpdatedAt()
        ];
    }

}