<?php
namespace App\Entity\Resource;

use App\Entity\FeatureSet;
use App\Entity\Resource\FeatureSet\FeatureContentSetResource;
use App\Entity\Resource\FeatureSet\FeaturePaymentSetResource;
use ScholarshipOwl\Data\AbstractResource;

class FeatureSetResource extends AbstractResource
{
    /**
     * @var FeatureSet
     */
    protected $entity;

    protected $fields = [
        'id'                => null,
        'name'              => null,
        'desktopPaymentSet' => FeaturePaymentSetResource::class,
        'mobilePaymentSet'  => FeaturePaymentSetResource::class,
        'contentSet'        => FeatureContentSetResource::class,
        'jobsBannerSet'     => null
    ];
}
