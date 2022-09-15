<?php namespace Test\Services;

use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Eligibility;
use App\Entity\Ethnicity;
use App\Entity\Field;
use App\Entity\MilitaryAffiliation;
use App\Entity\Profile;
use App\Entity\SchoolLevel;
use App\Events\Account\UpdateAccountEvent;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Services\EligibilityCacheService;
use App\Services\EligibilityService;
use App\Testing\TestCase;
use Carbon\Carbon;
use App\Entity\State;

class EligibilityServiceTest extends TestCase
{
    /**
     * @var EligibilityService
     */
    protected $service;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'eligibility';
        static::$truncate[] = 'eligibility_cache';
        static::$truncate[] = 'scholarship';
        static::$truncate[] = 'application';
        $this->service = $this->app->make(EligibilityService::class);
        $this->elbCacheService = $this->app->make(EligibilityCacheService::class);
    }

    public function testEligibilityStateFreeText()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();
        $eligibility = $this->generateEligibility($scholarship, Field::STATE_FREE_TEXT, Eligibility::TYPE_REQUIRED);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStateName('test'));
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $this->em->flush($eligibility->setType(Eligibility::TYPE_VALUE)->setValue('test'));
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertTrue($this->service->isEligible($account, $scholarship));


        $this->em->flush($eligibility->setType(Eligibility::TYPE_IN)->setValue('a,b,c'));
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStateName('a'));
        $this->assertTrue($this->service->isEligible($account, $scholarship));
        $this->updateProfile($profile->setStateName('b'));
        $this->assertTrue($this->service->isEligible($account, $scholarship));
        $this->updateProfile($profile->setStateName('c'));
        $this->assertTrue($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityCountryOfStudy()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();

        $eligibility = $this->generateEligibility($scholarship, Field::COUNTRY_OF_STUDY, Eligibility::TYPE_REQUIRED);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStudyCountry1(Country::USA));
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $this->em->flush($eligibility->setType(Eligibility::TYPE_VALUE)->setValue(Country::USA));
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $this->em->flush($eligibility->setType(Eligibility::TYPE_NOT)->setValue(Country::USA));
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($eligibility->setType(Eligibility::TYPE_IN)->setValue('1,41'));
        $this->em->flush($profile->setStudyCountry1(2));
       \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($profile->setStudyCountry2(3));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStudyCountry2(41));
        $this->updateProfile($profile->setStudyCountry3(1));
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStudyCountry3(41));
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStudyCountry4(1));
        $this->assertTrue($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityMultiFields()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();

        $this->updateProfile($profile->setGpa('2.4')
            ->setSchoolLevel(5)
            ->setDateOfBirth(new \DateTime('2000-01-01'))
            ->setCitizenship(Citizenship::CITIZENSHIP_USA)
            ->setDegreeType(DegreeType::DEGREE_BACHELOR)
        );

        $age = Carbon::instance($profile->getDateOfBirth())->age;
        $this->generateEligibilities([
            [$scholarship, Field::FIRST_NAME, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::LAST_NAME, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::AGE, Eligibility::TYPE_GREATER_THAN, $age - 1],
            [$scholarship, Field::AGE, Eligibility::TYPE_LESS_THAN, $age + 1],
            [$scholarship, Field::AGE, Eligibility::TYPE_VALUE, $age],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_GREATER_THAN, 3],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_LESS_THAN, 8],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_VALUE, 5],
            [$scholarship, Field::GPA, Eligibility::TYPE_GREATER_THAN, '2.1'],
            [$scholarship, Field::GPA, Eligibility::TYPE_LESS_THAN, '3.0'],
            [$scholarship, Field::GPA, Eligibility::TYPE_VALUE, '2.4'],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_GREATER_THAN, DegreeType::DEGREE_UNDECIDED],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_LESS_THAN, DegreeType::DEGREE_DOCTOR],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_VALUE, DegreeType::DEGREE_BACHELOR],
            [$scholarship, Field::CITIZENSHIP, Eligibility::TYPE_VALUE, Citizenship::CITIZENSHIP_USA],
        ]);

        $this->assertTrue($this->service->isEligible($account, $scholarship));
        $this->generateEligibility($scholarship, Field::AGE, Eligibility::TYPE_VALUE, '1,2,3');
        $this->assertFalse($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityFirstName()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $eligibility = $this->generateEligibility($scholarship, Field::FIRST_NAME, Eligibility::TYPE_REQUIRED);
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $eligibility->setType(Eligibility::TYPE_VALUE);
        $eligibility->setValue('test2');
        $this->em->flush($eligibility);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $eligibility->setValue('testFirstName');
        $this->em->flush($eligibility);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertTrue($this->service->isEligible($account, $scholarship));

        $eligibility->setType(Eligibility::TYPE_NOT);
        $eligibility->setValue('testFirstName');
        $this->em->flush($eligibility);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $eligibility->setType(Eligibility::TYPE_IN);
        $eligibility->setValue('test,testFirstName,test2');
        $this->em->flush($eligibility);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));

        $this->assertTrue($this->service->isEligible($account, $scholarship));
        $eligibility->setType(Eligibility::TYPE_IN);
        $eligibility->setValue('test,test2');
        $this->em->flush($eligibility);
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, true));
        $this->assertFalse($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityClause_between()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->generateEligibilities([
            [$scholarship, Field::AGE, Eligibility::TYPE_BETWEEN, '18,70'],
            [$scholarship, Field::BIRTHDAY_YEAR, Eligibility::TYPE_BETWEEN, '1990,2000'],
            [$scholarship, Field::BIRTHDAY_MONTH, Eligibility::TYPE_BETWEEN, '01,05'],
            [$scholarship, Field::BIRTHDAY_DAY, Eligibility::TYPE_BETWEEN, '01,15'],
            [$scholarship, Field::CITIZENSHIP, Eligibility::TYPE_BETWEEN, '1,5'],
            [$scholarship, Field::ETHNICITY, Eligibility::TYPE_BETWEEN,'1,5'],
            [$scholarship, Field::COUNTRY, Eligibility::TYPE_BETWEEN,'1,2'],
            [$scholarship, Field::STATE, Eligibility::TYPE_BETWEEN, '1,3'],
            [$scholarship, Field::ZIP, Eligibility::TYPE_BETWEEN, '11111,11113'],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_BETWEEN, '1,3'],
            [$scholarship, Field::DEGREE, Eligibility::TYPE_BETWEEN, '1,3'],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_BETWEEN,'2,4'],
            [$scholarship, Field::ENROLLMENT_YEAR, Eligibility::TYPE_BETWEEN, '2014,2020'],
            [$scholarship, Field::ENROLLMENT_MONTH, Eligibility::TYPE_BETWEEN, '9,12'],
            [$scholarship, Field::GPA, Eligibility::TYPE_BETWEEN, '3.0,3.9'],
            [$scholarship, Field::CAREER_GOAL, Eligibility::TYPE_BETWEEN, '9,11'],
            [$scholarship, Field::HIGH_SCHOOL_GRADUATION_YEAR, Eligibility::TYPE_BETWEEN, '2010,2015'],
            [$scholarship, Field::HIGH_SCHOOL_GRADUATION_MONTH, Eligibility::TYPE_BETWEEN, '03,05'],
            [$scholarship, Field::COLLEGE_GRADUATION_YEAR, Eligibility::TYPE_BETWEEN, '2020,2030'],
            [$scholarship, Field::COLLEGE_GRADUATION_MONTH, Eligibility::TYPE_BETWEEN, '06,08'],
            [$scholarship, Field::COUNTRY_OF_STUDY, Eligibility::TYPE_BETWEEN, '1,3'],
            [$scholarship, Field::MILITARY_AFFILIATION, Eligibility::TYPE_BETWEEN, '1,3'],
        ]);


        // add profile fields one by one, and when all fields is added the account must became eligible to the scholarship
        $this->updateProfile($profile->setDateOfBirth(new \DateTime('1995-01-01')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setCitizenship(Citizenship::CITIZENSHIP_USA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setEthnicity(Ethnicity::ETHNICITY_CAUCASIAN));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setCountry(Country::USA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setState(State::STATE_US_ALABAMA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setZip(11112));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setSchoolLevel(SchoolLevel::LEVEL_HIGH_SCHOOL_FRESHMAN));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setDegree(Degree::DEGREE_AGRICULTURE_AND_RELATED_SCIENCES));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setDegreeType(DegreeType::DEGREE_ASSOCIATE));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setEnrollmentYear(2015));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setEnrollmentMonth(10));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setGpa('3.5'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setCareerGoal(CareerGoal::OTHER));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($profile->setHighschoolGraduationYear(2011));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($profile->setHighschoolGraduationMonth(04));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setGraduationYear(2025));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setGraduationMonth('07'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($profile->setStudyCountry1(Country::USA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setMilitaryAffiliation(2));
        $this->em->flush($profile->setStudyCountry1(Country::USA));

        $this->assertTrue($this->service->isEligible($account, $scholarship));
    }


    public function testEligibilityClause_required()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId());

        $this->generateEligibilities([
            [$scholarship, Field::EMAIL, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::FIRST_NAME, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::LAST_NAME, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::PHONE, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::DATE_OF_BIRTH, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::AGE, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::BIRTHDAY_YEAR, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::BIRTHDAY_MONTH, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::BIRTHDAY_DAY, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::GENDER, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::CITIZENSHIP, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::ETHNICITY, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::COUNTRY, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::STATE, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::CITY, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::ADDRESS, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::ZIP, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::DEGREE, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::ENROLLMENT_YEAR, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::ENROLLMENT_MONTH, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::GPA, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::CAREER_GOAL, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::STUDY_ONLINE, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::HIGH_SCHOOL_NAME, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::HIGH_SCHOOL_GRADUATION_YEAR, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::HIGH_SCHOOL_GRADUATION_MONTH, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::COLLEGE_GRADUATION_YEAR, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::PHONE_AREA_CODE, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::PHONE_PREFIX, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::PHONE_LOCAL, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::FULL_NAME, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::COUNTRY_OF_STUDY, Eligibility::TYPE_REQUIRED],
            [$scholarship, Field::STATE_FREE_TEXT, Eligibility::TYPE_REQUIRED],
        ]);

        // add profile fields one by one, and when all fields is added the account must became eligible to the scholarship
        $this->updateProfile($profile->setPhone('(111) 111 - 1111'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setDateOfBirth(new \DateTime('2000-01-01')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setGender('male'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setCitizenship(Citizenship::CITIZENSHIP_USA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setEthnicity(Ethnicity::ETHNICITY_CAUCASIAN));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setCountry(Country::USA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setState(State::STATE_US_ALABAMA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setCity('Some City'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setAddress('Some Address'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setZip(777777));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setSchoolLevel(SchoolLevel::LEVEL_HIGH_SCHOOL_FRESHMAN));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setDegree(Degree::DEGREE_AGRICULTURE_AND_RELATED_SCIENCES));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setDegreeType(DegreeType::DEGREE_ASSOCIATE));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setEnrollmentYear(date('Y')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setEnrollmentMonth(date('m')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setGpa('3.5'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setCareerGoal(CareerGoal::OTHER));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStudyOnline('yes'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setHighschool('Some school name'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setGraduationYear(date('Y')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setGraduationMonth(date('m')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($profile->setHighschoolGraduationYear(date('Y')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($profile->setHighschoolGraduationMonth(date('m')));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->em->flush($profile->setStudyCountry1(Country::USA));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $this->updateProfile($profile->setStateName('Some state Name'));

        $this->assertTrue($this->service->isEligible($account, $scholarship));
    }


    public function testEligibilityClause_value()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();

        $populateProfile = function() use($profile) {
            $profile
                ->setPhone('(385) 215 - 0916')
                ->setDateOfBirth(new \DateTime('2000-01-01'))
                ->setGender('male')
                ->setCitizenship(Citizenship::CITIZENSHIP_USA)
                ->setEthnicity(Ethnicity::ETHNICITY_CAUCASIAN)
                ->setCountry(Country::USA)
                ->setState(State::STATE_US_ALABAMA)
                ->setCity('Some City')
                ->setAddress('Some Address')
                ->setZip(777777)
                ->setSchoolLevel(SchoolLevel::LEVEL_HIGH_SCHOOL_FRESHMAN)
                ->setDegree(Degree::DEGREE_AGRICULTURE_AND_RELATED_SCIENCES)
                ->setDegreeType(DegreeType::DEGREE_ASSOCIATE)
                ->setEnrollmentYear('2000')
                ->setEnrollmentMonth('01')
                ->setGpa('3.5')
                ->setCareerGoal(CareerGoal::OTHER)
                ->setStudyOnline('yes')
                ->setHighschool('Some school name')
                ->setHighschoolGraduationYear('2015')
                ->setHighschoolGraduationMonth('05')
                ->setGraduationYear('2020')
                ->setGraduationMonth('01')
                ->setStudyCountry1(Country::USA)
                ->setStateName('Some state Name');

                return $profile;
        };

        $this->updateProfile($populateProfile($profile));

        $this->generateEligibilities([
            [$scholarship, Field::EMAIL, Eligibility::TYPE_VALUE, $account->getEmail()],
            [$scholarship, Field::FIRST_NAME, Eligibility::TYPE_VALUE, $profile->getFirstName()],
            [$scholarship, Field::LAST_NAME, Eligibility::TYPE_VALUE, $profile->getLastName()],
            [$scholarship, Field::PHONE, Eligibility::TYPE_VALUE, $profile->getPhone()],
            [$scholarship, Field::DATE_OF_BIRTH, Eligibility::TYPE_VALUE, $profile->getDateOfBirth()->format('Y-m-d')],
            [$scholarship, Field::AGE, Eligibility::TYPE_VALUE, $profile->getAge()],
            [$scholarship, Field::BIRTHDAY_YEAR, Eligibility::TYPE_VALUE, $profile->getDateOfBirthYear()],
            [$scholarship, Field::BIRTHDAY_MONTH, Eligibility::TYPE_VALUE, $profile->getDateOfBirthMonth()],
            [$scholarship, Field::BIRTHDAY_DAY, Eligibility::TYPE_VALUE, $profile->getDateOfBirthDay()],
            [$scholarship, Field::GENDER, Eligibility::TYPE_VALUE, $profile->getGender()],
            [$scholarship, Field::CITIZENSHIP, Eligibility::TYPE_VALUE, $profile->getCitizenship()->getId()],
            [$scholarship, Field::ETHNICITY, Eligibility::TYPE_VALUE, $profile->getEthnicity()->getId()],
            [$scholarship, Field::COUNTRY, Eligibility::TYPE_VALUE, $profile->getCountry()->getId()],
            [$scholarship, Field::STATE, Eligibility::TYPE_VALUE, $profile->getState()->getId()],
            [$scholarship, Field::CITY, Eligibility::TYPE_VALUE, $profile->getCity()],
            [$scholarship, Field::ADDRESS, Eligibility::TYPE_VALUE, $profile->getAddress()],
            [$scholarship, Field::ZIP, Eligibility::TYPE_VALUE, $profile->getZip()],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_VALUE, $profile->getSchoolLevel()->getId()],
            [$scholarship, Field::DEGREE, Eligibility::TYPE_VALUE, $profile->getDegree()->getId()],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_VALUE, $profile->getDegreeType()->getId()],
            [$scholarship, Field::ENROLLMENT_YEAR, Eligibility::TYPE_VALUE, $profile->getEnrollmentYear()],
            [$scholarship, Field::ENROLLMENT_MONTH, Eligibility::TYPE_VALUE, $profile->getEnrollmentMonth()],
            [$scholarship, Field::GPA, Eligibility::TYPE_VALUE, $profile->getGpa()],
            [$scholarship, Field::CAREER_GOAL, Eligibility::TYPE_VALUE, $profile->getCareerGoal()->getId()],
            [$scholarship, Field::STUDY_ONLINE, Eligibility::TYPE_VALUE, $profile->getStudyOnline()],
            [$scholarship, Field::HIGH_SCHOOL_NAME, Eligibility::TYPE_VALUE, $profile->getHighschool()],
            [$scholarship, Field::HIGH_SCHOOL_GRADUATION_YEAR, Eligibility::TYPE_VALUE, $profile->getHighschoolGraduationYear()],
            [$scholarship, Field::HIGH_SCHOOL_GRADUATION_MONTH, Eligibility::TYPE_VALUE, $profile->getHighschoolGraduationMonth()],
            [$scholarship, Field::COLLEGE_GRADUATION_YEAR, Eligibility::TYPE_VALUE, $profile->getGraduationYear()],
            [$scholarship, Field::COLLEGE_GRADUATION_MONTH, Eligibility::TYPE_VALUE, $profile->getGraduationMonth()],
            [$scholarship, Field::PHONE_AREA_CODE, Eligibility::TYPE_VALUE, $profile->getPhoneAreaCode()],
            [$scholarship, Field::PHONE_PREFIX, Eligibility::TYPE_VALUE, $profile->getPhonePrefix()],
            [$scholarship, Field::PHONE_LOCAL, Eligibility::TYPE_VALUE, $profile->getPhoneLocal()],
            [$scholarship, Field::FULL_NAME, Eligibility::TYPE_VALUE, $profile->getFullName()],
            [$scholarship, Field::COUNTRY_OF_STUDY, Eligibility::TYPE_VALUE, $profile->getStudyCountry1()->getId()],
            [$scholarship, Field::STATE_FREE_TEXT, Eligibility::TYPE_VALUE, $profile->getStateName()],
        ]);

        $this->assertTrue($this->service->isEligible($account, $scholarship));

        /*
         * Change each each field separately and prove that eligibility fails
         */
        $this->updateProfile($profile->setPhone('2227778888888'));
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setDateOfBirth(new \DateTime('2000-02-02'));
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setGender('female');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setCitizenship(Citizenship::CITIZENSHIP_CANADA);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setEthnicity(Ethnicity::ETHNICITY_OTHER);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setCountry(Country::CANADA);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setState(State::STATE_US_CONNECTICUT);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setCity('Yet another City');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setAddress('Yet another Address');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setZip(555555);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setSchoolLevel(2);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setDegree(2);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setDegreeType(DegreeType::DEGREE_BACHELOR);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setEnrollmentYear('1971');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setEnrollmentMonth('02');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setGpa('4.0');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setCareerGoal(2);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setStudyOnline('no');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setHighschool('Yet another school name');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setGraduationYear('1971');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setGraduationMonth('02');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setStudyCountry1(Country::CANADA);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setStateName('Yet another state Name');
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityClause_graterThen()
    {
        $account = $this->generateAccount();
        /** @var Profile $profile */
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();

        $populateProfile = function() use($profile) {
            $profile
                ->setDateOfBirth(new \DateTime('2000-01-03'))
                ->setSchoolLevel(3)
                ->setDegreeType(3)
                ->setGpa('3.5')
                ->setMilitaryAffiliation(3);

                return $profile;
        };

        $this->updateProfile($populateProfile());

        $age = Carbon::instance($profile->getDateOfBirth())->age;

        $this->generateEligibilities([
            [$scholarship, Field::AGE, Eligibility::TYPE_GREATER_THAN, $age - 1],
            [$scholarship, Field::BIRTHDAY_YEAR, Eligibility::TYPE_GREATER_THAN, $profile->getDateOfBirthYear() - 1],
            [$scholarship, Field::BIRTHDAY_MONTH, Eligibility::TYPE_GREATER_THAN, $profile->getDateOfBirthMonth() - 1],
            [$scholarship, Field::BIRTHDAY_DAY, Eligibility::TYPE_GREATER_THAN, $profile->getDateOfBirthDay() - 1],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_GREATER_THAN, $profile->getSchoolLevel()->getId() - 1],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_GREATER_THAN, $profile->getDegreeType()->getId() - 1],
            [$scholarship, Field::GPA, Eligibility::TYPE_GREATER_THAN, $profile->getGpa() - '0.1'],
            [$scholarship, Field::MILITARY_AFFILIATION, Eligibility::TYPE_GREATER_THAN, $profile->getMilitaryAffiliation()->getId() - 1],
        ]);

        $this->assertTrue($this->service->isEligible($account, $scholarship));

        /*
         * Change each each field separately and prove that eligibility fails
         */
        $populateProfile()->setDateOfBirth(new \DateTime('2000-01-01'));
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setSchoolLevel($profile->getSchoolLevel()->getId() - 1);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setDegreeType($profile->getDegreeType()->getId() - 1);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setMilitaryAffiliation($profile->getMilitaryAffiliation()->getId() - 1);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityClause_graterThenOrEqual()
    {
        $account = $this->generateAccount();
        /** @var Profile $profile */
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();

        $populateProfile = function() use($profile) {
            $profile
                ->setDateOfBirth(new \DateTime('2000-01-03'))
                ->setSchoolLevel(3)
                ->setDegreeType(3)
                ->setGpa('3.5');

                return $profile;
        };

        $this->updateProfile($populateProfile());

        $age = Carbon::instance($profile->getDateOfBirth())->age;

        $this->generateEligibilities([
            [$scholarship, Field::AGE, Eligibility::TYPE_GREATER_THAN_OR_EQUAL, $age],
            [$scholarship, Field::BIRTHDAY_YEAR, Eligibility::TYPE_GREATER_THAN_OR_EQUAL, $profile->getDateOfBirthYear()],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_GREATER_THAN_OR_EQUAL, $profile->getSchoolLevel()->getId()],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_GREATER_THAN_OR_EQUAL, $profile->getDegreeType()->getId()],
            [$scholarship, Field::GPA, Eligibility::TYPE_GREATER_THAN_OR_EQUAL, $profile->getGpa() - '0.1'],
        ]);

        $this->assertTrue($this->service->isEligible($account, $scholarship));

        /*
         * Change each each field separately and prove that eligibility fails
         */
        $populateProfile()->setDateOfBirth(new \DateTime('1999-01-01'));
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setSchoolLevel($profile->getSchoolLevel()->getId() - 1);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setDegreeType($profile->getDegreeType()->getId() - 1);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityClause_lessThenOrEqual()
    {
        $account = $this->generateAccount();
        /** @var Profile $profile */
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();

        $populateProfile = function() use($profile) {
            $profile
                ->setDateOfBirth(new \DateTime('2000-01-03'))
                ->setSchoolLevel(3)
                ->setDegreeType(3)
                ->setGpa('3.5');

                return $profile;
        };

        $this->updateProfile($populateProfile());

        $age = Carbon::instance($profile->getDateOfBirth())->age;

        $this->generateEligibilities([
            [$scholarship, Field::AGE, Eligibility::TYPE_LESS_THAN_OR_EQUAL, $age],
            [$scholarship, Field::BIRTHDAY_YEAR, Eligibility::TYPE_LESS_THAN_OR_EQUAL, $profile->getDateOfBirthYear()],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_LESS_THAN_OR_EQUAL, $profile->getSchoolLevel()->getId()],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_LESS_THAN_OR_EQUAL, $profile->getDegreeType()->getId()],
            [$scholarship, Field::GPA, Eligibility::TYPE_LESS_THAN_OR_EQUAL, $profile->getGpa()],
        ]);

        $this->assertTrue($this->service->isEligible($account, $scholarship));

        /*
         * Change each each field separately and prove that eligibility fails
         */
        $populateProfile()->setDateOfBirth(new \DateTime('1999-01-01'));
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setSchoolLevel($profile->getSchoolLevel()->getId() + 1);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setDegreeType($profile->getDegreeType()->getId() + 1);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));
    }

    public function testEligibilityClause_notIn()
    {
        $account = $this->generateAccount();
        /** @var Profile $profile */
        $profile = $account->getProfile();
        $scholarship = $this->generateScholarship();

        $populateProfile = function() use($profile) {
            $profile
                ->setDateOfBirth(new \DateTime('2000-01-03'))
                ->setSchoolLevel(3)
                ->setDegreeType(3)
                ->setGpa('3.5');

                return $profile;
        };


        $this->updateProfile($populateProfile());

        $this->generateEligibilities([
            [$scholarship, Field::BIRTHDAY_YEAR, Eligibility::TYPE_NIN, implode(',',[$profile->getDateOfBirthYear() + 2, $profile->getDateOfBirthYear() + 1])],
            [$scholarship, Field::SCHOOL_LEVEL, Eligibility::TYPE_NIN, implode(',',[$profile->getSchoolLevel()->getId() + 2, $profile->getSchoolLevel()->getId() + 1])],
            [$scholarship, Field::DEGREE_TYPE, Eligibility::TYPE_NIN, implode(',',[$profile->getDegreeType()->getId() + 2, $profile->getDegreeType()->getId() + 1])],
            [$scholarship, Field::GPA, Eligibility::TYPE_NIN, implode(',',[$profile->getGpa() + '0.1', $profile->getGpa() + '0.2'])],
        ]);

        $this->assertTrue($this->service->isEligible($account, $scholarship));

        /*
         * Change each each field separately and prove that eligibility fails
         */
        $populateProfile()->setDateOfBirth(new \DateTime('2002-01-01'));
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setSchoolLevel($profile->getSchoolLevel()->getId() + 2);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));

        $populateProfile()->setDegreeType($profile->getDegreeType()->getId()  + 2);
        $this->updateProfile($profile);
        $this->assertFalse($this->service->isEligible($account, $scholarship));
    }

    public function testIsEligible()
    {
        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();

        $this->assertTrue($this->service->isEligible($account, $scholarship));
        $this->assertFalse($this->service->isEligible($account, 55555));
    }


    protected function updateProfile(Profile $profile)
    {
        $this->em->flush($profile);
        \Event::dispatch(new UpdateAccountEvent($profile->getAccount()));
    }
}

