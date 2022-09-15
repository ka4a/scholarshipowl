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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountControllerTest extends TestCase
{
    public function testGet()
    {
        $account = $this->generateAccount();
        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account.show', $account->getAccountId()));

        $this->seeJsonSubset($resp, ['status' => 200]);
    }

    public function testCreate()
	{
		static::$truncate[] = 'account';
		static::$truncate[] = 'profile';
		$this->account = null;

        $data = [
            'email'     => 'test@test.com',
            'firstname' => 'firstname',
            'lastname'  => 'lastname',
            'password'  => '123456'
        ];
        $fset = $this->generateFeatureSet();
        $resp = $this->post(route('rest-mobile::v1.account.create'), $data);

		$this->seeJsonSubset($resp, [
            "status" => 200,
            "data"   => [
                "accountId" => 1,
                "email" => $data['email'],
                "isMember" => false,
                "isFreemium" => false,
                "membership" => "Free",
                "freeTrial" => false,
                "freeTrialEndDate" => null,
                "eligibleScholarships" => 0,
                "username" => 'test',
                "socialAccount" => null,
                "profile"=> [
                    "firstName" => $data['firstname'],
                    "lastName" => $data['lastname'],
                    "fullName" => ucfirst($data['firstname']).' '.ucfirst($data['lastname']),
                    "country" => [
                        "id" => 1,
                        "name" => 'USA'
                    ],
                    "recurringApplication" => 0
                ]
            ]
        ]);
	}

    public function testUpdate()
    {
        $account = $this->generateAccount();
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
        $resp = $this->callWithJwt($account, 'PUT', route('rest-mobile::v1.account.update', $account->getAccountId()), [
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

        $this->seeJsonSubset($resp, ['status' => 200]);
    }

    public function testFormOptions()
    {
        $account = $this->generateAccount();
        $resp = $this->callWithJwt($account, 'GET', route('rest-mobile::v1.account.form-options', $account->getAccountId()));

        $this->seeJsonSubset($resp, ['status' => 200]);
    }

    public function testDelete()
    {
        $account = $this->generateAccount();
        $resp = $this->callWithJwt($account, 'DELETE', route('rest-mobile::v1.account.delete', $account->getAccountId()));
        $this->seeJsonSubset($resp, ['status' => 200]);

        $this->assertDatabaseMissing('account', [
            'account_id' => $account->getAccountId(),
            'deleted_at' => null
        ]);
    }

    public function testPasswordChange()
    {
        $hashedPassword = \Hash::make('111111');
        $account = $this->generateAccount('t@t.com', 'John', 'Doe', $hashedPassword);

        $resp = $this->callWithJwt($account, 'PUT', route('rest-mobile::v1.account.password.change', [
            'passwordCurrent' => '111111',
            'passwordNew' => '22222',
            'passwordNewRe' => '222222',
        ]));
        $this->seeJsonSubset($resp, ['status' => 400]);
        $this->seeJsonStructure($resp, ['status', 'error' => ['passwordNew']]);

        $resp = $this->callWithJwt($account, 'PUT', route('rest-mobile::v1.account.password.change', [
            'passwordCurrent' => 'wrong-pwd',
            'passwordNew' => '222222',
            'passwordNewRe' => '222222',
        ]));
        $this->seeJsonSubset($resp, ['status' => 403]);
        $this->assertDatabaseHas('account', [
            'password' => $hashedPassword
        ]);

        $resp = $this->callWithJwt($account, 'PUT', route('rest-mobile::v1.account.password.change', [
            'passwordCurrent' => '111111',
            'passwordNew' => '222222',
            'passwordNewRe' => '222222',
        ]));
        $this->seeJsonSubset($resp, ['status' => 200]);
        $this->assertDatabaseMissing('account', [
            'password' => $hashedPassword
        ]);
    }

    public function testPasswordReset()
    {
    	static::$truncate[] = 'forgot_password';

        $hashedPassword = \Hash::make('111111');
        $account = $this->generateAccount('t@t.com', 'John', 'Doe', $hashedPassword);
        $forgotPasswordEntity = $this->generatePasswordResetToken($account);

        $resp = $this->put(route('rest-mobile::v1.account.password.reset', [
            'passwordNew' => '222222',
            'token' => $forgotPasswordEntity->getToken(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 200]);

        // now the token must be expired (already used)
        $resp = $this->put(route('rest-mobile::v1.account.password.reset', [
            'passwordNew' => '222222',
            'token' => $forgotPasswordEntity->getToken(),
        ]));
        $this->seeJsonSubset($resp, ['status' => 400]);
        $this->seeJsonStructure($resp, ['error' => ['token']]);

    }
    public function testInstalledAppFlag()
    {
        $account = $this->generateAccount();
        $resp = $this->callWithJwt($account, 'PUT', route('rest-mobile::v1.account.app-installed'));
        $this->seeJsonSubset($resp, ['status' => 200]);

        $this->assertDatabaseHas('account', [
            'account_id' => $account->getAccountId(),
            'app_installed' => 1
        ]);

        $resp = $this->callWithJwt($account, 'PUT', route('rest-mobile::v1.account.app-uninstalled'));
        $this->seeJsonSubset($resp, ['status' => 200]);

        $this->assertDatabaseHas('account', [
            'account_id' => $account->getAccountId(),
            'app_installed' => 0
        ]);
    }


    public function testDeviceTokenSet()
    {
        $deviceToken = '111111';
        $account = $this->generateAccount();
        $resp = $this->callWithJwt($account, 'PUT', route('rest-mobile::v1.account.update', $account->getAccountId()), [
            'deviceToken' => $deviceToken,
        ]);
        $this->seeJsonSubset($resp, ['status' => 200]);
        $this->assertDatabaseHas('account', [
            'device_token' => $deviceToken
        ]);
    }
}
