<?php

namespace Test\Http\Controller\Rest;

use App\Entity\FeatureContentSet;
use App\Entity\FeaturePaymentSet;
use App\Entity\FeatureSet;
use App\Entity\PaymentMethod;
use App\Testing\TestCase;

class PaymentFeatureSetRestControllerTest extends TestCase
{
    public function testGetData()
    {
        $this->prepareFset();
        $this->actingAs($this->generateAccount());
        $resp = $this->get(route('rest::v1.payment-fset'));
        $this->seeJsonSuccessSubset($resp, $this->getDefaultFSet());
    }

    /**
     * @param FeatureSet $featureSet
     *
     * @return array
     */
    protected function getDefaultFSet()
    {
        return array(
                    'packages' =>
                        array(
                            '1' =>
                                array(
                                    'package_id' => 2,
                                    'name' => 'testPackage',
                                    'alias' => NULL,
                                    'braintree_plan' => NULL,
                                    'recurly_plan' => NULL,
                                    'stripe_plan' => NULL,
                                    'stripe_discount_id' => '',
                                    'price' => 10,
                                    'price_cents' => 1000,
                                    'price_per_month' => 1,
                                    'discount_price' => NULL,
                                    'description' => NULL,
                                    'scholarships_count' => 10,
                                    'is_scholarships_unlimited' => true,
                                    'expiration_type' => 'recurrent',
                                    'expiration_date' =>
                                        array(
                                            'date' => '-0001-11-30 00:00:00.000000',
                                            'timezone_type' => 3,
                                            'timezone' => 'Europe/Berlin',
                                        ),
                                    'free_trial' => false,
                                    'free_trial_period_type' => NULL,
                                    'free_trial_period_value' => NULL,
                                    'expiration_period_type' => 'month',
                                    'expiration_period_value' => 10,
                                    'is_active' => '0',
                                    'is_marked' => '0',
                                    'is_marked_mobile' => '0',
                                    'is_automatic' => '0',
                                    'priority' => 0,
                                    'success_message' => NULL,
                                    'success_title' => NULL,
                                ),
                            '2' =>
                                array(
                                    'package_id' => 3,
                                    'name' => 'testPackage',
                                    'alias' => NULL,
                                    'braintree_plan' => NULL,
                                    'recurly_plan' => NULL,
                                    'stripe_plan' => NULL,
                                    'stripe_discount_id' => '',
                                    'price' => 10,
                                    'price_cents' => 1000,
                                    'price_per_month' => 1,
                                    'discount_price' => NULL,
                                    'description' => NULL,
                                    'scholarships_count' => 10,
                                    'is_scholarships_unlimited' => true,
                                    'expiration_type' => 'recurrent',
                                    'expiration_date' =>
                                        array(
                                            'date' => '-0001-11-30 00:00:00.000000',
                                            'timezone_type' => 3,
                                            'timezone' => 'Europe/Berlin',
                                        ),
                                    'free_trial' => false,
                                    'free_trial_period_type' => NULL,
                                    'free_trial_period_value' => NULL,
                                    'expiration_period_type' => 'month',
                                    'expiration_period_value' => 10,
                                    'is_active' => '0',
                                    'is_marked' => '0',
                                    'is_marked_mobile' => '0',
                                    'is_automatic' => '0',
                                    'priority' => 0,
                                    'success_message' => NULL,
                                    'success_title' => NULL,
                                ),
                            '3' =>
                                array(
                                    'package_id' => 4,
                                    'name' => 'testPackage',
                                    'alias' => NULL,
                                    'braintree_plan' => NULL,
                                    'recurly_plan' => NULL,
                                    'stripe_plan' => NULL,
                                    'stripe_discount_id' => '',
                                    'price' => 10,
                                    'price_cents' => 1000,
                                    'price_per_month' => 1,
                                    'discount_price' => NULL,
                                    'description' => NULL,
                                    'scholarships_count' => 10,
                                    'is_scholarships_unlimited' => true,
                                    'expiration_type' => 'recurrent',
                                    'expiration_date' =>
                                        array(
                                            'date' => '-0001-11-30 00:00:00.000000',
                                            'timezone_type' => 3,
                                            'timezone' => 'Europe/Berlin',
                                        ),
                                    'free_trial' => false,
                                    'free_trial_period_type' => NULL,
                                    'free_trial_period_value' => NULL,
                                    'expiration_period_type' => 'month',
                                    'expiration_period_value' => 10,
                                    'is_active' => '0',
                                    'is_marked' => '0',
                                    'is_marked_mobile' => '0',
                                    'is_automatic' => '0',
                                    'priority' => 0,
                                    'success_message' => NULL,
                                    'success_title' => NULL,
                                )
                        ),
                    'payment_set' =>
                        array(
                            'package_common_option' => array(
                                '0' =>
                                    array(
                                        'text' => 'blah',
                                        'status' =>
                                            array(
                                                '1' => '1',
                                                '2' => '0',
                                                '3' => '1',
                                                '4' => '0',
                                            ),
                                    ),
                            )
                        )
                );

    }

    protected function prepareFset(): void
    {
        static::$truncate[] = 'feature_payment_set';
        static::$truncate[] = 'feature_content_set';
        static::$truncate[] = 'feature_set';
        static::$truncate[] = 'package';

        $this->actingAs($this->generateAdminAccount());

        $package1 = $this->generatePackage()->setExpirationPeriodValue(10);
        $package2 = $this->generatePackage()->setExpirationPeriodValue(10);
        $package3 = $this->generatePackage()->setExpirationPeriodValue(10);
        $package4 = $this->generatePackage()->setExpirationPeriodValue(10);


        \EntityManager::persist($package1);
        \EntityManager::persist($package2);
        \EntityManager::persist($package3);
        \EntityManager::persist($package4);
        \EntityManager::flush();

        $desktopPaymentSet = new FeaturePaymentSet(
            PaymentMethod::CREDIT_CARD,
            'PlansPage',
            'desktop payment set popup',
            [
                ['id' => $package1->getPackageId()],
                ['id' => $package2->getPackageId()],
                ['id' => $package3->getPackageId()],
                ['id' => $package4->getPackageId()],
            ]
        );

        $desktopPaymentSet->setCommonOption([
            1 => [
                'text' => 'blah',
                'status' => [
                    '1' => '1',
                    '2' => '0',
                    '3' => '1',
                    '4' => '0',
                ]
            ]
        ]);
        \EntityManager::persist($desktopPaymentSet);
        \EntityManager::flush();


        $contentSet = new FeatureContentSet([
            'homepage_header' => 'test',
            'name' => 'test name',
            'register_header' => 'test header',
            'register_heading_text' => 'test heading text',
            'register_subheading_text' => 'test_subheading_text',
            'register_cta_text' => 'register for free',
            'application_sent_title' => 'application sent title',
            'application_sent_description' => 'application sent title',
            'application_sent_content' => 'application sent title',
            'no_credits_title' => 'no_credits title',
            'no_credits_description' => 'no_credits title',
            'no_credits_content' => 'no_credits title',
            'upgrade_block_text' => 'upgrade block text',
            'upgrade_block_link_upgrade' => 'upgrade block link upgrade',
            'upgrade_block_link_vip' => 'upgrade block link vip',
            'hp_double_promotion_flag' => 0,
            'hp_ydi_flag' => 0,
            'hp_cta_text' => 'CHECK FOR SCHOLARSHIPS',
            'register2_heading_text' => 'test heading text',
            'register2_subheading_text' => 'test_subheading_text',
            'register2_cta_text' => 'register for free',
            'register3_heading_text' => 'test heading text 3 ',
            'register3_subheading_text' => 'test_subheading_text 3',
            'register3_cta_text' => 'register for free 3',
            'pp_header_text' => 'test',
            'pp_header_text_2' => 'test',
            'pp_carousel_items_cnt' => 8,
        ]);

        \EntityManager::persist($contentSet);
        \EntityManager::flush();

        $fset = new FeatureSet('PlansPage', $desktopPaymentSet, $desktopPaymentSet, $contentSet);

        \EntityManager::persist($fset);
        \EntityManager::flush($fset);
    }
}
