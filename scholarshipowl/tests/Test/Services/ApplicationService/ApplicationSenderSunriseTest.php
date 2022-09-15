<?php

namespace Test\Services\ApplicationService;

use App\Entity\Eligibility;
use App\Entity\Field;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Services\ApplicationService\ApplicationSenderSunrise;
use App\Testing\TestCase;
use App\Traits\SunriseSync;
use GuzzleHttp\Psr7\Response;

class ApplicationSenderSunriseTest extends TestCase
{
    use SunriseSync;

    /**
     * @var ApplicationSenderSunrise
     */
    protected $sender;

    public function setUp(): void
    {
        parent::setUp();

        $this->sender = new ApplicationSenderSunrise();

        static::$truncate[] = 'scholarship';
        static::$truncate[] = 'application';
        static::$truncate[] = 'eligibility';
    }

    public function testSendApplication()
    {
        static::$truncate[] = 'application_special_eligibility';
        static::$truncate[] = 'requirement_image';

        $externalScholarshipId = 1234567;
        $externalApplicationId = 555;

        $this->setMockHttpClient(
            $this->sender,
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'expires_in' => 12960000,
                'access_token' => md5(microtime())

            ])),
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['id' => $externalApplicationId]]))
        );

        $scholarship = $this->generateScholarship(ScholarshipStatus::PUBLISHED, $externalScholarshipId);
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $phone = '(123) 456 - 7891';
        $profile->setPhone($phone);
        \EntityManager::flush($account);

        $this->generateEligibilities([
            [$scholarship, Field::AGE, Eligibility::TYPE_GREATER_THAN, 15],
            [$scholarship, Field::DEGREE, Eligibility::TYPE_GREATER_THAN, 2],
            [$scholarship, Field::STATE, Eligibility::TYPE_GREATER_THAN, 2],
            [$scholarship, Field::PHONE, Eligibility::TYPE_REQUIRED, '', true],
        ]);

        $scholarship = $this->sender->prepareScholarship($scholarship, $account);

        // see the correct structure of the data without multipart (if scholarship has no requirements an application is send as not multipart form data)
        $data = $this->sender->prepareSubmitData($scholarship, $account, ['state' => 3]);

        $this->assertTrue(isset($data['data']['attributes']) && array_key_exists('state', $data['data']['attributes']));
        $this->assertTrue($data['data']['attributes']['source'] === 'sowl');
        // optional eligibilities must be included too
        $this->assertTrue($data['data']['attributes']['phone'] === $phone);


        $accountPicRequirement = $this->generateRequirementImage($scholarship, $this->requirementNameMap()['profilepic']);
        $accountPicFile = $this->generateAccountFile($account, 'test-profile-pic.png');
        $scholarship->setApplicationImages([$this->generateApplicationImage($accountPicFile, $accountPicRequirement)]);
        $data = $this->sender->prepareSubmitData($scholarship, $account, ['state' => 3]);


        $requirementSpElb = $this->generateRequirementSpecialEligibility($scholarship);
        $requirementSpElbExternalId = 348644;
        $requirementSpElb->setExternalId($requirementSpElbExternalId);
        \EntityManager::flush($requirementSpElb);
        $scholarship->setApplicationSpecialEligibility([$this->generateApplicationSpecialEligibility($requirementSpElb, $account, 1)]);

        $data = $this->sender->prepareSubmitData($scholarship, $account, ['state' => 3]);

        //see the correct structure of the data with multipart
        $this->assertTrue($data[0]['name'] === "data[attributes][requirements][{$accountPicRequirement->getExternalId()}]");
        $this->assertTrue($data[count($data) - 1]['name'] === 'data[attributes][source]' && $data[count($data) - 1]['contents'] === 'sowl');

        // special eligibility requirement
        $this->assertTrue($data[1]['name'] === "data[attributes][requirements][{$requirementSpElbExternalId}]");
        $this->assertTrue($data[1]['contents'] === true);

        $application = $this->generateApplication($scholarship, $account);
        $res = $this->sender->sendApplication($scholarship, $data, $application);
        $this->assertTrue($application->getExternalApplicationId() === $externalApplicationId);
    }

    public function testSendApplication_WrongType()
    {
        $this->expectException(\InvalidArgumentException::class, 'Can send only sunrise applications!');
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_NONE);
        $account = $this->generateAccount();
        $application = $this->generateApplication($scholarship, $account);
        $this->sender->sendApplication($scholarship, [], $application);
    }

    public function testSendApplication_WithExpiredToken()
    {
        $scholarship = $this->generateScholarship(ScholarshipStatus::PUBLISHED, 1234567);

        $this->setMockHttpClient(
            $this->sender,
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'expires_in' => 12960000,
                'access_token' => md5(microtime())

            ])),
            new Response(401, ['Content-Type' => 'application/json'], "Unauthorized"),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'expires_in' => 12960000,
                'access_token' => md5(microtime())

            ])),
            new Response(200, ['Content-Type' => 'application/json'], "Ok")
        );

        $account = $this->generateAccount();
        $data = $this->sender->prepareSubmitData($scholarship, $account, ['state_id' => 3]);
        $application = $this->generateApplication($scholarship, $account);
        $res = $this->sender->sendApplication($scholarship, $data, $application);
    }

}
