<?php

namespace App\Entity\Resource\FeatureSet;

use App\Entity\FeaturePaymentSet;
use App\Entity\Resource\PaymentMethodResource;
use ScholarshipOwl\Data\AbstractResource;

class FeaturePaymentSetResource extends AbstractResource
{
    /**
     * @var FeaturePaymentSet
     */
    protected $entity;

    protected $fields = [
        'id'            => null,
        'popupTitle'    => null,
        'showNames'     => null,
        'paymentMethod' => PaymentMethodResource::class,
        'packages'      => null,
        'mobileSpecialOfferOnly' => null,
    ];

    /**
     * @return array
     */
    public function toArray() : array
    {
        /**
         * @var Account $account
         */
        $account = \Auth::user();
        $result = parent::toArray();
        if (!is_null($account)) {
            $result['popupTitleDisplay'] = $account->mapTags($result['popupTitle']);
        }
        return $result;
    }
}