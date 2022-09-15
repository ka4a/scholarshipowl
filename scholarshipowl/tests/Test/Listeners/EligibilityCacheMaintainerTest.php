<?php

namespace Test\Listeners;

use App\Contracts\Recurrable;
use App\Entity\Domain;
use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\ScholarshipStatus;
use App\Events\Account\UpdateAccountEvent;
use App\Events\Scholarship\ScholarshipExpiredEvent;
use App\Events\Scholarship\ScholarshipPublishedEvent;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Services\ApplicationService;
use App\Services\EligibilityCacheService;
use App\Services\ScholarshipService;
use App\Testing\TestCase;
use Carbon\Carbon;


class EligibilityCacheMaintainerTest extends TestCase
{
    /**
     * @var ScholarshipService
     */
    protected $scholarshipService;

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    /**
     * @var EligibilityCacheRepository
     */
    protected $elbRepository;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'application';
        static::$truncate[] = 'scholarship';

        $this->elbCacheService = $this->app->make(EligibilityCacheService::class);
        $this->elbRepository = \EntityManager::getRepository(EligibilityCache::class);
        $this->applicationService = $this->app->make(ApplicationService::class);
        $this->scholarshipService = $this->app->make(ScholarshipService::class);
    }

    public function testOnApplicationAdd()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->applicationService->applyScholarship($account, $scholarship, true);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testOnScholarshipUpdated()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);


        $scholarship->setIsActive(false);
        \EntityManager::flush($scholarship);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);


        $scholarship->setIsActive(true);
        \EntityManager::flush($scholarship);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true, true));

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testOnScholarshipRecurred()
    {
        $account = $this->generateAccount();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $scholarship = $this->generateScholarship();
        $scholarship->setStartDate(Carbon::now()->subDay(7));
        $scholarship->setExpirationDate(Carbon::now());
        $scholarship->setIsRecurrent(true);
        $scholarship->setRecurringType(Recurrable::PERIOD_TYPE_WEEK);
        $scholarship->setRecurringValue(1);
        \EntityManager::flush($scholarship);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $scholarship2 = $this->scholarshipService->recur($scholarship);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        // now new scholarships in cache before it's published
        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $scholarship2->setStatus(ScholarshipStatus::PUBLISHED);
        \EntityManager::flush($scholarship2);
        \Event::dispatch(new ScholarshipPublishedEvent($scholarship2));

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testOnScholarshipExpired()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);


        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        \EntityManager::flush($scholarship);
        \Event::dispatch(new ScholarshipExpiredEvent($scholarship));

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testOnScholarshipDeleted()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);


        /** @var ScholarshipService $scholarshipService */
        $scholarshipService = app(ScholarshipService::class);
        $scholarshipService->deleteScholarship($scholarship->getScholarshipId());

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testOnScholarshipPublished()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship(ScholarshipStatus::UNPUBLISHED);
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);


        $scholarship->setStatus(ScholarshipStatus::PUBLISHED);
        \EntityManager::flush($scholarship);
        \Event::dispatch(new ScholarshipPublishedEvent($scholarship));

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testOnAccountUpdate()
    {
        $scholarship = $this->generateScholarship();
        $account = $this->generateAccount('t@t.com');
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->elbRepository->purgeEligibilityCache([$account->getAccountId()]);
        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        \Event::dispatch(new UpdateAccountEvent($account, 'my-account'));

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }
}
