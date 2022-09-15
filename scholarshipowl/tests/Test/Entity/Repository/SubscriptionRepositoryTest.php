<?php namespace Test\Entity\Repository;

use App\Entity\Package;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;

use App\Payment\RemotePaymentManager;
use App\Services\PaymentManager;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;

use Carbon\Carbon;

class SubscriptionRepositoryTest extends TestCase
{
    use EntityGenerator;

        public function tearDown(): void
    {
        static::$truncate[] = 'subscription';
        static::$truncate[] = 'transaction';

        parent::tearDown();
    }

    /**
     * @return SubscriptionRepository
     */
    protected function repository()
    {
        return \EntityManager::getRepository(Subscription::class);
    }

    public function testFindActiveSubscriptions()
    {
        $account1 = $this->generateAccount('test@t1.com');
        $account2 = $this->generateAccount('test@t2.com');
        $account3 = $this->generateAccount('test@t3.com');
        $account4 = $this->generateAccount('test@t4.com');
        $this->generateSubscription($this->generatePackage()->setIsScholarshipsUnlimited(false)->setPriority(10), $account1);
        $this->generateSubscription($this->generatePackage()->setIsScholarshipsUnlimited(false)->setPriority(10), $account2);
        $this->generateSubscription($this->generatePackage()->setIsScholarshipsUnlimited(false)->setPriority(10), $account3);
        $s4 = $this->generateSubscription($this->generatePackage(), $account4);


        $subscription1 = $this->generateSubscription($this->generatePackage()->setIsScholarshipsUnlimited(true)->setPriority(1), $account1);
        $subscription2 = $this->generateSubscription($this->generatePackage()->setIsScholarshipsUnlimited(false)->setPriority(20), $account2);
        $subscription3 = $this->generateSubscription($this->generatePackage()->setIsScholarshipsUnlimited(false)->setPriority(20), $account3);

        $this->em->flush();

        $subscriptions = static::repository()->findActiveSubscriptions([$account1, $account2, $account3]);

        $this->assertEquals($subscription1, $subscriptions[$account1->getAccountId()]);
        $this->assertEquals($subscription2, $subscriptions[$account2->getAccountId()]);
        $this->assertEquals($subscription3, $subscriptions[$account3->getAccountId()]);
    }

    public function testFindActiveUntilSubscriptions()
    {
        /** @var PaymentManager $pm */
        $pm = app(PaymentManager::class);

        $mockPm = function($pm, $subscription) {
            $rpm = \Mockery::mock(RemotePaymentManager::class)
                ->shouldReceive('cancelSubscription')
                ->with($subscription, false)
                ->andReturnUsing(function($subscription, $flush) {
                    $subscription->setRemoteStatus(Subscription::CANCELLED);
                    \EntityManager::flush($subscription);
                })
                ->getMock();
            $pm->setRemotePaymentManager($rpm);

            return $pm;
        };

        // must be canceled but still active
        $account1 = $this->generateAccount('t@t1.com');
        $package1 = $this->generatePackage();
        $package1->setExpirationPeriodType('day');
        $package1->setExpirationPeriodValue(5);
        \EntityManager::flush($package1);
        $subscription1 = $this->generateSubscription($package1, $account1);
        $this->generateTransaction($subscription1);

        $pm = $mockPm($pm, $subscription1);
        $pm->cancelSubscription($subscription1);

        // must be cancelled and inactive
        $account2 = $this->generateAccount('t@t2.com');
        $package2 = $this->generatePackage();
        $package2->setFreeTrial(true);
        $package2->setFreeTrialPeriod('day');
        $package2->setFreeTrialPeriodValue(7);
        \EntityManager::flush($package2);
        $subscription2 = $this->generateSubscription($package2, $account2);

        $pm = $mockPm($pm, $subscription2);
        $pm->cancelSubscription($subscription2);

        // must be cancelled and inactive (because of past due)
        $account3 = $this->generateAccount('t@t3.com');
        $package3 = $this->generatePackage();
        $package3->setExpirationPeriodType('day');
        $package3->setExpirationPeriodValue(5);
        \EntityManager::flush($package3);
        $subscription3 = $this->generateSubscription($package3, $account3);
        $t3 = $this->generateTransaction($subscription3);
        $t3->setCreatedDate(Carbon::instance(new \DateTime())->subDays(7));
        \EntityManager::flush($t3);

        $pm = $mockPm($pm, $subscription3);
        $pm->cancelSubscription($subscription3);


        $subscriptions = static::repository()->findActiveSubscriptions([$account1, $account2, $account3]);
        $this->assertEquals(
            $subscription1->getSubscriptionId(), $subscriptions[$account1->getAccountId()]->getSubscriptionId()
        );
        $this->assertTrue(is_null($subscriptions[$account2->getAccountId()]));
        $this->assertTrue(is_null($subscriptions[$account3->getAccountId()]));
    }

    public function testFindExpiredSubscriptionsDate()
    {
        $package = $this->generatePackage(Package::EXPIRATION_TYPE_DATE);
        $package->setExpirationDate(Carbon::now());
        $account = $this->generateAccount();
        $subscription = $this->generateSubscription($package, $account);
        \EntityManager::flush();

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = \EntityManager::getRepository(Subscription::class);

        $this->assertEmpty($subscriptionRepository->findExpiredSubscriptions());

        $subscription->setEndDate(Carbon::instance($package->getExpirationDate())->subDay());
        \EntityManager::flush();
        $subscriptions = $subscriptionRepository->findExpiredSubscriptions();
        $this->assertCount(1, $subscriptions);
    }

    public function testFindExpiredSubscriptionsPeriod()
    {
        $package = $this->generatePackage(Package::EXPIRATION_TYPE_PERIOD);
        $account = $this->generateAccount();
        $subscription = $this->generateSubscription($package, $account);

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = \EntityManager::getRepository(Subscription::class);

        $this->assertEmpty($subscriptionRepository->findExpiredSubscriptions());

        $subscription->setEndDate(Carbon::now()->subDay());
        \EntityManager::flush();
        $subscriptions = $subscriptionRepository->findExpiredSubscriptions();
        $this->assertCount(1, $subscriptions);
    }

    public function testFindExpiredSubscriptionsRecurrent()
    {
        $package = $this->generatePackage();
        $account = $this->generateAccount();
        $subscription1 = $this->generateSubscription($package, $account);
        $subscription2 = $this->generateSubscription($package, $account);

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = \EntityManager::getRepository(Subscription::class);

        $this->assertEmpty($subscriptionRepository->findExpiredSubscriptions());

        $subscription1->setRenewalDate(Carbon::now()->subMonth());
        \EntityManager::flush();
        $subscriptions = $subscriptionRepository->findExpiredSubscriptions();
        $this->assertCount(1, $subscriptions);
        $this->assertEquals($subscription1, $subscriptions[0]);

        $subscription2->setRenewalDate(Carbon::now()->subDays(Subscription::EXPIRING_PERIOD));
        \EntityManager::flush();
        $subscriptions = $subscriptionRepository->findExpiredSubscriptions();
        $this->assertCount(1, $subscriptions);
        $this->assertEquals($subscription1, $subscriptions[0]);

        $subscription2->setRenewalDate(Carbon::now()->subMonth());
        \EntityManager::flush();
        $subscriptions = $subscriptionRepository->findExpiredSubscriptions();
        $this->assertCount(2, $subscriptions);

        $subscription1->setSubscriptionStatus(SubscriptionStatus::EXPIRED);
        \EntityManager::flush();
        $subscriptions = $subscriptionRepository->findExpiredSubscriptions();
        $this->assertCount(1, $subscriptions);
        $this->assertEquals($subscription2, $subscriptions[0]);
    }

}
