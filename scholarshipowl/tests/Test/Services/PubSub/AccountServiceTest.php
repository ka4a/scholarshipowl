<?php

namespace Test\Services\PubSub;

use App\Entity\Eligibility;
use App\Entity\Field;
use App\Entity\Package;
use App\Entity\SubscriptionStatus;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Http\Traits\JsonResponses;
use App\Services\PubSub\AccountService;
use App\Testing\Traits\JsonResponseAsserts;
use Carbon\Carbon;

include_once 'PubSubServiceAbstractTest.php';

class AccountServiceTest extends PubSubServiceAbstractTest
{
    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();
        static::$truncate = ['eligibility_cache', 'scholarship', 'email'];
    }

    public function getService(){
        return app(AccountService::class);
    }

    public function testAddOrUpdateAccount()
    {
        $account = $this->generateAccount();
        $this->service->addOrUpdateAccount($account);
    }

    public function testUpdateAccount()
    {
        $account = $this->generateAccount();
        $this->service->updateAccount($account, [AccountService::FIELD_DATE_OF_BIRTH]);
    }

    public function testUpdateAccount_withSubscription()
    {
        $package = $this->generatePackage(Package::EXPIRATION_TYPE_RECURRENT, 'testPackage', 25)
            ->setExpirationPeriodType(Package::EXPIRATION_PERIOD_TYPE_MONTH)
            ->setFreeTrial(true);

        $account = $this->generateAccount();

        $subscription = $this->generateSubscription($package, $account)
            ->setRenewalDate(Carbon::now()->addMonth());


        $transaction = $this->generateTransaction($subscription);
        $subscription->setTransactions([$transaction]);
        $this->em->persist($subscription);
        $this->em->flush($subscription);


        $fields = $this->service->addOrUpdateAccount($account);

        $this->assertArrayContains([
            AccountService::FIELD_TRIAL => 'Yes',
            AccountService::FIELD_SUBSCRIPTION_IS_PAID => 'Yes',
            AccountService::FIELD_PACKAGE => 'testPackage',
            AccountService::FIELD_PACKAGE_PRICE => 25,
        ], $fields);

        $subscription->setSubscriptionStatus(SubscriptionStatus::CANCELED);
        $subscription->setActiveUntil(Carbon::now());
        $this->em->flush($subscription);

        $fields = $this->service->addOrUpdateAccount($account);
        $this->assertArrayContains([
            AccountService::FIELD_SUBSCRIPTION_IS_PAID => 'No',
            AccountService::FIELD_MEMBERSHIP_STATUS => 'Cancelled',
        ], $fields);

        $subscription->setActiveUntil(Carbon::tomorrow());
        $this->em->flush($subscription);

        $fields = $this->service->addOrUpdateAccount($account);
        $this->assertArrayContains([
            AccountService::FIELD_SUBSCRIPTION_IS_PAID => 'Yes',
            AccountService::FIELD_MEMBERSHIP_STATUS => 'Cancelled',
        ], $fields);
    }


    public function testDeleteAccount()
    {
        $account = $this->generateAccount();
        $this->service->deleteAccount($account->getAccountId());
    }

    public function testFields()
    {
        $list = $this->service->fields();
        $this->assertTrue(in_array(AccountService::FIELD_PACKAGE, $list));
        $this->assertTrue(in_array(AccountService::FIELD_EMAIL, $list));
        $this->assertTrue(in_array(AccountService::FIELD_FSET, $list));
    }

    public function testPopulateMergeFields()
    {
        $account = $this->generateAccount();
        $fields = $this->service->populateMergeFields([$account])[$account->getAccountId()];
        $list = $this->service->fields();
        $this->assertTrue(count(array_intersect(array_keys($fields), array_values($list))) === count($list));
    }

    public function testScholarshipFields()
    {
        $account = $this->generateAccount();

        // eligible, not expiring
        $scholarship1 = $this->generateScholarship();
        $scholarship1->setAmount(500);
        $scholarship1->setExpirationDate(Carbon::now()->addDays(10));
        \EntityManager::flush($scholarship1);
        // needed to update eligibility cache
        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship1, true, true));

        // eligible, expiring
        $scholarship2 = $this->generateScholarship();
        $scholarship2->setTitle('Test 2');
        \EntityManager::flush($scholarship2);

        // eligible, expiring
        $scholarship3 = $this->generateScholarship();
        $scholarship3->setTitle('Test 3');
        \EntityManager::flush($scholarship3);

        // not eligible, not expiring
        $scholarship4 = $this->generateScholarship();
        $scholarship4->setExpirationDate(Carbon::now()->addDays(10));
        \EntityManager::flush($scholarship4);
        $this->generateEligibility($scholarship4, Field::COUNTRY_OF_STUDY, Eligibility::TYPE_REQUIRED);

        // not eligible, expiring
        $scholarship5 = $this->generateScholarship();
        $this->generateEligibility($scholarship5, Field::COUNTRY_OF_STUDY, Eligibility::TYPE_REQUIRED);


        $fields = $this->service->populateMergeFields([$account])[$account->getAccountId()];
        $this->assertTrue($fields[AccountService::FIELD_UNREAD_MESSAGES_LIST] !== '');
        // welcome email only. Other are marked as read, see MailboxStubDriver::generateEmails()
        $this->assertTrue($fields[AccountService::FIELD_UNREAD_MESSAGES_COUNT] === 1);

        $this->assertTrue($fields[AccountService::FIELD_SCHOLARSHIP_EL_COUNT_NEW] === 3);
        $this->assertTrue($fields[AccountService::FIELD_SCHOLARSHIP_EL_COUNT] === 3);
        $this->assertTrue($fields[AccountService::FIELD_SCHOLARSHIP_EL_COUNT_EXPIRING] === 2);

        $this->assertTrue(
            $fields[AccountService::FIELD_SCHOLARSHIP_EL_AMOUNT] == (
                $scholarship1->getAmount() + $scholarship2->getAmount() + $scholarship3->getAmount())
        );
        $this->assertTrue(
            $fields[AccountService::FIELD_SCHOLARSHIP_EL_AMOUNT_NEW] == (
                $scholarship1->getAmount() + $scholarship2->getAmount() + $scholarship3->getAmount())
        );
        $this->assertTrue(
            $fields[AccountService::FIELD_SCHOLARSHIP_EL_AMOUNT_EXPIRING] == ($scholarship2->getAmount() + $scholarship3->getAmount())
        );

        $list = [
            '$'.number_format((int)$scholarship2->getAmount(), 0, '.', ',').' | '.$scholarship2->getTitle().' | '.$scholarship2->getExpirationDate()->format('m/d/Y'),
            '$'.number_format((int)$scholarship3->getAmount(), 0, '.', ',').' | '.$scholarship3->getTitle().' | '.$scholarship3->getExpirationDate()->format('m/d/Y'),
        ];
        $this->assertTrue(
            $fields[AccountService::FIELD_SCHOLARSHIP_EL_LIST_EXPIRING] == implode("\n", $list)
        );
    }
}
