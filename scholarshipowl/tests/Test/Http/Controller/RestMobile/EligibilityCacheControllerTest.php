<?php

namespace Test\Http\Controller\Rest;

use App\Entity\Domain;
use App\Entity\Eligibility;
use App\Entity\Field;
use App\Events\Account\UpdateAccountEvent;
use App\Services\EligibilityCacheService;
use App\Testing\TestCase;

class EligibilityCacheControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'eligibility_cache';
    }

    public function testGetEligibilityCache()
    {
        $account = $this->generateAccount('t@t.com');
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $this->actingAs($account);

        $resp = $this->call('GET', route('rest::v1.eligibility_cache.get'));

        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                'id',
                'accountId',
                'lastShownScholarshipIds',
                'eligibleScholarshipIds',
                'notSeenScholarshipCount',
                'notSeenScholarshipAmount',
                'notSeenScholarshipIds',
                'eligibleScholarshipAmount',
                'eligibleScholarshipCount',
            ]
        ]);

        $resp = $this->call('PUT', route('rest::v1.eligibility_cache.put'), [
            'last_shown_scholarship_ids' => [
                $scholarship->getScholarshipId(),
                $scholarship2->getScholarshipId()
            ],
        ]);

        $scholarship3 = $this->generateScholarship();

        $resp = $this->call('GET', route('rest::v1.eligibility_cache.get', ['fields' => 'notSeenScholarshipIds,notSeenScholarshipCount']));
        $this->seeJsonSuccess($resp, [
            'notSeenScholarshipIds' => [$scholarship3->getScholarshipId()],
            'notSeenScholarshipCount' => 1
        ]);
    }

    public function testUpdateShownScholarships()
    {
        $account = $this->generateAccount('t@t.com');
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $this->actingAs($account);

        $resp = $this->call('PUT', route('rest::v1.eligibility_cache.put'), [
            'last_shown_scholarship_ids' => [
                $scholarship->getScholarshipId(),
                $scholarship2->getScholarshipId()
            ],
        ]);

        $this->assertDatabaseHas('eligibility_cache', [
           'last_shown_scholarship_ids' => $this->castToJson('{"1": 10, "2": 10}'),
           'account_id' => $account->getAccountId(),
        ]);
    }

    /**
     * @return \App\Entity\Account
     */
    protected function generateAccountForTest()
    {
        $account = $this->generateAccount(
            'test@test.com',
            'testFirstName',
            'testLastName',
            'testPassword',
            Domain::SCHOLARSHIPOWL,
            false,
            false);
        return $account;
    }
}
