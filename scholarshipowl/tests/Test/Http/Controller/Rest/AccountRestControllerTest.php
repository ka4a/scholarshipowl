<?php namespace Test\Http\Controller\Rest;

use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Ethnicity;
use App\Entity\Profile;
use App\Entity\SchoolLevel;
use App\Entity\State;
use App\Services\OptionsManager;
use App\Testing\TestCase;
use Facebook\FacebookResponse;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class AccountRestControllerTest extends TestCase
{

    public function testAccountOptionsActions()
    {
        $this->actingAs($account = $this->generateAccount());

        $resp = $this->call('GET', route('rest::v1.options', $account->getAccountId()));

        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccessSubset($resp, [
            'countries' => [
                Country::USA => 'USA',
                Country::CANADA => 'Canada',
            ],
            'states' => [
                State::STATE_US_ALABAMA => 'Alabama',
            ],
            'genders' => Profile::genders(),
            'citizenships'  => [
                Citizenship::CITIZENSHIP_USA => 'U.S. Citizen',
            ],
            'ethnicities'   => [
                Ethnicity::ETHNICITY_CAUCASIAN => 'Caucasian',
                Ethnicity::ETHNICITY_OTHER => 'Other',
            ],
            'gpas'          => Profile::gpas(),
            'degrees'       => [
                Degree::DEGREE_AGRICULTURE_AND_RELATED_SCIENCES => 'Agriculture and Related Sciences',
            ],
            'degreeTypes'   => [
                DegreeType::DEGREE_UNDECIDED => 'Undecided',
            ],
            'careerGoals'   => [
                CareerGoal::OTHER => 'Other',
            ],
            'schoolLevels'  => [
                SchoolLevel::LEVEL_HIGH_SCHOOL_FRESHMAN => 'High school freshman',
            ],
            'studyOnline'   => Profile::studyOnlineOptions(),
        ]);

        $resp = $this->call('GET', route('rest::v1.account.options', $account->getAccountId()), [
            'only' => [
                OptionsManager::OPTIONS_GENDERS,
                OptionsManager::OPTIONS_STUDY_ONLINE,
                OptionsManager::OPTIONS_GPAS
            ],
        ]);

        $this->seeJsonSuccess($resp, [
            'genders' => Profile::genders(),
            'gpas' => Profile::gpas(),
            'studyOnline' => Profile::studyOnlineOptions(),
        ]);
    }

    public function testAccountRegisterNonUSAAction()
    {
        static::$truncate[] = 'profile';
        static::$truncate[] = 'ab_test_account';
        static::$truncate[] = 'account';
        $this->generateFeatureSet();

        $data = [
            'firstName' => 'test first name',
            'lastName' => 'test last name',
            'email' => 'test@test.com',
            'phone' => '+1788308083',
            'countryCode' => 'CA',
            'studyCountry' => [Country::CANADA, Country::USA],
        ];

        $resp = $this->post(route('rest::v1.account.register'), $data);
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccessSubset($resp, [
            'accountId' => 1,
            'email' => $data['email'],
            'profile' => [
                'firstName'     => $data['firstName'],
                'lastName'      => $data['lastName'],
                'phone'         => $data['phone'],
                'country'       => ['id' => Country::CANADA],
                'citizenship'   => ['id' => Citizenship::CITIZENSHIP_CANADA],
                'studyCountry1' => ['id' => Country::CANADA],
                'studyCountry2' => ['id' => Country::USA],
                'studyCountry3' => null,
                'studyCountry4' => null,
                'studyCountry5' => null,
            ]
        ]);

        $resp = $this->post(route('rest::v1.account.register'), $data);
        $this->assertTrue($resp->status() === 403);
    }

    public function testAccountRegisterAction()
    {
        static::$truncate[] = 'profile';
        static::$truncate[] = 'ab_test_account';
        static::$truncate[] = 'account';
        $this->generateFeatureSet();

        $resp = $this->post(route('rest::v1.account.register'));
        $this->seeJsonError($resp, [
            'firstName' => ['Please enter first name!'],
            'lastName' => ['Please enter last name!'],
            'email' => ['Please enter email!'],
            'phone' => ['Please enter phone!'],
        ]);

        $data = [
            'firstName' => 'test first name',
            'lastName' => 'test last name',
            'email' => 'test email',
            'phone' => 'test phone',
        ];

        $resp = $this->post(route('rest::v1.account.register'), $data);
        $this->seeJsonError($resp, [
            'phone' => ['Invalid phone number!'],
            'email' => ['Email address is invalid!'],
        ]);

        $data = [
            'email' => 'test@test.com',
            'phone' => '+17883080830',
        ] + $data;

        $resp = $this->post(route('rest::v1.account.register'), $data);
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonSuccessSubset($resp, [
            'accountId' => 1,
            'email' => $data['email'],
            'profile' => [
                'firstName'     => $data['firstName'],
                'lastName'      => $data['lastName'],
                'phone'         => '(788) 308 - 0830',
                'country'       => ['id' => Country::USA],
                'citizenship'   => null,
                'studyCountry1' => ['id' => Country::USA],
                'studyCountry2' => null,
                'studyCountry3' => null,
                'studyCountry4' => null,
                'studyCountry5' => null,
            ]
        ]);

        $resp = $this->post(route('rest::v1.account.register'), $data);
        $this->assertTrue($resp->status() === 403);
    }

    public function testUpdateUserInfo()
    {
        $this->actingAs($account = $this->generateAccount());

        $data = [
            'firstName'             => 'TestName3',
            'lastName'              => 'LastName3',
            'phone'                 => '111111',
            'dateOfBirth'           => '05.04.1985',
            'gender'                => 'male',
            'citizenship'         => 1,
            'ethnicity'           => 3,
            'country'             => 1,
            'state'               => 3,
            'city'                  => 'New-York',
            'address'               => 'Mail Avenu',
            'zip'                   => 12345,
            'schoolLevel'         => 2,
            'degree'              => 2,
            'degreeType'          => 3,
            'enrollmentYear'        => '1986',
            'enrollmentMonth'       => '6',
            'gpa'                   => '2.5',
            'careerGoal'          => 2,
            'graduationYear'        => '1999',
            'graduationMonth'       => '2',
            'studyOnline'           => 'no',
            'highschool'            => 'HighSchool',
            'enrolled'              => 1,
            'university'            => 'university',
            'university1'           => 'university1',
            'university2'           => 'university2',
            'university3'           => 'university3',
            'university4'           => 'university4',
            'militaryAffiliation' => 2
        ];

        // !Important: fields should be like this to be caught by Hydratable
        $resp = $this->call('PUT', route('rest::v1.account.update', $account->getAccountId()), [
            'firstname'           => $data['firstName'],
            'lastname'            => $data['lastName'],
            'phone'               => $data['phone'],
            'dateOfBirth'         => $data['dateOfBirth'],
            'gender'              => $data['gender'],
            'citizenship'         => $data['citizenship'],
            'ethnicity'           => $data['ethnicity'],
            'country'             => $data['country'],
            'state'               => $data['state'],
            'city'                => $data['city'],
            'address'             => $data['address'],
            'zip'                 => $data['zip'],
            'schoolLevel'         => $data['schoolLevel'],
            'degree'              => $data['degree'],
            'degreeType'          => $data['degreeType'],
            'enrollmentYear'      => $data['enrollmentYear'],
            'enrollmentMonth'     => $data['enrollmentMonth'],
            'gpa'                 => $data['gpa'],
            'careerGoal'          => $data['careerGoal'],
            'graduationYear'      => $data['graduationYear'],
            'graduationMonth'     => $data['graduationMonth'],
            'studyOnline'         => $data['studyOnline'],
            'highschool'          => $data['highschool'],
            'enrolled'            => $data['enrolled'],
            'university'          => $data['university'],
            'university1'         => $data['university1'],
            'university2'         => $data['university2'],
            'university3'         => $data['university3'],
            'university4'         => $data['university4'],
            'militaryAffiliation' => $data['militaryAffiliation']
        ]);

        $this->assertTrue($resp->status() === 200);
    }

    public function testGetAccount()
    {
        $this->actingAs($account = $this->generateAccount());

        $resp = $this->get(route('rest::v1.account.show', $account->getAccountId()));
        $this->assertTrue($resp->status() === 200);
    }

    public function testLinkFacebookAccount()
    {
        $mock = \Mockery::mock(LaravelFacebookSdk::class);
        $mock->shouldReceive('getLoginUrl')->andReturn(route('rest::v1.callbackFacebook'));
        $this->app->instance(LaravelFacebookSdk::class, $mock);

        $this->actingAs($account = $this->generateAccount());

        $resp = $this->get(route('rest::v1.account.linkFacebook'));

        $this->assertTrue(strpos($resp->content(), 'Redirecting to') !== false);
        $this->assertTrue(strpos($resp->content(), route('rest::v1.callbackFacebook')) !== false);
    }

    public function testCallbackFacebook()
    {
        static::$truncate[] = 'social_account';

        $tokenMock = \Mockery::mock(\Facebook\Authentication\AccessToken::class);
        $tokenMock->shouldReceive('isLongLived')->andReturn(true);

        $graphUserMock = \Mockery::mock(\Facebook\GraphNodes\GraphUser::class);
        $fbUserId = 12345;
        $graphUserMock->shouldReceive('getId')->andReturn($fbUserId);
        $graphUserMock->shouldReceive('getGraphUser')->andReturn($graphUserMock);

        $fbServiceMock = \Mockery::mock(LaravelFacebookSdk::class);
        $fbServiceMock->shouldReceive('getAccessTokenFromRedirect')->andReturn($tokenMock);
        $fbServiceMock->shouldReceive('setDefaultAccessToken')->andReturn(null);
        $fbServiceMock->shouldReceive('get')->andReturn($graphUserMock);

        $this->app->instance(LaravelFacebookSdk::class, $fbServiceMock);

        $this->actingAs($account = $this->generateAccount());

        $resp = $this->get(route('rest::v1.callbackFacebook'));

        $this->assertTrue(strpos($resp->content(), 'Redirecting to') !== false);
        $this->assertTrue(strpos($resp->content(), route('my-account')) !== false);

        $this->assertDatabaseHas('social_account', [
            'provider_user_id' => $fbUserId,
            'account_id' => $account->getAccountId()
        ]);
    }

    public function testUnlinkFacebookAccount()
    {
        static::$truncate[] = 'social_account';

        $fbResponseMock = \Mockery::mock(FacebookResponse::class);
        $fbResponseMock->shouldReceive('getDecodedBody')->andReturn(['success' => true]);

        $graphUserMock = \Mockery::mock(\Facebook\GraphNodes\GraphUser::class);
        $fbUserId = 12345;
        $graphUserMock->shouldReceive('getId')->andReturn($fbUserId);
        $graphUserMock->shouldReceive('getGraphUser')->andReturn($graphUserMock);

        $fbServiceMock = \Mockery::mock(LaravelFacebookSdk::class);
        $fbServiceMock->shouldReceive('setDefaultAccessToken')->andReturn(null);
        $fbServiceMock->shouldReceive('delete')->andReturn($fbResponseMock);

        $this->app->instance(LaravelFacebookSdk::class, $fbServiceMock);

        $account = $this->generateAccount();
        $socialAccount = $this->generateSocialAccount($account, $fbUserId);
        $this->actingAs($account);

        $this->assertDatabaseHas('social_account', [
            'provider_user_id' => $fbUserId,
            'account_id' => $account->getAccountId()
        ]);

        $resp = $this->delete(route('rest::v1.account.unlinkFacebook'));

        $this->assertTrue($resp->status() === 200);

        $this->assertDatabaseMissing('social_account', [
            'provider_user_id' => $fbUserId,
            'account_id' => $account->getAccountId()
        ]);
    }
}
