<?php namespace Test\Services;

use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use App\Events\Subscription\FreemiumCreditsRenewal;
use App\Services\SubscriptionService;
use App\Testing\TestCase;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;

class MandrillFreemiumTest extends TestCase
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
     * @var ScholarshipRepository
     */
    protected $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(SubscriptionService::class);
        $this->em = $this->app->make(EntityManager::class);
        $this->repository = $this->em->getRepository(Subscription::class);
    }

    public function testMidnightMaintanance(){
        $now = new \DateTime();
        $statement = "ALTER TABLE account AUTO_INCREMENT = 123;";
        \DB::unprepared($statement);
        $account = $this->generateAccount('emtyon@gmail.com');
        $account1 = $this->generateAccount('blah@gmail.com');
        $account2 = $this->generateAccount('blah2@gmail.com');
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
        $this->service->maintain($midnighDate, null , 1);

    }
}
