<?php namespace Test\Services;

use App\Contracts\Recurrable;
use App\Entity\ApplicationStatus;
use App\Entity\Profile;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Services\ApplicationService;
use App\Services\EligibilityCacheService;
use App\Services\ScholarshipService;
use App\Testing\TestCase;

use Carbon\Carbon;
use Mockery as m;

class ApplicationServiceTest extends TestCase
{

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * @var ScholarshipService
     */
    protected $scholarshipService;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'application';
        static::$truncate[] = 'scholarship';

        $this->scholarshipService = $this->app->make(ScholarshipService::class);
        $this->applicationService = $this->app->make(ApplicationService::class);
        $this->elbCacheService = $this->app->make(EligibilityCacheService::class);
    }

    public function testReapplyAfterRecurrence()
    {
        $account = $this->generateAccount();
        $this->generateSubscription(null, $account);
        $account->getProfile()->setRecurringApplication(Profile::RECURRENT_APPLY_ON_DEADLINE);

        $scholarship = $this->generateScholarship()
            ->setIsRecurrent(true)
            ->setRecurringType(Recurrable::PERIOD_TYPE_DAY)
            ->setRecurringValue(1)
            ->setRecurrenceStartNow(true)
            ->setStartDate(Carbon::now()->subDay(1));

        $this->generateApplicationText($this->generateRequirementText($scholarship), null, 'test text', $account);

        $this->applicationService->applyScholarship($account, $scholarship);
        $newScholarship = $this->scholarshipService->recur($scholarship);

        $application = $this->applicationService->applyScholarship($account, $newScholarship);

        $this->assertTrue($application->getApplicationStatus()->isPending());
    }

    public function testApplyToScholarshipWithoutFilledRequirements()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship);
        $requirementFile = $this->generateRequirementFile($scholarship);
        $requirementImage = $this->generateRequirementImage($scholarship);

        $account2 = $this->generateAccount('test@teststestset.com');
        $this->generateApplicationText($requirementText, null, 'test', $account2);
        $this->generateApplicationFile($this->generateAccountFile($account2), $requirementFile);
        $this->generateApplicationImage($this->generateAccountFile($account2), $requirementImage);

        try {
            $this->applicationService->applyScholarship($account, $scholarship, true);
        } catch (ApplicationService\Exception\MissingRequirementException $e) {
            $this->assertEquals('Scholarship missing text requirement: 1', $e->getMessage());
        }

        try {
            $this->generateApplicationText($requirementText, null, 'test', $account);
            $this->applicationService->applyScholarship($account, $scholarship, true);
        } catch (ApplicationService\Exception\MissingRequirementException $e) {
            $this->assertEquals('Scholarship missing file requirement: 1', $e->getMessage());
        }

        try {
            $this->generateApplicationFile($this->generateAccountFile($account), $requirementFile);
            $this->applicationService->applyScholarship($account, $scholarship, true);
        } catch (ApplicationService\Exception\MissingRequirementException $e) {
            $this->assertEquals('Scholarship missing image requirement: 1', $e->getMessage());
        }

        $this->generateApplicationImage($this->generateAccountFile($account), $requirementImage);
        $application = $this->applicationService->applyScholarship($account, $scholarship, true);
        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::PENDING));
    }

    public function testApplyToScholarshipValidateSubscriptionCredit()
    {
        // login history record needed for EligibilityCache
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $package = $this->generatePackage()->setIsScholarshipsUnlimited(true);
        $subscription = $this->generateSubscription($package, $account);
        \EntityManager::flush();

        $application = $this->applicationService->applyScholarship($account, $scholarship);
        $this->assertEquals($subscription, $application->getSubscription());
        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::PENDING));
    }

    public function testApplyToScholarshipUnlimitedSubscription()
    {
        // login history record needed for EligibilityCache
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $package = $this->generatePackage()
            ->setIsScholarshipsUnlimited(false)
            ->setScholarshipsCount(5);
        $this->generateSubscription($package, $account)->setCredit(0);
        $subscription = $this->generateSubscription($package, $account);
        \EntityManager::flush();

        $application = $this->applicationService->applyScholarship($account, $scholarship);
        $this->assertEquals($subscription, $application->getSubscription());
        $this->assertEquals(4, $subscription->getCredit());
        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::PENDING));
    }

    public function testApplyToScholarshipValidateSubscription()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $scholarship->setIsFree(false);
        $package = $this->generatePackage()
            ->setIsScholarshipsUnlimited(false)
            ->setScholarshipsCount(5);
        $this->generateSubscription($package, $account)->setCredit(0);
        \EntityManager::flush();

        $this->expectException(ApplicationService\Exception\ApplicationSubscriptionNotFound::class);
        $this->applicationService->applyScholarship($account, $scholarship);
    }

    public function testSendApplication()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_ONLINE);
        $application = $this->generateApplication($scholarship, $account);
        \EntityManager::flush();

        $submitData = [];
        $sender = m::mock(ApplicationService\ApplicationSenderOnline::class);
        $sender->shouldReceive('prepareScholarship')
            ->once()
            ->with($scholarship, $account)
            ->andReturn($scholarship);
        $sender->shouldReceive('prepareSubmitData')
            ->once()
            ->with($scholarship, $account)
            ->andReturn($submitData);
        $sender->shouldReceive('sendApplication')
            ->once()
            ->with($scholarship, $submitData, $application)
            ->andReturnUsing(function() use ($application, $submitData) {
                $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::IN_PROGRESS));
                $this->assertJsonStringEqualsJsonString(json_encode($submitData), $application->getSubmitedData());
                return 'ok';
            });

        $this->applicationService->setSenderOnline($sender);
        $this->applicationService->sendApplication($application);

        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::SUCCESS));
        $this->assertEquals('ok', $application->getComment());
    }

    public function testSendApplicationExceptionOnSendApplication()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_ONLINE);
        $application = $this->generateApplication($scholarship, $account);
        \EntityManager::flush();

        $submitData = [];
        $sender = m::mock(ApplicationService\ApplicationSenderOnline::class);
        $sender->shouldReceive('prepareScholarship')
            ->once()
            ->with($scholarship, $account)
            ->andReturn($scholarship);
        $sender->shouldReceive('prepareSubmitData')
            ->once()
            ->with($scholarship, $account)
            ->andReturn($submitData);
        $sender->shouldReceive('sendApplication')
            ->once()
            ->with($scholarship, $submitData, $application)
            ->andReturnUsing(function() use ($application, $submitData) {
                $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::IN_PROGRESS));
                $this->assertJsonStringEqualsJsonString(json_encode($submitData), $application->getSubmitedData());
                throw new \Exception('Test message!');
            });

        try {
            $this->applicationService->setSenderOnline($sender);
            $this->applicationService->sendApplication($application);
        } catch (\Exception $e) {
            $this->assertEquals('Test message!', $e->getMessage());
        }

        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::ERROR));
        $this->assertRegExp('/^Message: Test message!.*/', $application->getComment());
    }

    public function testSendApplicationExceptionOnSubmitData()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_ONLINE);
        $application = $this->generateApplication($scholarship, $account);
        \EntityManager::flush();

        $sender = m::mock(ApplicationService\ApplicationSenderOnline::class);
        $sender->shouldReceive('prepareScholarship')
            ->once()
            ->with($scholarship, $account)
            ->andReturn($scholarship);
        $sender->shouldReceive('prepareSubmitData')
            ->once()
            ->with($scholarship, $account)
            ->andThrow(\Exception::class, 'Test message!');

        try {
            $this->applicationService->setSenderOnline($sender);
            $this->applicationService->sendApplication($application);
        } catch (\Exception $e) {
            $this->assertEquals('Test message!', $e->getMessage());
        }

        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::ERROR));
        $this->assertRegExp('/^Message: Test message!.*/', $application->getComment());
    }

    public function testSendApplicationWrongType()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_NONE);
        $application = $this->generateApplication($scholarship, $account);
        \EntityManager::flush();

        try {
            $this->applicationService->sendApplication($application);
        } catch (\LogicException $e) {
            $this->assertEquals('Unknown application type!', $e->getMessage());
        }

        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::ERROR));
        $this->assertRegExp('/^Message: Unknown application type!.*/', $application->getComment());
    }

    public function testApplyToScholarshipWithOptionalRequirements()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();

        $requirementTextOptional = $this->generateRequirementText($scholarship);
        $requirementTextOptional->setIsOptional(true);
        \EntityManager::flush($requirementTextOptional);

        $requirementFile = $this->generateRequirementFile($scholarship);
        $requirementImage = $this->generateRequirementImage($scholarship);

        $account2 = $this->generateAccount('test@teststestset.com');
        $this->generateApplicationFile($this->generateAccountFile($account2), $requirementFile);
        $this->generateApplicationImage($this->generateAccountFile($account2), $requirementImage);

        try {
            $this->applicationService->applyScholarship($account, $scholarship, true);
        } catch (ApplicationService\Exception\MissingRequirementException $e) {
            $this->assertEquals('Scholarship missing file requirement: 1', $e->getMessage());
        }

        try {
            $this->generateApplicationFile($this->generateAccountFile($account), $requirementFile);
            $this->applicationService->applyScholarship($account, $scholarship, true);
        } catch (ApplicationService\Exception\MissingRequirementException $e) {
            $this->assertEquals('Scholarship missing image requirement: 1', $e->getMessage());
        }

        $this->generateApplicationImage($this->generateAccountFile($account), $requirementImage);
        $application = $this->applicationService->applyScholarship($account, $scholarship, true);
        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::PENDING));
    }

    public function testApplyToScholarshipWithOneOptionalRequirements()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();

        $requirementTextOptional = $this->generateRequirementText($scholarship);
        $requirementTextOptional->setIsOptional(true);
        \EntityManager::flush($requirementTextOptional);

        $application = $this->applicationService->applyScholarship($account, $scholarship, true);
        $this->assertTrue($application->getApplicationStatus()->is(ApplicationStatus::PENDING));
    }

}
