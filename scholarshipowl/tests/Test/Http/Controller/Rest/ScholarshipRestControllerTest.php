<?php namespace Test\Http\Controller\Rest;

use App\Entity\Application;
use App\Entity\Eligibility;
use App\Entity\Field;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use Carbon\Carbon;

class ScholarshipRestControllerTest extends TestCase
{

    use EntityGenerator;

    public function setUp(): void
    {
        static::$truncate[] = 'accounts_favorite_scholarships';
        static::$truncate[] = 'scholarship';
        static::$truncate[] = 'eligibility';
        static::$truncate[] = 'application';
        static::$truncate[] = 'eligibility_cache';

        parent::setUp();
    }

    public function testScholarshipEligibleWithRequirementsAndApplicationRequirements()
    {
        $this->actingAs($account = $this->generateAccount('test@test2.com'));
        $scholarship = $this->generateScholarship();
        $requirementText = $this->generateRequirementText($scholarship);
        $requirementFile = $this->generateRequirementFile($scholarship);
        $requirementImage = $this->generateRequirementImage($scholarship);
        $applicationText = $this->generateApplicationText($requirementText, null, 'test', $account);
        $applicationFile = $this->generateApplicationFile($this->generateAccountFile($account), $requirementFile);
        $applicationImage = $this->generateApplicationImage($this->generateAccountFile($account), $requirementImage);

        // Should not be returned
        $this->generateApplicationFile($this->generateAccountFile(), $requirementFile);

        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['scholarshipId' => $scholarship->getScholarshipId()]], ['count' => 1]);
        $this->seeJsonSuccessSubset($resp, [[
            'requirements' => [
                'texts' => [[
                    'id' => $requirementText->getId(),
                    'scholarshipId' => $scholarship->getScholarshipId(),
                ]],
                'files' => [[
                    'id' => $requirementFile->getId(),
                    'scholarshipId' => $scholarship->getScholarshipId(),
                ]],
                'images' => [[
                    'id' => $requirementImage->getId(),
                    'scholarshipId' => $scholarship->getScholarshipId(),
                ]],
            ],
        ]]);
        $this->seeJsonSuccessSubset($resp, [[
            'application' => [
                'status' => 3,
                'texts' => [[
                    'id' => $applicationText->getId(),
                    'scholarshipId' => $scholarship->getScholarshipId(),
                ]],
                'files' => [[
                    'id' => $applicationFile->getId(),
                    'scholarshipId' => $scholarship->getScholarshipId(),
                ]],
                'images' => [[
                    'id' => $applicationImage->getId(),
                    'scholarshipId' => $scholarship->getScholarshipId(),
                ]],
            ],
        ]]);

        $this->assertCount(1, $this->decodeResponseJson($resp)['data'][0]['application']['files'] ?? []);
    }

    public function testShowAction()
    {
        $scholarship = $this->generateScholarship();
        $baseUrl = \URL::to('/');

        $this->actingAs($this->generateAdminAccount());
        $resp = $this->get(route('rest::v1.scholarship.show', $scholarship->getScholarshipId()));
        $this->seeJsonSuccessSubset($resp, [
            "amount" => 10,
            "description" => null,
            "isFavorite" => 0,
            "isSent" => 0,
            "derivedStatus" => null,
            "expirationDate" => [
                "date" =>  $scholarship->getExpirationDate()->format('Y-m-d H:i:s.u'),
                "timezone" => "Europe/Berlin",
                "timezone_type" => 3
            ],
            'externalUrl' => 'test',
            'TOSUrl' => null,
            'PPUrl' => null,
            "scholarshipId" => 1,
            "logo" => "${baseUrl}/assets/img/scholarship/college.jpg",
            "title" => "test",
            "url" => "${baseUrl}/scholarships/1-test",
            'requirements' => [
                'texts' => [],
                'files' => [],
                'images' => [],
                'inputs' => [],
            ],
            'isRecurrent' => false,
            'image' => null,
        ]);
    }

    public function testEligibleAction()
    {
        static::$truncate[] = 'eligibility';
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->generateEligibility($scholarship, Field::ADDRESS, Eligibility::TYPE_VALUE, 22);
        $this->generateEligibility($scholarship, Field::ADDRESS, Eligibility::TYPE_VALUE, 22);
        $this->generateScholarship();
        \EntityManager::flush();

        $this->actingAs($account);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSubset($resp, [
            'data' => [['scholarshipId' => 2]],
            'meta' => ['count' => 1],
        ]);
    }

    public function testEligibleScholarshipApplicationStatus()
    {
        $this->actingAs($account = $this->generateAccount('test2@test.com'));
        $scholarship = $this->generateScholarship();

        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_READY_TO_SUBMIT]]]);

        $requirementText = $this->generateRequirementText($scholarship);
        $requirementFile = $this->generateRequirementFile($scholarship);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_INCOMPLETE]]]);

        $applicationText = $this->generateApplicationText($requirementText, null, 'test', $account);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_IN_PROGRESS]]]);

        \EntityManager::remove($applicationText);
        \EntityManager::flush($applicationText);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_INCOMPLETE]]]);

        $applicationFile = $this->generateApplicationFile($this->generateAccountFile($account), $requirementFile);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_IN_PROGRESS]]]);

        \EntityManager::remove($applicationFile);
        \EntityManager::flush($applicationFile);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_INCOMPLETE]]]);

        $this->generateApplicationText($requirementText, null, 'test', $account);
        $this->generateApplicationFile($this->generateAccountFile($account), $requirementFile);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_READY_TO_SUBMIT]]]);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->seeJsonSuccessSubset($resp, [['application' => ['status' => Scholarship::APPLICATION_STATUS_READY_TO_SUBMIT]]]);
    }

    public function testIndexSimpleResponse()
    {
        $scholarship = $this->generateScholarship();

        $baseUrl = \URL::to('/');
        $this->actingAs($this->generateAdminAccount());
        $resp = $this->get(route('rest::v1.scholarship.index'));

        $expected = [
            'status' => 200,
            'data' => [
                [
                    "scholarshipId" => 1,
                    "url" => "${baseUrl}/scholarships/1-test",
                    "logo" => "${baseUrl}/assets/img/scholarship/college.jpg",
                    "title" => "test",
                    'description' => null,
                    "isFavorite" => 0,
                    'externalUrl' => 'test',
                    'amount' => 10,
                    'expirationDate' => [
                        "date" =>  $scholarship->getExpirationDate()->format('Y-m-d H:i:s').".000000",
                        "timezone" => "Europe/Berlin",
                        "timezone_type" => 3
                    ],
                    'TOSUrl' => null,
                    'PPUrl' => null,
                    'requirements' => [
                        'texts' => [],
                        'files' => [],
                        'images' => [],
                        'inputs' => [],
                    ],
                    'isRecurrent' => false,
                    'image' => null,
                ]
            ],
            'meta' => [
                 'count' => 1,
                'start' => 0,
                'limit' => 1000
            ]
        ];

        $this->seeJsonSubset($resp, $expected);
    }

    public function testIndexWithSortByAndSortDirection()
    {
        $this->actingAs($this->generateAdminAccount());
        $this->generateScholarship();
        $this->generateScholarship();

        $resp = $this->get(route('rest::v1.scholarship.index'));
        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 1],
                ['scholarshipId' => 2],
            ],
        ]);

        $params = ['sort_by' => 'scholarshipId', 'sort_direction' => 'DESC'];
        $this->actingAs($this->generateAccount(), 'api');
        $resp = $this->get(route('rest::v1.scholarship.index') .'?'. http_build_query($params));

        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 2],
                ['scholarshipId' => 1],
            ]
        ]);
    }

    public function testIndexWithSortJson()
    {
        $this->actingAs($this->generateAdminAccount());
        $this->generateScholarship();
        $this->generateScholarship();

        $resp = $this->get(route('rest::v1.scholarship.index'));
        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 1],
                ['scholarshipId' => 2],
            ],
        ]);

        $params = ['sort' => json_encode([['property' => 'scholarshipId', 'direction' => 'DESC']])];
        $resp = $this->get(route('rest::v1.scholarship.index') .'?'. http_build_query($params));
        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 2],
                ['scholarshipId' => 1],
            ]
        ]);
    }

    public function testIndexWithSortArray()
    {
        $this->actingAs($this->generateAdminAccount());
        $this->generateScholarship();
        $this->generateScholarship();

        $resp = $this->get(route('rest::v1.scholarship.index'));
        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 1],
                ['scholarshipId' => 2],
            ],
        ]);

        $params = ['sort' => [['property' => 'scholarshipId', 'direction' => 'DESC']]];
        $resp = $this->get(route('rest::v1.scholarship.index') .'?'. http_build_query($params));
        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 2],
                ['scholarshipId' => 1],
            ]
        ]);
    }

    public function testIndexSimpleFilter()
    {
        $this->actingAs($this->generateAdminAccount());
        $this->generateScholarship();
        $this->generateScholarship();

        $params = ['filter' => [['property' => 'scholarshipId', 'operator' => 'eq', 'value' => 2]]];
        $resp = $this->get(route('rest::v1.scholarship.index') .'?'. http_build_query($params));
        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 2],
            ],
            'meta' => [
                'count' => 1,
            ],
        ]);
    }

    public function testIndexStartAndLimit()
    {
        $this->actingAs($this->generateAdminAccount());
        $this->generateScholarship();
        $this->generateScholarship();
        $this->generateScholarship();
        $this->generateScholarship();
        $this->generateScholarship();

        $params = ['start' => 2, 'limit' => 2];

        $resp = $this->get(route('rest::v1.scholarship.index') .'?'. http_build_query($params));
        $this->seeJsonSubset($resp, [
            'data' => [
                ['scholarshipId' => 3],
                ['scholarshipId' => 4],
            ],
            'meta' => [
                'count' => 5,
                'start' => 2,
                'limit' => 2,
            ]
        ]);
    }


    public function testSetFavoriteScholarship()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->assertDatabaseMissing('accounts_favorite_scholarships', [
            'account_id' => $account->getAccountId() ,
            'scholarship_id' => $scholarship->getScholarshipId(),
        ]);
        $this->generateAndCheckFavoriteScholarship($account, $scholarship);
    }

    public function testSetUnfavoriteScholarship()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->generateAndCheckFavoriteScholarship($account, $scholarship);

        $resp = $this->get(route('rest::v1.scholarship.unfavorite', $scholarship->getScholarshipId()));
        $this->assertTrue($resp->status() === 200);
        $this->assertDatabaseMissing('accounts_favorite_scholarships', [
            'account_id' => $account->getAccountId() ,
            'scholarship_id' => $scholarship->getScholarshipId(),
        ]);
    }

    protected function generateAndCheckFavoriteScholarship($account, $scholarship)
    {
        $this->actingAs($account);
        $resp = $this->get(route('rest::v1.scholarship.favorite', $scholarship->getScholarshipId()));
        $this->assertTrue($resp->status() === 200);
        $this->assertDatabaseHas('accounts_favorite_scholarships', [
            'account_id'     => $account->getAccountId(),
            'scholarship_id' => $scholarship->getScholarshipId(),
            'favorite'       => 1
        ]);
    }

    public function testErrorOnSetFavoriteScholarship(){
        $account = $this->generateAccount();
        $this->actingAs($account);
        $resp = $this->get(route('rest::v1.scholarship.favorite', 2));
        $this->assertTrue($resp->status() === 404); // scholarship not found
    }

    public function testDerivedStatus_noneSunrise()
    {
        $account = $this->generateAccount();
        $this->actingAs($account);
        $externalScholarshipId = '299a5ecf-b577-11e8-ac1a-0a580a080113';
        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, $externalScholarshipId, 111
        );

        $application = $this->generateApplication($scholarship, $account);

        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_SENT);

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        \EntityManager::flush($scholarship);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_DRAW_CLOSED);
    }

    public function testDerivedStatus_NotInvolvedUser()
    {
        $account = $this->generateAccount();
        $this->actingAs($account);
        $externalScholarshipId = '299a5ecf-b577-11e8-ac1a-0a580a080113';
        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, $externalScholarshipId, 111
        );

        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] === '');

        $application = $this->generateApplication($scholarship, $account);
        $externalApplicationId = '99stfhh-6776-ee74-1242-01qwt7460975';
        $application->setExternalApplicationId($externalApplicationId);
        \EntityManager::flush($application);

        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_SENT);

        $application->setExternalStatus(Application::EXTERNAL_STATUS_ACCEPTED);
        \EntityManager::flush($application);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_ACCEPTED);

        $application->setExternalStatus(Application::EXTERNAL_STATUS_ACCEPTED);
        \EntityManager::flush($application);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_ACCEPTED);

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_CHOOSING_WINNER);
        \EntityManager::flush($scholarship);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_CHOOSING);

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_POTENTIAL_WINNER);
        \EntityManager::flush($scholarship);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_CHOOSING);
        $this->assertTrue(empty($this->decodeResponseJson($resp)['data'][0]['winnerFormUrl']));

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_FINAL_WINNER);
        \EntityManager::flush($scholarship);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_CHOSEN);
    }

    public function testDerivedStatus_involvedUser()
    {
        $account = $this->generateAccount();
        $this->actingAs($account);
        $externalScholarshipId = '299a5ecf-b577-11e8-ac1a-0a580a080113';
        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, $externalScholarshipId, 111
        );

        $resp = $this->get(route('rest::v1.scholarship.eligible'));;
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] === '');

        $application = $this->generateApplication($scholarship, $account);
        $externalApplicationId = '99stfhh-6776-ee74-1242-01qwt7460975';
        $application->setExternalApplicationId($externalApplicationId);
        \EntityManager::flush($application);

        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_SENT);

        $application->setExternalStatus(Application::EXTERNAL_STATUS_ACCEPTED);
        \EntityManager::flush($application);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_ACCEPTED);

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        $scholarship->setWinnerFormUrl('https://winner-form-url');
        $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_POTENTIAL_WINNER);
        \EntityManager::flush($scholarship);
        $application->setExternalStatus(Application::EXTERNAL_STATUS_POTENTIAL_WINNER);
        \EntityManager::flush($application);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_WON);
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['winnerFormUrl'] == 'https://winner-form-url');

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_CHOOSING_WINNER);
        \EntityManager::flush($scholarship);
        $application->setExternalStatus(Application::EXTERNAL_STATUS_DISQUALIFIED_WINNER);
        \EntityManager::flush($application);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_MISSED);

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_FINAL_WINNER);
        \EntityManager::flush($scholarship);
        $application->setExternalStatus(Application::EXTERNAL_STATUS_DISQUALIFIED_WINNER);
        \EntityManager::flush($application);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_MISSED);

        $scholarship->setStatus(ScholarshipStatus::EXPIRED);
        $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_FINAL_WINNER);
        \EntityManager::flush($scholarship);
        $application->setExternalStatus(Application::EXTERNAL_STATUS_PROVED_WINNER);
        \EntityManager::flush($application);
        $resp = $this->get(route('rest::v1.scholarship.eligible'));
        $this->assertTrue($this->decodeResponseJson($resp)['data'][0]['derivedStatus'] == Scholarship::DERIVED_STATUS_AWARDED);
    }

}
