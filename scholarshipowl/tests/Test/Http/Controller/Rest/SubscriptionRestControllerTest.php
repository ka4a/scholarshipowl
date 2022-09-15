<?php namespace Test\Http\Controller\Rest;

use App\Entity\MarketingSystemAccountData as MarketingData;
use App\Rest\Index\SimpleFiltersQueryBuilder;
use App\Testing\TestCase;

class SubscriptionRestControllerTest extends TestCase
{

    public function setUp(): void
    {
        static::$truncate[] = 'marketing_system_account_data';
        parent::setUp();
    }

    public function testSubscriptionAccountMarketingData()
    {
        $this->actingAsAdmin();

        $this->generateSubscription();
        $this->generateSubscription()->getAccount()
            ->addMarketingData(new MarketingData(MarketingData::AFFILIATE_ID, 100));
        $this->generateSubscription()->getAccount()
            ->addMarketingData(new MarketingData(MarketingData::AFFILIATE_ID, 200))
            ->addMarketingData(new MarketingData('test1', 'test2'));
        $this->em->flush();

        $resp = $this->get(route('rest::v1.subscription.index'));
        $this->seeJsonSuccessSubset($resp, [], ['count' => 3]);

        $resp = $this->get(route('rest::v1.subscription.index', ['filter_marketing' =>
            [['name' => MarketingData::AFFILIATE_ID, 'operator' => 'eq', 'value' => 200]]
        ]));
        $this->seeJsonSuccessSubset($resp, [['accountId' => 4]], ['count' => 1]);
        $resp = $this->get(route('rest::v1.subscription.index', ['filter_marketing' =>
            [['name' => MarketingData::AFFILIATE_ID, 'operator' => 'gte', 'value' => 100]]
        ]));
        $this->seeJsonSuccessSubset($resp, [], ['count' => 2]);
        $resp = $this->get(route('rest::v1.subscription.index', ['filter_marketing' =>
            [
                ['name' => 'test1', 'operator' => 'eq', 'value' => 'test2'],
            ]
        ]));
        $this->seeJsonSuccessSubset($resp, [['accountId' => 4]], ['count' => 1]);
    }
}
