<?php

namespace Test\Entity\Repository;

use App\Entity\Domain;
use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Repository\EntityRepository;
use App\Entity\ScholarshipStatus;
use App\Services\EligibilityCacheService;
use App\Services\EligibilityService;
use App\Testing\TestCase;

class EligibilityCacheRepositoryTest extends TestCase
{
    /**
     * @var EligibilityCacheRepository
     */
    protected $elbCacheRepo;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'eligibility_cache';
        static::$truncate[] = 'scholarship';

        $this->elbCacheRepo = \EntityManager::getRepository(EligibilityCache::class);
        $this->elbCacheService = app(EligibilityCacheService::class);
    }


    public function testPurgeEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');
        $account3 = $this->generateAccount('t3@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account3->getAccountId(), true);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account3->getAccountId(),
        ]);


        $this->elbCacheRepo->purgeEligibilityCache([$account2->getAccountId(), $account3->getAccountId()]);

        $this->assertDatabaseMissing('eligibility_cache', [
           'account_id' => $account2->getAccountId(),
        ]);

        $this->assertDatabaseMissing('eligibility_cache', [
           'account_id' => $account3->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testRemoveScholarshipFromAccounts()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');
        $account3 = $this->generateAccount('t3@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account3->getAccountId(), true);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account3->getAccountId(),
        ]);

        $this->elbCacheRepo->removeScholarshipFromAccounts($scholarship2, [$account2->getAccountId(), $account3->getAccountId()]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account3->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account3->getAccountId(),
        ]);
    }


    public function testAddScholarshipToExistingItems()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId(), true);

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship(ScholarshipStatus::PUBLISHED, 0, 0, false);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->elbCacheRepo->addScholarshipToExistingItems($scholarship2, [$account->getAccountId(), $account2->getAccountId()]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        // make sure we do not add a scholarship double time
        $this->elbCacheRepo->addScholarshipToExistingItems($scholarship2, [$account->getAccountId(), $account2->getAccountId()]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);
    }

    public function testFetchAccountEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com');

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        /** @var EligibilityCache $item */
        $item = $this->elbCacheRepo->fetchAccountEligibilityCache($account->getAccountId());

        $this->assertTrue(count($item->getEligibleScholarshipIds()) === 2);

        $account2 = $this->generateAccount('t2@t.com', 'John', 'Doe', '123', Domain::SCHOLARSHIPOWL, false, false);
        $this->assertTrue($this->elbCacheRepo->fetchAccountEligibilityCache($account2->getAccountId()) === null);
    }

    public function testFetchAccountsEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com');
        $account2 = $this->generateAccount('t2@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId(), true);

        $items = $this->elbCacheRepo->fetchAccountsEligibilityCache([$account->getAccountId(), $account2->getAccountId()]);

        $this->assertTrue(count($items) === 2);
    }

    public function testUpdateLastShownScholarships()
    {
        $account = $this->generateAccount('t@t.com');
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);

        $item = $this->elbCacheRepo->findOneBy(['account' => $account->getAccountId()]);

        /** @var EligibilityCache $resultItem */
        $resultItem = $this->elbCacheRepo->updateLastShownScholarships($item, [
            [
                'scholarship_id' => $scholarship->getScholarshipId(),
                'amount' => $scholarship->getAmount()
            ],
            [
                'scholarship_id' => $scholarship2->getScholarshipId(),
                'amount' => $scholarship2->getAmount()
            ],
        ]);

        $this->assertTrue(count($resultItem->getLastShownScholarshipIds()) === 2);
    }

    public function testRemoveStaleCache()
    {
        $account = $this->generateAccount('t@t.com');
        $this->generateSubscription(null, $account);
        $account2 = $this->generateAccount('t2@t.com');

        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);
        $this->elbCacheService->updateAccountEligibilityCache($account2->getAccountId(), true);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);

        $this->elbCacheRepo->removeStaleCache();

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        $this->assertDatabaseMissing('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account2->getAccountId(),
        ]);
    }

    public function testUpdateAccountEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com', 'John', 'Doe', 'pass123', Domain::SCHOLARSHIPOWL, false, false);
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $scholarshipsData = [
            [
                'scholarship_id' => $scholarship->getScholarshipId(),
                'amount' => $scholarship->getAmount()
            ],
            [
                'scholarship_id' => $scholarship2->getScholarshipId(),
                'amount' => $scholarship2->getAmount()
            ]
        ];

        $this->elbCacheRepo->updateAccountEligibilityCache($scholarshipsData, $account->getAccountId(), true);

        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);

        // make sure on race condition a unique constraint violation is not thrown, so emulate double insert
        $scholarshipsData[1]['amount'] = 20;
        $this->elbCacheRepo->updateAccountEligibilityCache($scholarshipsData, $account->getAccountId(), true);
        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 20}'),
           'account_id' => $account->getAccountId(),
        ]);

        // make sure update (not create) works ok
        $scholarshipsData[1]['amount'] = 30;
        $this->elbCacheRepo->updateAccountEligibilityCache($scholarshipsData, $account->getAccountId());
        $this->assertDatabaseHas('eligibility_cache', [
           'eligible_scholarship_ids' => $this->castToJson('{"1": 10, "2": 30}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    public function testGetNotPersisted()
    {
        $account = $this->generateAccount('t@t.com', 'John', 'Doe', 'pass123', Domain::SCHOLARSHIPOWL, false, false);
        $scholarship = $this->generateScholarship();

        /** @var EligibilityService $elbService */
        $elbService = app(EligibilityService::class);
        $scholarshipsData = $elbService->fetchEligibleScholarshipsData($account->getAccountId());

        $item = $this->elbCacheRepo->getNotPersisted($scholarshipsData, $account->getAccountId());

        $this->assertTrue($item instanceof EligibilityCache);
        $this->assertTrue($item->getEligibleScholarshipIds(true)[0] === $scholarship->getScholarshipId());
    }
}
