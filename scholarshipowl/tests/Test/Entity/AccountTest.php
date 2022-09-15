<?php namespace Test\Entity;

use App\Entity\Account;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use Carbon\Carbon;

class AccountTest extends TestCase
{
    use EntityGenerator;

    public function testReferralAndReferredAccounts()
    {
        static::$truncate[] = 'referral';

        $account1 = $this->generateAccount('test1@test.com');
        $account2 = $this->generateAccount('test2@test.com');

        $account1->addReferredAccount($account2);

        \EntityManager::flush();
        $this->assertDatabaseHas('referral', [
            'referral_account_id' => $account1->getAccountId(),
            'referred_account_id' => $account2->getAccountId(),
        ]);

        \EntityManager::clear();
        $account3 = $this->generateAccount('test3@test.com');

        /** @var Account $account1 */
        $account1 = \EntityManager::find(Account::class, $account1->getAccountId());
        $account1->addReferredAccount($account3);

        \EntityManager::flush();
        $this->assertDatabaseHas('referral', [
            'referral_account_id' => $account1->getAccountId(),
            'referred_account_id' => $account3->getAccountId(),
        ]);

        $referred = $account1->getReferredAccount();

        $this->assertCount(2, $referred);
        $this->assertEquals(\EntityManager::find(Account::class, $account2->getAccountId()), $referred[0]);
        $this->assertEquals(\EntityManager::find(Account::class, $account3->getAccountId()), $referred[1]);
    }

    public function testAccountMapTags()
    {
        $account = $this->generateAccount('test@test.com');
        $this->fillProfileData($account->getProfile());
        $age = Carbon::createFromDate(2000,9,6)->age;

        $this->assertEquals('test@application-inbox.com', $account->mapTags("[[email]]"));
        $this->assertEquals('Testfirstname', $account->mapTags('[[first_name]]'));
        $this->assertEquals('Testlastname', $account->mapTags('[[last_name]]'));
        $this->assertEquals('+12345678900', $account->mapTags('[[phone]]'));
        $this->assertEquals('(234) 567 - 8900', $account->mapTags('[[phone_mask]]'));
        $this->assertEquals('Male', $account->mapTags('[[gender]]'));
        $this->assertEquals('U.S. Citizen', $account->mapTags('[[citizenship]]'));
        $this->assertEquals('Caucasian', $account->mapTags('[[ethnicity]]'));
        $this->assertEquals('USA', $account->mapTags('[[country]]'));
        $this->assertEquals('Alabama', $account->mapTags('[[state]]'));
        $this->assertEquals('AL', $account->mapTags('[[state_abbreviation]]'));
        $this->assertEquals('New York', $account->mapTags('[[city]]'));
        $this->assertEquals('Street Name 1 apt. 1', $account->mapTags('[[address]]'));
        $this->assertEquals('12345', $account->mapTags('[[zip]]'));
        $this->assertEquals('High school freshman', $account->mapTags('[[school_level]]'));
        $this->assertEquals('Agriculture and Related Sciences', $account->mapTags('[[degree]]'));
        $this->assertEquals('Undecided', $account->mapTags('[[degree_type]]'));
        $this->assertEquals('2015', $account->mapTags('[[enrollment_year]]'));
        $this->assertEquals('9', $account->mapTags('[[enrollment_month]]'));
        $this->assertEquals('test_gpa_value', $account->mapTags('[[gpa]]'));
        $this->assertEquals('Art, Design or Fashion', $account->mapTags('[[career_goal]]'));
        $this->assertEquals('2014', $account->mapTags('[[graduation_year]]'));
        $this->assertEquals('8', $account->mapTags('[[graduation_month]]'));
        $this->assertEquals('Yes', $account->mapTags('[[study_online]]'));
        $this->assertEquals('Highschool value', $account->mapTags('[[highschool]]'));
        $this->assertEquals('University value', $account->mapTags('[[university]]'));
        $this->assertEquals('09/06/2000', $account->mapTags('[[date_of_birth]]'));
        $this->assertEquals("$age", $account->mapTags('[[age]]'));
        $this->assertEquals(
            "test@application-inbox.com [[test]] $age Testfirstname 12345",
            $account->mapTags('[[email]] [[test]] [[age]] [[first_name]] [[zip]]')
        );
    }

}
