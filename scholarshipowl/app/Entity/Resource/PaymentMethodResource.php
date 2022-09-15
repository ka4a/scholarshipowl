<?php
namespace App\Entity\Resource;

use ScholarshipOwl\Data\AbstractResource;

class PaymentMethodResource extends AbstractResource
{
    /**
     * @var FeatureSet
     */
    protected $entity;

    protected $fields = [
        'id'   => null,
        'name' => null,
    ];
}
