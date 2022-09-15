<?php namespace Test\Services;

use App\Entity\Country;
use App\Entity\Domain;
use App\Entity\Eligibility;
use App\Entity\EligibilityCache;
use App\Entity\Field;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\ScholarshipStatus;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Services\EligibilityCacheService;
use App\Testing\TestCase;
use Carbon\Carbon;

class EligibilityCacheServiceTest extends TestCase
{
    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    /**
     * @var EligibilityCacheRepository
     */
    protected $elbCacheRepo;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'eligibility_cache';
        static::$truncate[] = 'scholarship';

        $this->elbCacheService = $this->app->make(EligibilityCacheService::class);
        $this->elbCacheRepo = \EntityManager::getRepository(EligibilityCache::class);
    }

    public function testRemoveFromEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId());

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->elbCacheService->removeFromEligibilityCache($scholarship2);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);
    }

    public function testRotateScholarship()
    {
        $account = $this->generateAccount('t@t.com');
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $account2 = $this->generateAccount('t2@t.com');
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId());

        $scholarship = $this->generateScholarship();
        $this->elbCacheService->rotateScholarship($scholarship);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId()
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account2->getAccountId()
        ]);

        $profile = $account->getProfile();
        $profile->setZip(77777);
        \EntityManager::flush($profile);

        $this->generateEligibility($scholarship, Field::ZIP, Eligibility::TYPE_VALUE, 77777);

        $this->elbCacheService->rotateScholarship($scholarship);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId()
        ]);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account2->getAccountId()
        ]);
    }

    public function testAddToEligibilityCache()
    {
        $account = $this->generateAccount();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->addToEligibilityCache($scholarship);
        $scholarship2 = $this->generateScholarship();
        $this->elbCacheService->addToEligibilityCache($scholarship2);

        $this->assertDatabaseHas('eligibility_cache', [
           //[\DB::raw('JSON_CONTAINS(eligible_scholarship_ids, \'{"1": 10, "2": 10}\')'), '=', 1],
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId()
        ]);
    }

    public function testGetEligibleScholarshipIds()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');
        $profile2 = $account2->getProfile();
        $profile2->setGpa('3.5');
        \EntityManager::flush($profile2);

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        // make it not eligible to account1
        $scholarship3 = $this->generateScholarship();
        $this->generateEligibility($scholarship3, Field::GPA, Eligibility::TYPE_REQUIRED);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));

        $ids1 = $this->elbCacheService->getEligibleScholarshipIds([$account->getAccountId()])[$account->getAccountId()];
        $this->assertTrue(count($ids1) === 2 && !in_array($scholarship3->getScholarshipId(), $ids1));

        $ids2 = $this->elbCacheService->getEligibleScholarshipIds([$account2->getAccountId()])[$account2->getAccountId()];
        $this->assertTrue(count($ids2) === 3);
    }

    public function testGetEligibleCount()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');;

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();


        $r = $this->elbCacheService->getEligibleCount([$account->getAccountId(), $account2->getAccountId()]);
        $this->assertTrue($r[$account->getAccountId()] === 2);
        $this->assertTrue($r[$account2->getAccountId()] === 2);

        $r = $this->elbCacheService->getEligibleCount([$account->getAccountId(), $account2->getAccountId()], [$scholarship->getScholarshipId()]);
        $this->assertTrue($r[$account->getAccountId()] === 1);
        $this->assertTrue($r[$account2->getAccountId()] === 1);
    }

    public function testGetEligibleAmount()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();


        $r = $this->elbCacheService->getEligibleAmount([$account->getAccountId(), $account2->getAccountId()]);
        $this->assertTrue($r[$account->getAccountId()] === 20);
        $this->assertTrue($r[$account2->getAccountId()] === 20);

        $r = $this->elbCacheService->getEligibleAmount([$account->getAccountId(), $account2->getAccountId()], [$scholarship->getScholarshipId()]);
        $this->assertTrue($r[$account->getAccountId()] === 10);
        $this->assertTrue($r[$account2->getAccountId()] === 10);
    }

    public function testGetAccountEligibleAmount()
    {
        $account = $this->generateAccount('t@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $amount = $this->elbCacheService->getAccountEligibleAmount($account->getAccountId());
        $this->assertTrue($amount === 20);

        $amount = $this->elbCacheService->getAccountEligibleAmount($account->getAccountId(), [$scholarship->getScholarshipId()]);
        $this->assertTrue($amount === 10);
    }

    public function testGetAccountEligibleCount()
    {
        $account = $this->generateAccount('t@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $amount = $this->elbCacheService->getAccountEligibleCount($account->getAccountId());
        $this->assertTrue($amount === 2);

        $amount = $this->elbCacheService->getAccountEligibleCount($account->getAccountId(), [$scholarship->getScholarshipId()]);
        $this->assertTrue($amount === 1);
    }

    public function testUpdateAccountEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com', 'John', 'Doe', 'pass123', Domain::SCHOLARSHIPOWL, false, false);
        // eligibility cache maintained only for accounts with subscriptions
        $this->generateSubscription(null, $account);

        $scholarship = $this->generateScholarship(ScholarshipStatus::PUBLISHED, 0, 0, false);
        $scholarship2 = $this->generateScholarship(ScholarshipStatus::PUBLISHED, 0, 0, false);


        /** @var EligibilityCache $elbCacheItem */
        $elbCacheItem = $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());
        $this->assertTrue(count($elbCacheItem->getEligibleScholarshipIds()) === 2);
    }

    public function testGetAccountEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        /** @var EligibilityCache $item */
        $item = $this->elbCacheService->getAccountEligibilityCache($account->getAccountId());
        $this->assertTrue(count($item->getEligibleScholarshipIds()) === 2);

        $data = $this->elbCacheService->getAccountEligibilityCache($account->getAccountId(), EligibilityCacheService::FETCH_AS_ARRAY);
        $this->assertTrue(count($data['eligibleScholarshipIds']) === 2);
        $this->assertTrue(array_key_exists($scholarship->getScholarshipId(), $data['eligibleScholarshipIds']));

        // in this mode sscholarshipIds returned along with its amount
        $data = $this->elbCacheService->getAccountEligibilityCache($account->getAccountId(), EligibilityCacheService::FETCH_AS_FULL_ARRAY);
        $this->assertTrue(array_sum($data['eligibleScholarshipIds']) === 20);

        // generate account without triggering an event, so do not create eligibility cache.
        // But the cache must be created while getting an item
        $account2 = $this->generateAccount('t2@t.com', 'John', 'Doe', 'pass123', Domain::SCHOLARSHIPOWL, false, false);
        $this->assertDatabaseMissing('eligibility_cache', [
           'account_id' => $account2->getAccountId(),
        ]);
        $item2 = $this->elbCacheService->getAccountEligibilityCache($account2->getAccountId());
        $this->assertTrue(count($item2->getEligibleScholarshipIds()) === 2);
    }

    public function testUpdateAccountLastShownScholarships()
    {
        $account = $this->generateAccount('t@t.com');
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        /** @var EligibilityCache $resultItem */
        $resultItem = $this->elbCacheService->updateAccountLastShownScholarships(
            $account->getAccountId(), [$scholarship->getScholarshipId(), $scholarship2->getScholarshipId()]
        );

        $this->assertTrue(count($resultItem->getLastShownScholarshipIds()) === 2);
    }

    public function testGetAccountEligibleScholarshipIds()
    {
        $account = $this->generateAccount('t@t.com');
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $this->assertTrue(count($this->elbCacheService->getAccountEligibleScholarshipIds($account->getAccountId())) === 2);
        $this->assertTrue(count($this->elbCacheService->getAccountEligibleScholarshipIds($account->getAccountId(), [$scholarship->getScholarshipId()])) === 1);
    }

    public function testFetchScholarshipEligibleAccountIds()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->generateEligibility($scholarship, Field::COUNTRY_OF_STUDY, Eligibility::TYPE_VALUE, Country::USA);
        $this->generateEligibility($scholarship, Field::GPA, Eligibility::TYPE_VALUE, '3.5');
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 0);
        $profile->setStudyCountry1(Country::USA);
        $profile->setGpa('3.5');
        \EntityManager::flush($profile);


        $this->generateEligibility($scholarship, Field::EMAIL, Eligibility::TYPE_VALUE, $account->getEmail());
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);


        $this->generateEligibility($scholarship, Field::DATE_OF_BIRTH, Eligibility::TYPE_REQUIRED);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 0);
        $profile->setDateOfBirth(new \DateTime());
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);


        $this->generateEligibility($scholarship, Field::CAREER_GOAL, Eligibility::TYPE_GREATER_THAN, 5);
        $profile->setCareerGoal(7);
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);

        $this->generateEligibility($scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_IN, '1,2,3');
        $profile->setDegreeType(4);
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 0);
        $profile->setDegreeType(2);
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);


        $this->generateEligibility($scholarship, Field::ZIP, Eligibility::TYPE_NIN, '333333,444444');
        $profile->setZip(444444);
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 0);
        $profile->setZip(555555);
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);

        $this->generateEligibility($scholarship, Field::ETHNICITY, Eligibility::TYPE_BETWEEN, '1,3');
        $profile->setEthnicity(4);
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 0);
        $profile->setEthnicity(2);
        \EntityManager::flush($profile);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);
    }

    public function testFetchScholarshipEligibleAccountIds_filterOutWithoutCache()
    {
        $account1 = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');
        $scholarship = $this->generateScholarship();

        $this->elbCacheService->updateAccountEligibilityCache($account1->getAccountId(), true);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship, 1000, null, false);
        // should be: account1, account2
        $this->assertTrue(count(iterator_to_array($r)[0]) === 2);

        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship, 1000, null, true);
        // should be: account1
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);

        $this->elbCacheRepo->purgeEligibilityCache([$account1->getAccountId(), $account1->getAccountId()]);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship, 1000, null, true);
        // should be no accounts with cache
        $this->assertTrue(count(iterator_to_array($r)[0]) === 0);

        $this->elbCacheService->updateAccountEligibilityCache($account1->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId(), true);
        /** @var EligibilityCacheRepository $elbCacheRepo */
        $elbCacheRepo = \EntityManager::getRepository(EligibilityCache::class);
        $elbCacheRepo->purgeEligibilityCache([$account2->getAccountId()]);
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship, 1000, null, true);
        // should be: account1
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);

        $account3 = $this->generateAccount('t3@t.com');
        $account4 = $this->generateAccount('t4@t.com');

        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship, 1000, null, false);
        // should be: account1, account2, account3, account4
        $this->assertTrue(count(iterator_to_array($r)[0]) === 4);

        $this->elbCacheRepo->purgeEligibilityCache([$account3->getAccountId()]);
        // should be: account1
        $r = $this->elbCacheService->fetchScholarshipEligibleAccountIds($scholarship, 1000, null, true);
        $this->assertTrue(count(iterator_to_array($r)[0]) === 1);
    }
}
