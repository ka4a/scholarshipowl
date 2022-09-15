<?php namespace Test\Services;

use App\Entity\FeatureSet;
use App\Entity\Package;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use App\Entity\TransactionPaymentType;
use App\Entity\TransactionStatus;
use App\Entity\SubscriptionAcquiredType;
use App\Payment\Events\SubscriptionCancelledEvent;
use App\Payment\Events\SubscriptionExpiredEvent;
use App\Payment\Events\SubscriptionPaymentEvent;
use App\Payment\Events\SubscriptionPaymentFailedEvent;
use App\Payment\Braintree\BraintreeTransactionData as TransactionData;
use App\Payment\Events\SubscriptionAddEvent;

use App\Payment\RemotePaymentManager;
use App\Services\PaymentManager;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Database\Query\Expression;
use Mockery as m;

class PaymentManagerTest extends TestCase
{
    use EntityGenerator;

    /**
     * @var PaymentManager
     */
    protected $paymentManager;

    public function setUp(): void
    {
        parent::setUp();
        FeatureSet::set($fset = $this->generateFeatureSet());
        $this->withoutEvents();
        $this->app->singleton(PaymentManager::class, function(Container $app) {
            return new PaymentManager($app->make('em'), $app->make('events'), $app->make('payment.remote_manager'));
        });
        $this->paymentManager = app(PaymentManager::class);
    }

    public function tearDown(): void
    {
        static::$truncate[] = 'subscription';
        static::$truncate[] = 'transaction';

        parent::tearDown();
    }

    protected function generateTransactionData() : TransactionData
    {
        return new TransactionData(
            [], 101.99, 'bank_transaction', 'provided_transaction', 'mobile', TransactionPaymentType::CREDIT_CARD
        );
    }

    public function testApplyFreeTrialPackage()
    {
        $this->expectsEvents([SubscriptionAddEvent::class, SubscriptionPaymentEvent::class]);
        $account = $this->generateAccount();
        $package = $this->generatePackage()
            ->setFreeTrial(true)
            ->setFreeTrialPeriod(Package::EXPIRATION_PERIOD_TYPE_DAY)
            ->setFreeTrialPeriod(7);
        \EntityManager::flush();

        /** @var Subscription $subscription */
        list($subscription,) = $this->paymentManager
            ->applyPackageOnAccount($account, $package, SubscriptionAcquiredType::PURCHASED);

        $this->assertDatabaseHas('subscription', [
            'account_id' => $account->getAccountId(),
            'package_id' => $package->getPackageId(),
            'free_trial' => true,
        ]);

        $this->paymentManager->subscriptionPayment($subscription, $this->generateTransactionData());

        $this->assertDatabaseHas('subscription', [
            'account_id' => $account->getAccountId(),
            'package_id' => $package->getPackageId(),
            'free_trial' => false,
        ]);
    }

    public function testFreeTrialPeriodDates()
    {
        $format = 'Y-m-d';
        $now = Carbon::now();
        $this->expectsEvents([SubscriptionAddEvent::class]);
        $account = $this->generateAccount();
        $package = $this->generatePackage()
            ->setFreeTrial(true)
            ->setFreeTrialPeriod(Package::EXPIRATION_PERIOD_TYPE_DAY)
            ->setFreeTrialPeriodValue(7);
        \EntityManager::flush($package);

        /** @var Subscription $subscription */
        list($subscription,) = $this->paymentManager
            ->applyPackageOnAccount($account, $package, SubscriptionAcquiredType::PURCHASED);

        $this->assertEquals(
            $now->copy()->addDays(7)->format($format),
            $subscription->getFreeTrialEndDate()->format($format)
        );

        \EntityManager::flush(
            $package->setFreeTrialPeriod(Package::EXPIRATION_PERIOD_TYPE_WEEK)->setFreeTrialPeriodValue(3)
        );

        /** @var Subscription $subscription */
        list($subscription,) = $this->paymentManager
            ->applyPackageOnAccount($account, $package, SubscriptionAcquiredType::PURCHASED);

        $this->assertEquals(
            $now->copy()->addWeek(3)->format($format),
            $subscription->getFreeTrialEndDate()->format($format)
        );

        \EntityManager::flush(
            $package->setFreeTrialPeriod(Package::EXPIRATION_PERIOD_TYPE_MONTH)->setFreeTrialPeriodValue(2)
        );

        /** @var Subscription $subscription */
        list($subscription,) = $this->paymentManager
            ->applyPackageOnAccount($account, $package, SubscriptionAcquiredType::PURCHASED);

        $this->assertEquals(
            $now->copy()->addMonth(2)->format($format),
            $subscription->getFreeTrialEndDate()->format($format)
        );

        \EntityManager::flush(
            $package->setFreeTrialPeriod(Package::EXPIRATION_PERIOD_TYPE_YEAR)->setFreeTrialPeriodValue(1)
        );

        /** @var Subscription $subscription */
        list($subscription,) = $this->paymentManager
            ->applyPackageOnAccount($account, $package, SubscriptionAcquiredType::PURCHASED);

        $this->assertEquals(
            $now->copy()->addYear()->format($format),
            $subscription->getFreeTrialEndDate()->format($format)
        );
    }

    public function testApplyPackageOnAccountWithoutTransaction()
    {
        $this->expectsEvents(SubscriptionAddEvent::class);

        $account = $this->generateAccount();
        $package = $this->generatePackage();

        \PaymentManager::applyPackageOnAccount($account, $package, SubscriptionAcquiredType::FREEBIE);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => 1,
            'package_id' => $package->getPackageId(),
            'account_id' => $account->getAccountId(),
            'subscription_acquired_type_id' => SubscriptionAcquiredType::FREEBIE,
        ]);
    }

    public function testApplyPackageOnAccountWithTransaction()
    {
        $this->expectsEvents(SubscriptionAddEvent::class);

        $account = $this->generateAccount();
        $package = $this->generatePackage(Package::EXPIRATION_TYPE_PERIOD, 'testpackage', 101.99);

        $transaction = new TransactionData(array(), 101.99, 'bank_transaction', 'provided_transaction', 'mobile', TransactionPaymentType::CREDIT_CARD);
        \PaymentManager::applyPackageOnAccount($account, $package, SubscriptionAcquiredType::PURCHASED, $transaction);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => 1,
            'package_id' => $package->getPackageId(),
            'account_id' => $account->getAccountId(),
            'subscription_acquired_type_id' => SubscriptionAcquiredType::PURCHASED,
            'recurrent_count' => null,
        ]);

        $this->assertDatabaseHas('transaction', [
            'transaction_id' => 1,
            'subscription_id' => 1,
            'account_id' => $account->getAccountId(),
            'recurrent_number' => null,
            'bank_transaction_id' => 'bank_transaction',
            'provider_transaction_id' => 'provided_transaction',
            'transaction_status_id' => 1,
            'device' => 'mobile',
            'amount' => 101.99
        ]);
    }

    public function testSubscriptionSuccessPayment()
    {
        $this->expectsEvents(SubscriptionPaymentEvent::class);

        $account = $this->generateAccount();
        $package = $this->generatePackage();
        $subscription = $this->generateSubscription($package, $account);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::ACTIVE,
            'recurrent_count' => 0,
        ]);

        \PaymentManager::subscriptionPayment(
            $subscription,
            new TransactionData(array(), 101.99, 'bank_transaction', 'provided_transaction', 'mobile', TransactionPaymentType::CREDIT_CARD));

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::ACTIVE,
            'recurrent_count' => 1,
        ]);

        $this->assertDatabaseHas('transaction', [
            'transaction_id' => 1,
            'subscription_id' => $subscription->getSubscriptionId(),
            'recurrent_number' => 1,
            'bank_transaction_id' => 'bank_transaction',
            'provider_transaction_id' => 'provided_transaction',
            'transaction_status_id' => TransactionStatus::SUCCESS,
            'device' => 'mobile',
            'amount' => 101.99
        ]);
    }

    public function testSubscriptionFailedPayment()
    {
        $this->expectsEvents(SubscriptionPaymentFailedEvent::class);

        $account = $this->generateAccount();
        $package = $this->generatePackage();
        $subscription = $this->generateSubscription($package, $account);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::ACTIVE,
            'recurrent_count' => 0,
        ]);

        \PaymentManager::subscriptionPaymentFailed($subscription);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::ACTIVE,
            'recurrent_count' => 0,
        ]);
    }

    public function testSubscriptionExpire()
    {
        $this->expectsEvents(SubscriptionExpiredEvent::class);

        $account = $this->generateAccount();
        $package = $this->generatePackage();
        $subscription = $this->generateSubscription($package, $account);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::ACTIVE,
        ]);

        \PaymentManager::expireSubscription($subscription, true);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::EXPIRED,
            [new Expression('date(terminated_at)'), '=', (new \DateTime())->format('Y-m-d')]
        ]);
    }

    public function testSubscriptionCancel()
    {
        $this->expectsEvents(SubscriptionCancelledEvent::class);

        $account = $this->generateAccount();
        $package = $this->generatePackage();
        $subscription = $this->generateSubscription($package, $account);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::ACTIVE,
        ]);

        $remotePaymentManager = m::mock(RemotePaymentManager::class)
            ->shouldReceive('cancelSubscription')
            ->with($subscription, false)
            ->andReturnUsing(function($subscription, $flush) {
                $subscription->setRemoteStatus(Subscription::CANCELLED);
                \EntityManager::flush($subscription);
            })
            ->getMock();

        $this->paymentManager->setRemotePaymentManager($remotePaymentManager)
            ->cancelSubscription($subscription);

        $this->assertDatabaseHas('subscription', [
            'subscription_id' => $subscription->getSubscriptionId(),
            'subscription_status_id' => SubscriptionStatus::CANCELED,
            'remote_status' => Subscription::CANCELLED,
            [new Expression('date(terminated_at)'), '=', (new \DateTime())->format('Y-m-d')]
        ]);
    }
}
