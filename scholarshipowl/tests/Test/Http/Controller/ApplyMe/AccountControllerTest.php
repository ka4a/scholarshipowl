<?php
# CrEaTeD bY FaI8T IlYa
# 2016

namespace Test\Http\Controller\ApplyMe;

use App\Entity\Account;
use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Domain;
use App\Entity\Ethnicity;
use App\Entity\MilitaryAffiliation;
use App\Entity\SchoolLevel;
use App\Entity\State;
use App\Testing\TestCase;

class AccountControllerTest extends TestCase
{
	protected $V = 'v2';

	protected $account = null;

	protected $userUpdateData = [
		'firstname'            => 'firstnameupdate',
		'lastname'             => 'lastnameupdate',
		'gender'               => 'male',
		'ethnicity'            => 3,
        'fullName'             => 'firstnameupdate lastnameupdate',
		'phone'                => '23456789',
		'citizenship'          => 2,
		'country'              => 1,
		'dateOfBirth'          => '10/10/1990',
		'schoolLevel'          => 3,
		'enrollmentMonth'      => 3,
		'enrollmentYear'       => 2005,
		'gpa'                  => '2.0',
		'degree'               => 1,
		'degreeType'           => 2,
		'careerGoal'           => 2,
		'graduationYear'       => 1990,
		'graduationMonth'      => 05,
		'highschoolGraduationYear' => 1985,
		'highschoolGraduationMonth' => 06,
		'studyOnline'          => 'yes',
		'highschool'           => 'some High School',
		'enrolled'             => true,
		'university'           => 'Some University Here',
		'university1'          => 'Some University Here1',
		'university2'          => 'Some University Here2',
		'university3'          => 'Some University Here3',
		'university4'          => 'Some University Here4',
		'address'              => 'some address',
		'militaryAffiliation'  => 1,
		'zip'                  => 12345,
		'city'                 => 'New-York',
		'state'                => 2,
		'password'             => '1234567',
		'recurringApplication' => 1,
		'pro'                  => 1
	];

	protected $data = [
		'email'     => 'test@test.com',
		'firstname' => 'firstname',
		'lastname'  => 'lastname',
		'password'  => '123456'
	];

	/**
	 * @return Account
	 */
	protected function getAccount() : Account
	{
		return $this->account ?? $this->account = $this->generateAccount(
				$email = 'test@test.com',
				$firstName = 'testFirstName',
				$lastName = 'testLastName',
				$password = 'testPassword',
				$domain = Domain::APPLYME
			);
	}

	public function testGetAccount()
	{
		$account = $this->getAccount();
		$this->testAccountUpdate();

		$this->actingAs($account);
		$this->be($account);

		$resp = $this->get(route('apply-me-api::' . $this->V . '.account.show', $account->getAccountId()));

		$this->seeJsonSubset($resp, ["status" => 200,
                              "data"   => [
                                  "accountId"        => $account->getAccountId(),
                                  "email"            => 'test@test.com',
                                  "isMember"         => false,
                                  "membership"       => "Free",
                                  "freeTrial"        => false,
                                  "freeTrialEndDate" => null,
                                  "unreadInbox"      => 1,
                                  "eligibleScholarships" => 0,
                                  "username"         => 'test',
                                  "socialAccount"    => null,
                                  "transactions"     => null,
                                  "profile"          => [
                                      "completeness"        => 100,
                                      "firstName"           => $this->userUpdateData['firstname'],
                                      "lastName"            => $this->userUpdateData['lastname'],
                                      "phone"               => strval($this->userUpdateData['phone']),
                                      "dateOfBirth"         => $this->userUpdateData['dateOfBirth'],
                                      "gender"              => $this->userUpdateData['gender'],
                                      "fullName"            => $this->userUpdateData['fullName'],
                                      "citizenship"         => [
                                          "id"   => $this->userUpdateData['citizenship'],
                                          "name" => Citizenship::find($this->userUpdateData['citizenship'])->getName()
                                      ],
                                      "ethnicity"           => [
                                          "id"   => $this->userUpdateData['ethnicity'],
                                          "name" => Ethnicity::find($this->userUpdateData['ethnicity'])->getName()
                                      ],
                                      "country"             => [
                                          "id"   => $this->userUpdateData['country'],
                                          "name" => Country::find($this->userUpdateData['country'])->getName()
                                      ],
                                      "state"               => [
                                          "id"   => $this->userUpdateData['state'],
                                          "name" => State::find($this->userUpdateData['state'])->getName()
                                      ],
                                      "city"                => $this->userUpdateData['city'],
                                      "address"             => $this->userUpdateData['address'],
                                      "zip"                 => strval($this->userUpdateData['zip']),
                                      "schoolLevel"         => [
                                          "id"   => $this->userUpdateData['schoolLevel'],
                                          "name" => SchoolLevel::find($this->userUpdateData['schoolLevel'])->getName()
                                      ],
                                      "degree"              => [
                                          "id"   => $this->userUpdateData['degree'],
                                          "name" => Degree::find($this->userUpdateData['degree'])->getName()
                                      ],
                                      "degreeType"          => [
                                          "id"   => $this->userUpdateData['degreeType'],
                                          "name" => DegreeType::find($this->userUpdateData['degreeType'])->getName()
                                      ],
                                      "enrollmentYear"      => $this->userUpdateData['enrollmentYear'],
                                      "enrollmentMonth"     => $this->userUpdateData['enrollmentMonth'],
                                      "gpa"                 => $this->userUpdateData['gpa'],
                                      "careerGoal"          => [
                                          "id"   => $this->userUpdateData['careerGoal'],
                                          "name" => CareerGoal::find($this->userUpdateData['careerGoal'])->getName()
                                      ],
                                      "avatar"              => null,
                                      "isSubscribed"        => null,
                                      "graduationYear"      => $this->userUpdateData['graduationYear'],
                                      "graduationMonth"     => $this->userUpdateData['graduationMonth'],
                                      "studyOnline"         => $this->userUpdateData['studyOnline'],
                                      "highschool"          => $this->userUpdateData['highschool'],
                                      "enrolled"            => $this->userUpdateData['enrolled'],
                                      "university"          => $this->userUpdateData['university'],
                                      "university1"         => $this->userUpdateData['university1'],
                                      "university2"         => $this->userUpdateData['university2'],
                                      "university3"         => $this->userUpdateData['university3'],
                                      "university4"         => $this->userUpdateData['university4'],
                                      "pro"                 => $this->userUpdateData['pro'] ? 'true' : 'false',
                                      "profileType"         => null,
                                      "militaryAffiliation" => [
                                          "id"   => $this->userUpdateData['militaryAffiliation'],
                                          "name" => MilitaryAffiliation::find($this->userUpdateData['militaryAffiliation'])->getName()
                                      ],
                                      "recurring"           => $this->userUpdateData['recurringApplication']
                                  ]]]);
	}

	/**
	 * Register first step
	 */
	public function testAccountRegister()
	{
		static::$truncate[] = 'account';
		static::$truncate[] = 'profile';
		$this->account = null;
        $fset = $this->generateFeatureSet();
		$resp = $this->post(route('apply-me-api::' . $this->V . '.account.store'), $this->data);
		$this->seeJsonSubset($resp, ["status" => 200,
							  "data"   => [
							      "accountId"        => 1,
								  "email"            => $this->data['email'],
								  "isMember"         => false,
								  "membership"       => "Free",
								  "freeTrial"        => false,
								  "freeTrialEndDate" => null,
								  "eligibleScholarships" => 0,
								  "unreadInbox"      => 1,
                                  "username"         => 'test',
                                  "transactions"     => null,
                                  "socialAccount"    => null,
								  "profile"          => [
								      "completeness"        => 11,
									  "firstName"           => $this->data['firstname'],
									  "lastName"            => $this->data['lastname'],
									  "fullName"            => $this->data['firstname']." ".$this->data['lastname'],
									  "phone"               => null,
									  "dateOfBirth"         => null,
									  "gender"              => null,
									  "citizenship"         => null,
									  "ethnicity"           => null,
									  "isSubscribed"        => null,
									  "avatar"              => null,
									  "country"             => [
										  "id"   => 1,
										  "name" => Country::find(1)->getName()
									  ],
									  "state"               => null,
									  "city"                => null,
									  "address"             => null,
									  "zip"                 => null,
									  "schoolLevel"         => null,
									  "degree"              => null,
									  "degreeType"          => null,
									  "enrollmentYear"      => null,
									  "enrollmentMonth"     => null,
									  "gpa"                 => null,
									  "careerGoal"          => null,
									  "graduationYear"      => null,
									  "graduationMonth"     => null,
									  "studyOnline"         => null,
									  "highschool"          => null,
									  "enrolled"            => null,
									  "university"          => null,
									  "university1"         => null,
									  "university2"         => null,
									  "university3"         => null,
									  "university4"         => null,
									  "militaryAffiliation" => null,
									  "profileType"         => null,
									  "pro"                 => 'false',
									  "recurring"           => 0
								  ]]]);
	}

	public function testCheckRightPassword()
	{
		$this->testAccountRegister();
		$resp = $this->post(route('apply-me-api::' . $this->V . '.auth'), [
			'email'    => $this->data['email'],
			'password' => $this->data['password']
		]);

		$this->assertTrue($resp->status() === 200);
	}

	public function testAccountUpdate()
	{
		$this->actingAs($account = $this->getAccount());
		$this->be($account);
		$resp = $this->put(route('apply-me-api::' . $this->V . '.account.update', $account->getAccountId()), $this->userUpdateData);

		/** @var Account $account */
		$account = $this->em->getRepository(Account::class)->find(1);

		$this->assertTrue(\Hash::check($this->userUpdateData['password'], $account->getPassword()));
		$this->seeJsonSubset($resp, ["status" => 200,
                              "data"   => [
                                  "accountId"        => $account->getAccountId(),
                                  "email"            => 'test@test.com',
                                  "isMember"         => false,
                                  "membership"       => "Free",
                                  "freeTrial"        => false,
                                  "freeTrialEndDate" => null,
                                  "unreadInbox"      => 1,
                                  "eligibleScholarships" => 0,
                                  "username"         => 'test',
                                  "socialAccount"    => null,
                                  "transactions"     => null,
                                  "profile"          => [
                                      "completeness"        => 100,
                                      "firstName"           => $this->userUpdateData['firstname'],
                                      "lastName"            => $this->userUpdateData['lastname'],
                                      "dateOfBirth"         => $this->userUpdateData['dateOfBirth'],
                                      "gender"              => $this->userUpdateData['gender'],
                                      "fullName"            => $this->userUpdateData['fullName'],
                                      "citizenship"         => [
                                          "id"   => $this->userUpdateData['citizenship'],
                                          "name" => Citizenship::find($this->userUpdateData['citizenship'])->getName()
                                      ],
                                      "ethnicity"           => [
                                          "id"   => $this->userUpdateData['ethnicity'],
                                          "name" => Ethnicity::find($this->userUpdateData['ethnicity'])->getName()
                                      ],
                                      "country"             => [
                                          "id"   => $this->userUpdateData['country'],
                                          "name" => Country::find($this->userUpdateData['country'])->getName()
                                      ],
                                      "state"               => [
                                          "id"   => $this->userUpdateData['state'],
                                          "name" => State::find($this->userUpdateData['state'])->getName()
                                      ],
                                      "city"                => $this->userUpdateData['city'],
                                      "address"             => $this->userUpdateData['address'],
                                      "zip"                 => strval($this->userUpdateData['zip']),
                                      "schoolLevel"         => [
                                          "id"   => $this->userUpdateData['schoolLevel'],
                                          "name" => SchoolLevel::find($this->userUpdateData['schoolLevel'])->getName()
                                      ],
                                      "degree"              => [
                                          "id"   => $this->userUpdateData['degree'],
                                          "name" => Degree::find($this->userUpdateData['degree'])->getName()
                                      ],
                                      "degreeType"          => [
                                          "id"   => $this->userUpdateData['degreeType'],
                                          "name" => DegreeType::find($this->userUpdateData['degreeType'])->getName()
                                      ],
                                      "enrollmentYear"      => $this->userUpdateData['enrollmentYear'],
                                      "enrollmentMonth"     => $this->userUpdateData['enrollmentMonth'],
                                      "gpa"                 => $this->userUpdateData['gpa'],
                                      "careerGoal"          => [
                                          "id"   => $this->userUpdateData['careerGoal'],
                                          "name" => CareerGoal::find($this->userUpdateData['careerGoal'])->getName()
                                      ],
                                      "avatar"              => null,
                                      "isSubscribed"        => null,
                                      "graduationYear"      => $this->userUpdateData['graduationYear'],
                                      "graduationMonth"     => $this->userUpdateData['graduationMonth'],
                                      "studyOnline"         => $this->userUpdateData['studyOnline'],
                                      "highschool"          => $this->userUpdateData['highschool'],
                                      "enrolled"            => $this->userUpdateData['enrolled'],
                                      "university"          => $this->userUpdateData['university'],
                                      "university1"         => $this->userUpdateData['university1'],
                                      "university2"         => $this->userUpdateData['university2'],
                                      "university3"         => $this->userUpdateData['university3'],
                                      "university4"         => $this->userUpdateData['university4'],
                                      "pro"                 => $this->userUpdateData['pro'] ? 'true' : 'false',
                                      "profileType"         => null,
                                      "militaryAffiliation" => [
                                          "id"   => $this->userUpdateData['militaryAffiliation'],
                                          "name" => MilitaryAffiliation::find($this->userUpdateData['militaryAffiliation'])->getName()
                                      ],
                                      "recurring"           => $this->userUpdateData['recurringApplication']
                                  ]]]);

	}

	/**
	 * Show predefined register data
	 */
	public function testPredefinedRegisterData()
	{
		$this->actingAs($account = $this->getAccount());
		$this->be($account);
		$resp = $this->get(route('apply-me-api::v1.account.register.data'));
		$this->seeJsonContains($resp, [
			"status" => 200,
			"data"   => "success",
			"meta"   => [
				"genders"      => [
					"female" => "Female",
					"male"   => "Male",
					"other"  => "Other"
				],
				"citizenships" => [
					"1" => "U.S. Citizen",
					"2" => "U.S. Legal Permanent Resident",
					"3" => "U.S. Legal Temporary Resident (International Students)"
				],
				"ethnicities"  => [
					"1" => "Caucasian",
					"2" => "African American",
					"3" => "Hispanic / Latino",
					"4" => "Asian / Pacific Islander",
					"5" => "American Indian / Native Alaskan",
					"6" => "Other"
				],
				"gpas"         => [
					"N/A" => "N/A",
					"2.0" => "2.0",
					"2.1" => "2.1",
					"2.2" => "2.2",
					"2.3" => "2.3",
					"2.4" => "2.4",
					"2.5" => "2.5",
					"2.6" => "2.6",
					"2.7" => "2.7",
					"2.8" => "2.8",
					"2.9" => "2.9",
					"3.0" => "3.0",
					"3.1" => "3.1",
					"3.2" => "3.2",
					"3.3" => "3.3",
					"3.4" => "3.4",
					"3.5" => "3.5",
					"3.6" => "3.6",
					"3.7" => "3.7",
					"3.8" => "3.8",
					"3.9" => "3.9",
					"4.0" => "4.0"
				],
				"degrees"      => [
					"1"  => "Agriculture and Related Sciences",
					"2"  => "Architecture and Related Services",
					"3"  => "Area, Ethnic, Cultural and Gender Studies",
					"4"  => "Biological and Biomedical Sciences",
					"5"  => "Business, Management and Marketing",
					"6"  => "Communication and Journalism",
					"7"  => "Computer and Information Sciences",
					"8"  => "Construction Trades",
					"9"  => "Education",
					"10" => "Engineering",
					"11" => "English Language and Literature",
					"12" => "Family and Consumer Sciences",
					"13" => "Foreign Languages, Literature and Linguistics",
					"14" => "Health Professions and Clinical Sciences",
					"15" => "History",
					"16" => "Legal Professions and Law Studies",
					"17" => "Liberal Arts / General Studies",
					"18" => "Library Science",
					"19" => "Mathematics and Statistics",
					"20" => "Mechanic and Repair Tech / Technicians",
					"21" => "Military Technologies",
					"22" => "Multi / Interdisciplinary Studies",
					"23" => "Natural Resources and Conservation",
					"24" => "Parks, Recreation, and Fitness Studies",
					"25" => "Personal and Culinary Services",
					"26" => "Philosophy and Religious Studies",
					"27" => "Physical Sciences",
					"28" => "Precision Production",
					"29" => "Psychology",
					"30" => "Public Administration and Social Service",
					"31" => "Security and Protective Services",
					"32" => "Social Sciences",
					"33" => "Technology Education / Industrial Arts",
					"34" => "Theology and Religious Vocations",
					"35" => "Transportation and Materials Moving",
					"36" => "Visual and Performing Arts",
					"37" => "Not Listed / Other"
				],
				"degreeTypes"  => [
					"2" => "Certificate",
					"3" => "Associate's Degree",
					"4" => "Bachelor's Degree",
					"5" => "Graduate Certificate",
					"6" => "Master's Degree",
					"7" => "Doctoral (Ph.D.)",
					"1" => "Undecided",
				],
				"careerGoals"  => [
					"1"  => "Art, Design or Fashion",
					"2"  => "Beauty or Cosmetology",
					"3"  => "Business / Marketing / Management",
					"4"  => "Computers / IT / Technology",
					"5"  => "Culinary Arts",
					"6"  => "Health Care / Nursing",
					"7"  => "Law / Criminal Justice",
					"8"  => "Teaching / Education",
					"9"  => "Vocational / Technical",
					"10" => "Other"
				],
				"schoolLevels" => [
					"1"  => "High school freshman",
					"2"  => "High school sophomore",
					"3"  => "High school junior",
					"4"  => "High school senior",
					"5"  => "College 1st year",
					"6"  => "College 2nd year",
					"7"  => "College 3rd year",
					"8"  => "College 4th year",
					"9"  => "Graduate student",
					"10" => "Adult/Non-traditional Student"
				],
				"studyOnline"  => [
					"yes"   => "Yes",
					"no"    => "No",
					"maybe" => "Maybe"
				],
				"states"       => [
					"1"  => "Alabama",
					"2"  => "Alaska",
					"3"  => "Arizona",
					"4"  => "Arkansas",
					"5"  => "California",
					"6"  => "Colorado",
					"7"  => "Connecticut",
					"8"  => "Delaware",
					"9"  => "District of Columbia",
					"10" => "Florida",
					"11" => "Georgia",
					"12" => "Hawaii",
					"13" => "Idaho",
					"14" => "Illinois",
					"15" => "Indiana",
					"16" => "Iowa",
					"17" => "Kansas",
					"18" => "Kentucky",
					"19" => "Louisiana",
					"20" => "Maine",
					"21" => "Maryland",
					"22" => "Massachusetts",
					"23" => "Michigan",
					"24" => "Minnesota",
					"25" => "Mississippi",
					"26" => "Missouri",
					"27" => "Montana",
					"28" => "Nebraska",
					"29" => "Nevada",
					"30" => "New Hampshire",
					"31" => "New Jersey",
					"32" => "New Mexico",
					"33" => "New York",
					"34" => "North Carolina",
					"35" => "North Dakota",
					"36" => "Ohio",
					"37" => "Oklahoma",
					"38" => "Oregon",
					"39" => "Pennsylvania",
					"40" => "Puerto Rico",
					"41" => "Rhode Island",
					"42" => "South Carolina",
					"43" => "South Dakota",
					"44" => "Tennessee",
					"45" => "Texas",
					"46" => "Utah",
					"47" => "Vermont",
					"48" => "Virginia",
					"49" => "Washington",
					"50" => "West Virginia",
					"51" => "Wisconsin",
					"52" => "Wyoming"
				]
			]
		]);
	}

	/**
	 * Update password
	 */
	public function testPasswordReset()
	{
		$account = $this->getAccount();

		$resp = $this->post(route('apply-me-api::v1.account.password.reset'), [
			'email' => $account->getEmail()
		]);

		$this->assertFalse(\Hash::check('testPassword', $account->getPassword()));
		$this->assertTrue($resp->status() === 200);
	}

}
