<?php


namespace Test\Services\Marketing;

use App\Entity\Country;
use App\Entity\Domain;
use App\Entity\Marketing\CoregPlugin;
use App\Listeners\CappexDataDealListener;
use App\Services\Marketing\CoregService;
use App\Services\Marketing\SubmissionService;
use App\Testing\TestCase;

class CappexDataDealTest extends TestCase
{
    public function testUsersAgainst()
    {

        $suitRule = [
            'price' => '2.00',
            'cap' => 10.0,
            'states' =>
                [
                    0 => 'AZ',
                ],
            'grad_year' =>
                [
                    0 => 2020,
                    1 => 2021,
                    2 => 2022,
                ],
            'gpa' => '2.5',
            'gender' => 'N/A',
            'degree' => 'N/A',
            'iped' =>
                [
                    0 => '126182',
                ],
        ];
        $notSuitRule = [
            'price' => '1.00',
            'cap' => 100.0,
            'states' =>
                [
                    0 => 'AZ',
                    1 => 'CO',
                    2 => 'NM',
                    3 => 'TX',
                ],
            'grad_year' =>
                [
                    0 => 2022,
                    1 => 2023,
                ],
            'gpa' => '2.5',
            'gender' => 'N/A',
            'degree' => 'N/A',
            'iped' =>
                [
                    0 => '126182',
                ],
        ];
        $notSuitRuleWithUni = $notSuitRuleWithoutState = $notSuitRuleWithDegree = [
            'price' => '2.00',
            'cap' => 50,
            'states' =>
                [
                    1 => 'CO',
                ],
            'grad_year' =>
                [
                    0 => 2020,
                    1 => 2021,
                ],
            'gpa' => '4.0',
            'gender' => 'female',
            'degree' => 'N/A',
            'iped' =>
                [
                    0 => '188854',
                ],
        ];
        $notSuitRuleWithoutState['states'] = ['Nationwide'];
        $notSuitRuleWithDegree['degree'] = "2";
        $mock = \Mockery::mock(CappexDataDealListener::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getRules')->andReturn(
            [
                $suitRule, $notSuitRule, $notSuitRuleWithUni, $notSuitRuleWithoutState, $notSuitRuleWithDegree
            ]
        );

        $acc = $this->generateAccount('test@test.com',
            'testFirstName',
            'testLastName',
            'testPassword',
            Domain::SCHOLARSHIPOWL,
            false,
            false);
        $profile = $acc->getProfile();
        $profile->setGpa(3.0);
        $profile->setUniversity('Adams State University');
        $profile->setState(3);
        $profile->setHighschoolGraduationYear(2020);

        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals([0 => $suitRule], $res);

        $profile->setHighschoolGraduationYear(2022);
        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals(
            [
                0 => $suitRule,
                1 => $notSuitRule
            ], $res);

        $profile->setHighschoolGraduationYear(2023);
        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals(
            [
                0 => $notSuitRule
            ], $res);

        $profile->setHighschoolGraduationYear(2025);
        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals([], $res);

        $profile->setUniversity('');
        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals([], $res);
        $this->assertNotContains([0 => $notSuitRule], $res);

        $profile->setUniversity('American Musical & Dramatic Academy');
        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals([], $res);
        $this->assertNotContains([0 => $notSuitRule], $res);

        $profile->setGpa('4.1');
        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals([], $res);
        $this->assertNotContains([0 => $notSuitRule], $res);

        $profile->setGender('female');
        $profile->setHighschoolGraduationYear(2020);
        $profile->setState(6);

        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals([$notSuitRuleWithUni, $notSuitRuleWithoutState], $res);

        $accWithoutState = $this->generateAccount('test2@test.com',
            'tes2tFirstName',
            'testLa2stName',
            'testPas2sword',
            Domain::SCHOLARSHIPOWL,
            false,
            false);
        $profileWithoutState = $accWithoutState->getProfile();

        $this->em->flush($profileWithoutState);
        $res = $mock->againstRule($accWithoutState);
        $this->assertEquals([], $res);

        $profileWithoutState->setGpa(4.5);
        $profileWithoutState->setGender('female');
        $profileWithoutState->setUniversity('American Musical & Dramatic Academy');
        $profileWithoutState->setHighschoolGraduationYear(2020);
        $this->em->flush($profileWithoutState);
        $res = $mock->againstRule($accWithoutState);
        $this->assertEquals([$notSuitRuleWithoutState], $res);

        $profileWithoutState->setDegree(2);
        $profileWithoutState->setState(6);
        $this->em->flush($profileWithoutState);
        $res = $mock->againstRule($accWithoutState);
        $this->assertEquals([$notSuitRuleWithUni, $notSuitRuleWithoutState, $notSuitRuleWithDegree], $res);


    }

    public function testOrderBySumAndCap()
    {
        $suitRule = $suitRule2 =  [
            'id' => 'rule_1',
            'price' => '2.00',
            'cap' => 10.0,
            'states' =>
                [
                    0 => 'AZ',
                ],
            'grad_year' =>
                [
                    0 => 2020,
                ],
            'gpa' => '2.5',
            'gender' => 'N/A',
            'degree' => 'N/A',
            'iped' =>
                [
                    0 => '126182',
                ],
        ];
        $suitRule2['price'] = '1.0';
        $suitRule2['states'] = ['Nationwide'];
        $suitRule2['id'] = 'rule_2';

        $mock = \Mockery::mock(CappexDataDealListener::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getRules')->andReturn(
            [
                $suitRule, $suitRule2
            ]
        );

        $acc = $this->generateAccount('test2@test.com',
            'tes2tFirstName',
            'testLa2stName',
            'testPas2sword',
            Domain::SCHOLARSHIPOWL,
            false,
            false);
        $profile = $acc->getProfile();

        $profile->setGpa(4.5);
        $profile->setUniversity('Adams State University');
        $profile->setHighschoolGraduationYear(2020);
        $profile->setState(3);
        $this->em->flush($profile);
        $res = $mock->againstRule($acc);
        $this->assertEquals([$suitRule, $suitRule2], $res);

        //set flag for changing the order for rules
        $res = $mock->againstRule($acc, true);
        $this->assertEquals([$suitRule2, $suitRule], $res);
    }

    public function testOriginalRules()
    {
        $ss = app(SubmissionService::class);
        $dataDeal = new CappexDataDealListener($ss);
        $acc = $this->generateAccount('test2@test.com',
            'tes2tFirstName',
            'testLa2stName',
            'testPas2sword',
            Domain::SCHOLARSHIPOWL,
            false,
            false);
        $profile = $acc->getProfile();

        $profile->setGpa(4.5);
        $profile->setUniversity('Alverno College');
        $profile->setHighschoolGraduationYear(2021);
        $profile->setGender('female');
        $profile->setState(14);
        $this->em->flush($profile);
        $res = $dataDeal->againstRule($acc);
        $this->assertEquals([[
            'price' => '1.00',
            'cap' => 87,
            'states' =>
                [
                    0 => 'WI',
                    1 => 'IA',
                    2 => 'IL',
                    3 => 'MI',
                    4 => 'MN',
                    5 => 'IN',
                    6 => 'TX',
                ],
            'grad_year' =>
                [
                    0 => 2020,
                    1 => 2021,
                    2 => 2022,
                ],
            'gpa' => '2.4',
            'gender' => 'Female',
            'degree' => 'N/A',
            'iped' =>
                [
                    0 => '238193',
                ],
        ]], $res);
    }

}