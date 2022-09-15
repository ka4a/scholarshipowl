<?php

namespace Test\Http\Controller\Rest;

use App\Entity\FeatureSet;
use App\Testing\TestCase;

class FeatureSetRestControllerTest extends TestCase
{
    public function testGetData()
    {
        $this->actingAs($this->generateAccount());
        $fset = $this->generateFeatureSet();

        $resp = $this->get(route('rest::v1.fset', $fset->getId()));
        $this->seeJsonSuccessSubset($resp, $this->getDefaultFSet($fset));
    }

    /**
     * @param FeatureSet $featureSet
     *
     * @return array
     */
    protected function getDefaultFSet($featureSet)
    {
        return [
            'id'                => $featureSet->getId(),
            'name'              => 'new feature set',
            'desktopPaymentSet' => [
                'id'            => $featureSet->getDesktopPaymentSet()->getId(),
                'popupTitle'    => 'desktop payment set popup',
                'showNames'     => true,
                'paymentMethod' => [],
                'packages'      => [
                    1 => 2,
                    0 => 1,
                ],
            ],
            'mobilePaymentSet'  => [
                'id'            => $featureSet->getMobilePaymentSet()->getId(),
                'popupTitle'    => 'mobile payment set popup',
                'showNames'     => true,
                'paymentMethod' => [],
                'packages'      => [
                    0 => 1,
                    1 => 2,
                ],
            ],
            'contentSet'        => [
                'id'                 => $featureSet->getContentSet()->getId(),
                'name'               => 'test name',
                'homepageHeader'     => 'test',
                'registerHeader'     => 'test header',
                'registerHeadingText' => 'test heading text',
                'registerSubheadingText' => 'test_subheading_text',
                'registerHideFooter' => false,
                'registerCtaText' => 'register for free',
                'selectApplyNow'     => 'apply now',
                'applicationSentTitle' => 'application sent title',
                'applicationSentDescription' => 'application sent title',
                'applicationSentContent' => 'application sent title',
                'noCreditsTitle' => 'no_credits title',
                'noCreditsDescription' => 'no_credits title',
                'noCreditsContent' => 'no_credits title',
                'upgradeBlockText' => 'upgrade block text',
                'upgradeBlockLinkUpgrade' => 'upgrade block link upgrade',
                'upgradeBlockLinkVip' => 'upgrade block link vip',
                'register3HeadingText' => 'test heading text 3 ',
                'register3SubheadingText' => 'test_subheading_text 3',
                'register3CtaText' => 'register for free 3',
            ],
        ];
    }
}
