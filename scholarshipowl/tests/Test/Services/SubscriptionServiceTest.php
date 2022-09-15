<?php namespace Test\Services;

use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\PaymentFsetHistory;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\State;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use App\Services\SubscriptionService;
use App\Testing\TestCase;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;

class SubscriptionServiceTest extends TestCase
{

    /**
     * @var SubscriptionService
     */
    protected $service;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SubscriptionRepository
     */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        static::$truncate[] = 'feature_set';
        static::$truncate[] = 'feature_content_set';
        $this->service = $this->app->make(SubscriptionService::class);
        $this->em = $this->app->make(EntityManager::class);
        $this->repository = $this->em->getRepository(Subscription::class);
    }

    public function testHourlyMaintananceTimezones()
    {
        $now = new \DateTime();

        $account = $this->generateAccount();
        $package = $this->generatePackage(Package::EXPIRATION_TYPE_NO_EXPIRY);
        $package->setIsFreemium(true);
        $package->setFreemiumRecurrencePeriod('day');
        $package->setFreemiumRecurrenceValue(1);
        $package->setFreemiumCredits(3);

        $package2 = $this->generatePackage(Package::EXPIRATION_TYPE_NO_EXPIRY, 'test-expired');
        $package2->setIsFreemium(false);

        $packageNeverFreemium = $this->generatePackage(Package::EXPIRATION_TYPE_NO_EXPIRY, 'test-never-freemium');
        $packageNeverFreemium->setIsFreemium(true);
        $packageNeverFreemium->setFreemiumRecurrencePeriod('never');
        $packageNeverFreemium->setFreemiumRecurrenceValue(0);
        $packageNeverFreemium->setFreemiumCredits(1);

        $subscription = $this->generateSubscription($package, $account);
        $subscription2 = $this->generateSubscription($package2, $account);
        $subscriptionNeverFreemium = $this->generateSubscription($packageNeverFreemium, $account);

        $subscription2->setEndDate(Carbon::instance($now)->subDay(2));
        $this->em->flush();
        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'credit' => 3
        ]);
        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription2->getSubscriptionId(),
            'credit' => 10
        ]);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscriptionNeverFreemium->getSubscriptionId(),
            'credit' => 1,
            'freemium_recurrence_period' => 'never'
        ]);

        $subscription->setCredit(0);
        $this->em->flush();
        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'credit' => 0
        ]);

        $subscriptionNeverFreemium->setCredit(0);
        $this->em->flush();
        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscriptionNeverFreemium->getSubscriptionId(),
            'credit' => 0,
            'freemium_recurrence_period' => 'never'
        ]);

        $maintainDate = Carbon::instance($now)->addDay(2);
        $this->service->maintain($maintainDate, null, 1);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'credit' => 3,
            'freemium_credits_updated_date' => $maintainDate
        ]);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription2->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::EXPIRED,
        ]);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscriptionNeverFreemium->getSubscriptionId(),
            'credit' => 0,
            'freemium_recurrence_period' => 'never'
        ]);
    }

    public function testMidnightMaintanance(){
        $now = new \DateTime();
        $account = $this->generateAccount();
        $midnightPackage = $this->generatePackage(Package::EXPIRATION_TYPE_NO_EXPIRY, 'test-midnight');
        $midnightPackage->setIsFreemium(true);
        $midnightPackage->setFreemiumRecurrencePeriod('day');
        $midnightPackage->setFreemiumRecurrenceValue(1);
        $midnightPackage->setFreemiumCredits(3);
        $this->em->flush();
        $midnightSubscription = $this->generateSubscription($midnightPackage, $account);
        $midnightSubscription->setFreemiumCreditsUpdatedDate(Carbon::instance($now)->subDay(1)->setTime(23,59,59));
        $midnightSubscription->setCredit(0);
        $this->em->flush();
        $midnighDate = Carbon::instance($now)->addDay(1)->setTime(00,00,00);
        $this->service->maintain($midnighDate, null, 1);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $midnightSubscription->getSubscriptionId(),
            'credit' => 3,
            'freemium_credits_updated_date' => $midnighDate
        ]);
    }
}
