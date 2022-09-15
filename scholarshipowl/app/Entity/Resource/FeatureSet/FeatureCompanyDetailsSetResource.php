<?php
namespace App\Entity\Resource\FeatureSet;

use App\Entity\FeatureCompanyDetailsSet;
use ScholarshipOwl\Data\AbstractResource;

class FeatureCompanyDetailsSetResource extends AbstractResource{
    /**
     * @var FeatureCompanyDetailsSet
     */
    protected $entity;

    protected $fields = [
        'id'          => null,
        'companyName' => null,
        'companyName2' => null,
        'address1'    => null,
        'address2'    => null,
    ];
}