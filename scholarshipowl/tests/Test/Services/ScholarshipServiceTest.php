<?php namespace Test\Services;

use App\Contracts\Recurrable;
use App\Entity\ApplicationFile;
use App\Entity\ApplicationImage;
use App\Entity\ApplicationText;
use App\Entity\Package;
use App\Entity\Profile;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\RequirementFile;
use App\Entity\RequirementImage;
use App\Entity\RequirementText;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Events\Account\AccountEvent;
use App\Events\Account\ElbCacheAccountEvent;
use App\Events\Scholarship\ScholarshipBeforeRecurredEvent;
use App\Events\Scholarship\ScholarshipRecurredEvent;
use App\Listeners\ApplyForDYIScholarshipListener;
use App\Services\ApplicationService;
use App\Services\PubSub\TransactionalEmailService;
use App\Testing\TestCase;
use App\Services\ScholarshipService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Mail;

class ScholarshipServiceTest extends TestCase
{

    /**
     * @var ScholarshipService
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

        Mail::fake();

        $this->service = $this->app->make(ScholarshipService::class);
        $this->em = $this->app->make(EntityManager::class);
        $this->repository = $this->em->getRepository(Scholarship::class);
    }

    public function testHourlyMaintananceTimezones()
    {
        $now = new \DateTime();
        $scholarship = $this->generateScholarship(ScholarshipStatus::UNPUBLISHED);
        $scholarship2 = $this->generateScholarship(ScholarshipStatus::UNPUBLISHED);

        $this->service->maintain(Carbon::instance($now)->subDay(1));
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
            'status' => ScholarshipStatus::UNPUBLISHED,
        ]);
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship2->getScholarshipId(),
            'status' => ScholarshipStatus::UNPUBLISHED,
        ]);

        $this->service->maintain(Carbon::instance($now)->subMinute(5));
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
            'status' => ScholarshipStatus::UNPUBLISHED,
        ]);
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship2->getScholarshipId(),
            'status' => ScholarshipStatus::UNPUBLISHED,
        ]);

        $this->service->maintain(Carbon::instance($now)->addMinute(5));
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
            'status' => ScholarshipStatus::PUBLISHED,
        ]);
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship2->getScholarshipId(),
            'status' => ScholarshipStatus::PUBLISHED,
        ]);

        $this->service->maintain(Carbon::instance($now)->addDay(2));
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
            'status' => ScholarshipStatus::EXPIRED,
        ]);
        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship2->getScholarshipId(),
            'status' => ScholarshipStatus::EXPIRED,
        ]);
    }

    public function testHourlyMaintananceRecurrence()
    {
        $this->generateScholarship();
        $scholarship1 = $this->generateScholarship();
        $scholarship1->setStartDate(Carbon::now()->subMonth(1));
        $scholarship1->setExpirationDate(Carbon::now()->subMonth(1)->addDays(17));
        $scholarship1->setIsRecurrent(true);
        $scholarship1->setRecurringType(Recurrable::PERIOD_TYPE_MONTH);
        $scholarship1->setRecurringValue(2);

        $scholarship2 = $this->generateScholarship();
        $scholarship2->setStartDate(Carbon::now());
        $scholarship2->setExpirationDate(Carbon::now()->addDays(17));
        $scholarship2->setIsRecurrent(true);
        $scholarship2->setRecurringType(Recurrable::PERIOD_TYPE_MONTH);
        $scholarship2->setRecurringValue(2);

        $this->em->flush();

        $this->assertCount(3, $this->repository->findAll());

        $result = $this->service->maintain();

        /** @var Scholarship[] $scholarships */
        $this->assertCount(4, $scholarships = $this->repository->findAll());
        $this->assertEquals($scholarship1->getTitle(), $scholarships[3]->getTitle());
        $this->assertEquals(0, $result[0]);
        $this->assertEquals(1, $result[1]);
        $this->assertEquals(1, $result[2]);
    }

    public function testRecurScholarship()
    {
        $this->expectsEvents(ScholarshipBeforeRecurredEvent::class);
        $this->expectsEvents(ScholarshipRecurredEvent::class);

        $start = Carbon::now();
        $end = Carbon::now()->addDays(17);

        $from = $this->generateScholarship();
        $from->setStartDate($start);
        $from->setExpirationDate($end);
        $from->setIsRecurrent(true);
        $from->setRecurringType(Recurrable::PERIOD_TYPE_MONTH);
        $from->setRecurringValue(2);

        $this->em->flush();

        $scholarship = $this->service->recur($from);

        $this->assertTrue($scholarship->isUnpublished());
        $this->assertEquals($scholarship->getTitle(), $from->getTitle());
        $this->assertEquals($scholarship->getStartDate(), $start->addMonth(2));
        $this->assertEquals($scholarship->getExpirationDate(), $end->addMonth(2));

        $this->assertEquals($scholarship->getParentScholarship(), $from);
        $this->assertEquals($scholarship->getCurrentScholarship(), null);

        $scholarship2 = $this->service->recur($scholarship);

        $this->assertEquals($scholarship2->getParentScholarship(), $scholarship);
        $this->assertEquals($scholarship2->getCurrentScholarship(), null);
        $this->assertEquals($scholarship->getParentScholarship(), $from);
        $this->assertEquals($scholarship->getCurrentScholarship(), $scholarship2);
        $this->assertEquals($from->getParentScholarship(), null);
        $this->assertEquals($scholarship2, $from->getCurrentScholarship());
    }

    public function testCopyRecurrentScholarship()
    {
        $from = $this->generateScholarship();
        $from->setIsRecurrent(true);
        \EntityManager::flush($from);
        $fromText = $this->generateRequirementText($from);
        $fromImage = $this->generateRequirementImage($from);
        $fromFile = $this->generateRequirementFile($from);

        $account = $this->generateAccount();
        $applicationText = $this->generateApplicationText($fromText, $this->generateAccountFile($account));
        $applicationFile = $this->generateApplicationFile($this->generateAccountFile($account), $fromFile);
        $applicationImage = $this->generateApplicationImage($this->generateAccountFile($account), $fromImage);

        $to = $this->service->copy($from);

        $this->assertNotEquals($from->getScholarshipId(), $to->getScholarshipId());
        $this->assertNotEquals($from->getTitle(), $to->getTitle());
        $this->assertEquals($from->getDescription(), $to->getDescription());
        $this->assertEquals($from->getMetaTitle(), $to->getMetaTitle());
        $this->assertEquals($from->getAmount(), $to->getAmount());

        $this->assertNotEquals($fromText, $to->getRequirementTexts()->first());
        $this->assertInstanceOf(RequirementText::class, $to->getRequirementTexts()->first());
        $this->assertEquals($fromText->getAllowFile(), $to->getRequirementTexts()->first()->getAllowFile());
        $this->assertEquals($fromText->getTitle(), $to->getRequirementTexts()->first()->getTitle());
        $this->assertEquals($fromText->getDescription(), $to->getRequirementTexts()->first()->getDescription());

        $this->assertNotEquals($fromFile, $to->getRequirementFiles()->first());
        $this->assertNotEquals($fromFile, $to->getRequirementFiles()->first());
        $this->assertInstanceOf(RequirementFile::class, $to->getRequirementFiles()->first());
        $this->assertEquals($fromFile->getTitle(), $to->getRequirementFiles()->first()->getTitle());
        $this->assertEquals($fromFile->getDescription(), $to->getRequirementFiles()->first()->getDescription());

        $this->assertNotEquals($fromImage, $to->getRequirementImages()->first());
        $this->assertNotEquals($fromImage, $to->getRequirementImages()->first());
        $this->assertInstanceOf(RequirementImage::class, $to->getRequirementImages()->first());
        $this->assertEquals($fromImage->getTitle(), $to->getRequirementImages()->first()->getTitle());
        $this->assertEquals($fromImage->getDescription(), $to->getRequirementImages()->first()->getDescription());
        $this->assertEquals($fromImage->getMinWidth(), $to->getRequirementImages()->first()->getMinWidth());
        $this->assertEquals($fromImage->getMaxWidth(), $to->getRequirementImages()->first()->getMaxWidth());
        $this->assertEquals($fromImage->getMinHeight(), $to->getRequirementImages()->first()->getMinHeight());
        $this->assertEquals($fromImage->getMaxHeight(), $to->getRequirementImages()->first()->getMaxHeight());

        /** @var ApplicationText[] $applicationTexts */
        $applicationTexts = $this->repository->findApplicationRequirements($to, $to->getRequirementTexts()->first());
        $this->assertNotEmpty($applicationTexts);
        $this->assertEquals($applicationTexts[0]->getAccount(), $account);
        $this->assertEquals($applicationTexts[0]->getRequirement(), $to->getRequirementTexts()->first());
        $this->assertEquals($applicationTexts[0]->getScholarship(), $to);
        $this->assertEquals($applicationTexts[0]->getAccountFile(), $applicationText->getAccountFile());

        /** @var ApplicationFile[] $applicationFiles */
        $applicationFiles = $this->repository->findApplicationRequirements($to, $to->getRequirementFiles()->first());
        $this->assertNotEmpty($applicationFiles);
        $this->assertEquals($applicationFiles[0]->getAccount(), $account);
        $this->assertEquals($applicationFiles[0]->getRequirement(), $to->getRequirementFiles()->first());
        $this->assertEquals($applicationFiles[0]->getScholarship(), $to);
        $this->assertEquals($applicationFiles[0]->getAccountFile(), $applicationFile->getAccountFile());

        /** @var ApplicationImage[] $applicationImages */
        $applicationImages = $this->repository->findApplicationRequirements($to, $to->getRequirementImages()->first());
        $this->assertNotEmpty($applicationImages);
        $this->assertEquals($applicationImages[0]->getAccount(), $account);
        $this->assertEquals($applicationImages[0]->getRequirement(), $to->getRequirementImages()->first());
        $this->assertEquals($applicationImages[0]->getScholarship(), $to);
        $this->assertEquals($applicationImages[0]->getAccountFile(), $applicationImage->getAccountFile());
    }


    public function testCopyNonRecurrentScholarship()
    {
        $from = $this->generateScholarship();
        $from->setIsRecurrent(false);
        \EntityManager::flush($from);
        $fromText = $this->generateRequirementText($from);
        $fromImage = $this->generateRequirementImage($from);
        $fromFile = $this->generateRequirementFile($from);

        $account = $this->generateAccount();
        $applicationText = $this->generateApplicationText($fromText, $this->generateAccountFile($account));
        $applicationFile = $this->generateApplicationFile($this->generateAccountFile($account), $fromFile);
        $applicationImage = $this->generateApplicationImage($this->generateAccountFile($account), $fromImage);

        $to = $this->service->copy($from);

        $this->assertNotEquals($from->getScholarshipId(), $to->getScholarshipId());
        $this->assertNotEquals($from->getTitle(), $to->getTitle());
        $this->assertEquals($from->getDescription(), $to->getDescription());
        $this->assertEquals($from->getMetaTitle(), $to->getMetaTitle());
        $this->assertEquals($from->getAmount(), $to->getAmount());

        $this->assertNotEquals($fromText, $to->getRequirementTexts()->first());
        $this->assertInstanceOf(RequirementText::class, $to->getRequirementTexts()->first());
        $this->assertEquals($fromText->getAllowFile(), $to->getRequirementTexts()->first()->getAllowFile());
        $this->assertEquals($fromText->getTitle(), $to->getRequirementTexts()->first()->getTitle());
        $this->assertEquals($fromText->getDescription(), $to->getRequirementTexts()->first()->getDescription());

        $this->assertNotEquals($fromFile, $to->getRequirementFiles()->first());
        $this->assertNotEquals($fromFile, $to->getRequirementFiles()->first());
        $this->assertInstanceOf(RequirementFile::class, $to->getRequirementFiles()->first());
        $this->assertEquals($fromFile->getTitle(), $to->getRequirementFiles()->first()->getTitle());
        $this->assertEquals($fromFile->getDescription(), $to->getRequirementFiles()->first()->getDescription());

        $this->assertNotEquals($fromImage, $to->getRequirementImages()->first());
        $this->assertNotEquals($fromImage, $to->getRequirementImages()->first());
        $this->assertInstanceOf(RequirementImage::class, $to->getRequirementImages()->first());
        $this->assertEquals($fromImage->getTitle(), $to->getRequirementImages()->first()->getTitle());
        $this->assertEquals($fromImage->getDescription(), $to->getRequirementImages()->first()->getDescription());
        $this->assertEquals($fromImage->getMinWidth(), $to->getRequirementImages()->first()->getMinWidth());
        $this->assertEquals($fromImage->getMaxWidth(), $to->getRequirementImages()->first()->getMaxWidth());
        $this->assertEquals($fromImage->getMinHeight(), $to->getRequirementImages()->first()->getMinHeight());
        $this->assertEquals($fromImage->getMaxHeight(), $to->getRequirementImages()->first()->getMaxHeight());

        /** @var ApplicationText[] $applicationTexts */
        $applicationTexts = $this->repository->findApplicationRequirements($to, $to->getRequirementTexts()->first());
        $this->assertEmpty($applicationTexts);

        /** @var ApplicationFile[] $applicationFiles */
        $applicationFiles = $this->repository->findApplicationRequirements($to, $to->getRequirementFiles()->first());
        $this->assertEmpty($applicationFiles);

        /** @var ApplicationImage[] $applicationImages */
        $applicationImages = $this->repository->findApplicationRequirements($to, $to->getRequirementImages()->first());
        $this->assertEmpty($applicationImages);
    }

    public function testDeleteScholarship()
    {
        $scholarship = $this->generateScholarship();

        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
        ]);

        $this->service->deleteScholarship($scholarship->getScholarshipId());

        $this->assertDatabaseMissing('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
        ]);
    }

    public function testDeleteScholarshipByExternalId()
    {
        $externalScholarshipId = 12345;
        $scholarship = $this->generateScholarship(ScholarshipStatus::PUBLISHED, $externalScholarshipId);

        $this->assertDatabaseHas('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
        ]);

        $this->service->deleteScholarshipByExternalId($scholarship->getExternalScholarshipId());

        $this->assertDatabaseMissing('scholarship', [
            'scholarship_id' => $scholarship->getScholarshipId(),
        ]);
    }

    public function testFilterEligible()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $r = $this->service->filterEligible(
            [
                $scholarship->getScholarshipId(),
                $scholarship2->getScholarshipId(),
                555 // some note eligible id
            ],
            $account
        );

        $this->assertTrue(count($r) === 2);
    }

    public function testAutomaticScholarship()
    {
        static::$truncate[] = 'application';

        $this->withoutEvents();

        $this->app->singleton(\App\Services\PubSub\TransactionalEmailService::class, function () {
            return $this->createMock(TransactionalEmailService::class);
        });

        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $applicationService = $this->app->make(ApplicationService::class);
        $automaticScholarshipListener = new ApplyForDYIScholarshipListener($this->em, $applicationService);

        $automaticScholarship = $this->generateScholarship();
        $automaticScholarship->setIsAutomatic(true);
        $this->em->persist($automaticScholarship);
        $this->em->flush();
        $automaticScholarshipListener->onUpdateAccount(new ElbCacheAccountEvent(new AccountEvent($account)));

        $profile = $this->setProfileCompleteness($profile, 90);

        $automaticScholarshipListener->onUpdateAccount(new ElbCacheAccountEvent(new AccountEvent($account)));

        $this->assertDatabaseHas('application', [
            'scholarship_id' => $automaticScholarship->getScholarshipId(),
            'account_id' => $account->getAccountId()
        ]);



    }

    public function testAutomaticScholarshipNotApplyForNewFreemiumUser()
    {
        static::$truncate[] = 'application';

        $this->withoutEvents();

        $this->app->singleton(\App\Services\PubSub\TransactionalEmailService::class, function () {
            return $this->createMock(TransactionalEmailService::class);
        });

        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $applicationService = $this->app->make(ApplicationService::class);
        $automaticScholarshipListener = new ApplyForDYIScholarshipListener($this->em, $applicationService);

        $automaticScholarship = $this->generateScholarship();
        $automaticScholarship->setIsAutomatic(true);
        $this->em->persist($automaticScholarship);
        $this->em->flush();

        $freemiumPackage = $this->generatePackage(Package::EXPIRATION_TYPE_NO_EXPIRY)
            ->setIsScholarshipsUnlimited(false)
            ->setIsFreemium(true)
            ->setFreemiumRecurrencePeriod(Package::EXPIRATION_PERIOD_TYPE_NEVER)
            ->setFreemiumRecurrenceValue(0)
            ->setFreemiumCredits(1);
        $freemiumPackage->setAlias(Package::FREEMIUM_MVP_ALIAS);
        $subscription = $this->generateSubscription($freemiumPackage, $account);
        $this->em->flush();
        $profile = $this->setProfileCompleteness($profile, 90);

        $automaticScholarshipListener->onUpdateAccount(new ElbCacheAccountEvent(new AccountEvent($account)));

        $this->assertDatabaseMissing('application', [
            'scholarship_id' => $automaticScholarship->getScholarshipId(),
            'account_id' => $account->getAccountId()
        ]);
    }


    public function testAutomaticScholarshipNotApplyForOldFreemiumUser()
    {
        static::$truncate[] = 'application';

        $this->withoutEvents();

        $this->app->singleton(\App\Services\PubSub\TransactionalEmailService::class, function () {
            return $this->createMock(TransactionalEmailService::class);
        });

        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $applicationService = $this->app->make(ApplicationService::class);
        $automaticScholarshipListener = new ApplyForDYIScholarshipListener($this->em, $applicationService);

        $automaticScholarship = $this->generateScholarship();
        $automaticScholarship->setIsAutomatic(true);
        $this->em->persist($automaticScholarship);
        $this->em->flush();

        $freemiumPackage = $this->generatePackage(Package::EXPIRATION_TYPE_NO_EXPIRY)
            ->setIsScholarshipsUnlimited(false)
            ->setIsFreemium(true)
            ->setFreemiumRecurrencePeriod(Package::EXPIRATION_PERIOD_TYPE_NEVER)
            ->setFreemiumRecurrenceValue(0)
            ->setFreemiumCredits(1);

        $subscription = $this->generateSubscription($freemiumPackage, $account);
        $this->em->flush();
        $profile = $this->setProfileCompleteness($profile, 90);

        $automaticScholarshipListener->onUpdateAccount(new ElbCacheAccountEvent(new AccountEvent($account)));

        $this->assertDatabaseHas('application', [
            'scholarship_id' => $automaticScholarship->getScholarshipId(),
            'account_id' => $account->getAccountId()
        ]);
    }

    /**
     * @param int $fieldPercent
     * @param \App\Entity\Profile $profile
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function setProfileCompleteness(Profile $profile, int $fieldPercent)
    {
        $fields = [
            "firstName" => "f_name",
            "lastName" => "l_name",
            "phone" => "0931033434",
            "gender" => "male",
            "city" => "city",
            "address" => "address",
            "zip" => "10001",
            "studyOnline" => "yes",
            "enrollmentYear" => 2018,
            "enrollmentMonth" => 10,
            "graduationYear" => 2018,
            "graduationMonth" => 10,
            "highschoolGraduationYear" => 2003,
            "highschoolGraduationMonth" => 05,
            "highSchool" => "highschool",
            "university" => 'university',
            "militaryAffiliation" => 1,
            "careerGoal" => "1",
            "citizenship" => "1",
            "ethnicity" => "1",
            "country" => "1",
            "state" => "1",
            "schoolLevel" => "1",
            "degree" => "1",
            "degreeType" => "1",
            "gpa" => "3.0",
            "dateOfBirth" => now()
        ];

        $targetFieldCount = round((count($fields) / 100) * $fieldPercent);

        $fields = array_chunk($fields, $targetFieldCount, true)[0];

        foreach ($fields as $field => $value) {
            $method = 'set' . ucfirst($field);
            if (method_exists($profile, $method)) {
                $profile->{$method}($value);
            }
        }

        $this->em->persist($profile);
        $this->em->flush($profile);

        return $profile;
    }
}
