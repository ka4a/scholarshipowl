<?php
namespace App\Entity\Resource\FeatureSet;

use App\Entity\FeatureContentSet;
use App\Entity\Resource\TaggedResource;
use ScholarshipOwl\Data\AbstractResource;

class FeatureContentSetResource extends AbstractResource
{
    /**
     * @var FeatureContentSet
     */
    protected $entity;

    protected $fields = [
        'id'                         => null,
        'popupTitle'                 => null,
        'name'                       => null,
        'homepageHeader'             => null,
        'registerHeader'             => null,
        'registerHeadingText'        => null,
        'registerSubheadingText'     => null,
        'registerHideFooter'         => null,
        'registerCtaText'            => null,
        'selectApplyNow'             => null,
        'selectHideCheckboxes'       => null,
        'applicationSentTitle'       => null,
        'applicationSentDescription' => null,
        'applicationSentContent'     => null,
        'noCreditsTitle'             => null,
        'noCreditsDescription'       => null,
        'noCreditsContent'           => null,
        'upgradeBlockText'           => null,
        'upgradeBlockLinkUpgrade'    => null,
        'upgradeBlockLinkVip'        => null,
        'register2HeadingText'       => null,
        'register2SubheadingText'    => null,
        'register2CtaText'           => null,

        'register3HeadingText'       => null,
        'register3SubheadingText'    => null,
        'register3CtaText'           => null,

        'registerIllustration' => null,
        'register2Illustration' => null,
        'register3Illustration' => null,

        'ppHeaderText'               => TaggedResource::class,
        'ppHeaderText2'              => TaggedResource::class,
        'ppCarouselItemsCnt'         => null,
    ];
}