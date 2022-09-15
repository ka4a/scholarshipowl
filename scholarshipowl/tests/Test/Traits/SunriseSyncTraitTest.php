<?php

namespace Test\Traits;

use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Eligibility;
use App\Entity\Ethnicity;
use App\Entity\Field;
use App\Entity\Profile;
use App\Testing\TestCase;
use App\Traits\PhoneFormatter;
use App\Traits\SunriseSync;
use Carbon\Carbon;

class SunriseSyncTraitTest extends TestCase
{
    /** @var SunriseSync|\PHPUnit_Framework_MockObject_MockObject */
    protected $traitMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->traitMock = $this->getMockForTrait(SunriseSync::class);
    }

    public function testEligibilityOperatorMap()
    {
        $this->assertTrue(Eligibility::TYPE_VALUE === $this->traitMock->eligibilityOperatorMap()['eq']);
        $this->assertTrue(Eligibility::TYPE_REQUIRED === $this->traitMock->eligibilityOperatorMap()['']);
        $this->assertTrue(Eligibility::TYPE_BETWEEN === $this->traitMock->eligibilityOperatorMap()['between']);
    }

    public function testEligibilityFieldsMap()
    {
        $this->assertTrue(Field::DEGREE === $this->traitMock->eligibilityFieldsMap()['fieldOfStudy'][0]);
        $this->assertTrue(Field::FULL_NAME === $this->traitMock->eligibilityFieldsMap()['name'][0]);
    }

    public function testReverseEligibilityFieldsMap()
    {
        $this->assertTrue($this->traitMock->reverseEligibilityFieldsMap()[Field::DEGREE] === 'fieldOfStudy');
        $this->assertTrue($this->traitMock->reverseEligibilityFieldsMap()[Field::FULL_NAME] === 'name');
        $this->assertTrue($this->traitMock->reverseEligibilityFieldsMap()[Field::ENROLLMENT_MONTH] === 'enrollmentDate');
    }

    public function testResolveEligibilityField()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();
        $profile->setFirstName('John');
        $profile->setLastName('Doe');
        $profile->setPhone('+23456789');
        $profile->setDateOfBirth(Carbon::instance(new \DateTime())->subDays(6570));
        $profile->setState(44);
        $profile->setCity('Nashville');
        $profile->setCountry(Country::USA);
        $profile->setAddress('address1');
        $profile->setAddress2('address2');
        $profile->setZip(37027);
        $profile->setSchoolLevel(2);
        $profile->setDegree(3);
        $profile->setDegreeType(5);
        $profile->setGpa('3.9');
        $profile->setGender(Profile::GENDER_FEMALE);
        $profile->setEthnicity(Ethnicity::ETHNICITY_CAUCASIAN);
        $profile->setCitizenship(Citizenship::CITIZENSHIP_USA);
        $profile->setEnrolled(true);
        $profile->setEnrollmentMonth('10');
        $profile->setEnrollmentYear('2019');
        $profile->setCareerGoal(5);
        $profile->setGraduationMonth('05');
        $profile->setGraduationYear('2019');
        $profile->setUniversity('MIT');
        $profile->setHighschool('Bronx');
        $profile->setMilitaryAffiliation(3);

        \EntityManager::flush($profile);


        $fields = [
            Field::FULL_NAME => $profile->getFullName(),
            Field::EMAIL => $account->getEmail(),
            Field::PHONE => $profile->getPhone(),
            Field::DATE_OF_BIRTH => $profile->getDateOfBirth()->format('Y-m-d'),
            Field::AGE => $profile->getDateOfBirth()->format('Y-m-d'),
            Field::COUNTRY => $profile->getCountry()->getId(),
            Field::STATE => $profile->getState()->getId(),
            Field::CITY => $profile->getCity(),
            Field::ADDRESS => $profile->getFullAddress(),
            Field::ZIP => $profile->getZip(),
            Field::SCHOOL_LEVEL => $profile->getSchoolLevel()->getId(),
            Field::DEGREE => $profile->getDegree()->getId(),
            Field::DEGREE_TYPE => $profile->getDegreeType()->getId(),
            Field::GPA => 21, // 21 is mapped to '3.9'
            Field::GENDER => 1, // female
            Field::ETHNICITY => $profile->getEthnicity()->getId(),
            Field::CITIZENSHIP => $profile->getCitizenship()->getId(),
            Field::ENROLLMENT_MONTH => sprintf('01-%02d-%d', $profile->getEnrollmentMonth(), $profile->getEnrollmentYear()),
            Field::ENROLLMENT_YEAR => sprintf('01-%02d-%d', $profile->getEnrollmentMonth(), $profile->getEnrollmentYear()),
            Field::CAREER_GOAL => $profile->getCareerGoal()->getId(),
            Field::HIGH_SCHOOL_GRADUATION_MONTH => null, // if user is enrolled in collage, then graduation month and year are about collage
            Field::HIGH_SCHOOL_GRADUATION_YEAR => null,
            Field::COLLEGE_NAME => $profile->getUniversity(),
            Field::COLLEGE_GRADUATION_MONTH => sprintf('01-%02d-%d', $profile->getGraduationMonth(), $profile->getGraduationYear()),
            Field::COLLEGE_GRADUATION_YEAR => sprintf('01-%02d-%d', $profile->getGraduationMonth(), $profile->getGraduationYear()),
            Field::HIGH_SCHOOL_NAME => $profile->getHighschool(),
            Field::MILITARY_AFFILIATION => $this->traitMock->eligibilityMapMilitaryAffiliation($profile->getMilitaryAffiliation()->getId(), true),
        ];

        foreach ($fields as $k => $v) {
            $this->assertTrue($v === $this->traitMock->resolveEligibilityField($account, $k));
        }
    }

    public function testEligibilityMapGpa()
    {
        $this->assertTrue(1 === $this->traitMock->eligibilityMapGpa('N/A', true));
        $this->assertTrue(7 === $this->traitMock->eligibilityMapGpa('2.5', true));
        $this->assertTrue(8 === $this->traitMock->eligibilityMapGpa('2.6', true));

        $this->assertTrue('N/A' === $this->traitMock->eligibilityMapGpa(1));
        $this->assertTrue('2.5' === $this->traitMock->eligibilityMapGpa(7));
        $this->assertTrue('2.6' === $this->traitMock->eligibilityMapGpa(8));
    }

    public function testEligibilityMapGender()
    {
        $this->assertTrue(1 === $this->traitMock->eligibilityMapGender('female', true));
        $this->assertTrue(2 === $this->traitMock->eligibilityMapGender('male', true));
        $this->assertTrue(3 === $this->traitMock->eligibilityMapGender('other', true));

        $this->assertTrue('female' === $this->traitMock->eligibilityMapGender(1));
        $this->assertTrue('male' === $this->traitMock->eligibilityMapGender(2));
        $this->assertTrue('other' === $this->traitMock->eligibilityMapGender(3));
    }

    public function testEligibilityMapMilitaryAffiliation()
    {
        $this->assertTrue(1 === $this->traitMock->eligibilityMapMilitaryAffiliation('0', true));
        $this->assertTrue(11 === $this->traitMock->eligibilityMapMilitaryAffiliation('10', true));
        $this->assertTrue(29 === $this->traitMock->eligibilityMapMilitaryAffiliation('28', true));

        $this->assertTrue('0' === $this->traitMock->eligibilityMapMilitaryAffiliation(1));
        $this->assertTrue('10' === $this->traitMock->eligibilityMapMilitaryAffiliation(11));
        $this->assertTrue('28' === $this->traitMock->eligibilityMapMilitaryAffiliation(29));
    }

    public function testNormalizeEligibilities()
    {

        $data = [
            [
                'id' => 8273,
                'eligibilityType' => 'eq',
                'eligibilityValue' => '0',
                'optional' => true,
                'field' => [
                    'id' => 'enrolledInCollege',
                    'name' => 'Enrolled in College',
                    'type' => 'option',
                    'options' => [
                        '1' => 'Yes',
                        '2' => 'No'
                    ]
                ]
            ],
            [
                'id' => 3992,
                'eligibilityType' => 'lt',
                'eligibilityValue' => '10',
                'optional' => false,
                'field' => [
                    'id' => 'GPA',
                    'name' => 'GPA',
                    'type' => 'option',
                    'options' => [
                        '1' => 'N/A',
                        '2' => '2.0',
                        '3' => '2.1',
                        '4' => '2.2',
                        '5' => '2.3',
                        '6' => '2.4',
                        '7' => '2.5',
                        '8' => '2.6',
                        '9' => '2.7',
                        '10' => '2.8',
                        '11' => '2.9',
                        '12' => '3.0',
                        '13' => '3.1',
                        '14' => '3.2',
                        '15' => '3.3',
                        '16' => '3.4',
                        '17' => '3.5',
                        '18' => '3.6',
                        '19' => '3.7',
                        '20' => '3.8',
                        '21' => '3.9',
                        '22' => '4.0'
                    ]
                ]
            ],
            [
                'id' => 2238,
                'eligibilityType' => null,
                'eligibilityValue' => null,
                'field' => [
                    'id' => 'phone',
                    'name' => 'Phone',
                    'type' => 'phone',
                    'options' => []
                ]
            ]
        ];

        $this->traitMock->normalizeEligibilities($data);

        $this->assertArrayContains([
            'alias' => 'enrolledInCollege',
            'isOptional' => true,
            'operator' => 'boolean',
            'value' => 0
        ], $data[0]);

        $this->assertArrayContains([
            'alias' => 'GPA',
            'isOptional' => false,
            'operator' => 'less_than',
            'value' => '2.8'
        ], $data[1]);

        $this->assertArrayContains([
            'alias' => 'phone',
            'isOptional' => false,
            'operator' => 'required',
            'value' => ''
        ], $data[2]);
    }
}