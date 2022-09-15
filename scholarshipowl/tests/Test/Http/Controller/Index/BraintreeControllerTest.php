<?php namespace Test\Http\Controller\Index;

use App\Entity\PaymentMethod;

use App\Payment\Braintree\BraintreeTransactionData;
use App\Testing\TestCase;
use App\Testing\Braintree\WebhookTesting;
use App\Testing\Traits\EntityGenerator;

use Braintree\WebhookNotification;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BraintreeControllerTest extends TestCase
{
    use WithoutMiddleware;
    use EntityGenerator;

    const ROUTE = '/webhook';

    public function setUp(): void
    {
        parent::setUp();

        static::$truncate[] = 'subscription';
        static::$truncate[] = 'transaction';
    }

    public  function testRouteWebhookFailure()
    {
        $resp = $this->post(static::ROUTE);
        $this->assertTrue($resp->status() === 500);
    }

    public function testRouteWebhookKindTest()
    {
        $mockNotification = WebhookTesting::sampleNotification(WebhookNotification::CHECK, 1);
        $resp = $this->post(static::ROUTE, [
            'bt_signature' => $mockNotification['bt_signature'],
            'bt_payload' => $mockNotification['bt_payload'],
        ]);

        $this->assertTrue($resp->status() === 200);
    }

    public function testRouteWebhookKindSubscriptionChargeSuccessfully()
    {
        $subscription = $this->generateSubscription($this->generatePackage(), $this->generateAccount(), PaymentMethod::BRAINTREE);
        $subscription->setExternalId(666);
        \EntityManager::flush($subscription);

        \PaymentManager::shouldReceive('subscriptionPayment')->once()->with($subscription, \Mockery::type(BraintreeTransactionData::class));

        $mockNotification = WebhookTesting::sampleNotification(WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY, 666);
        $resp = $this->post(static::ROUTE, [
            'bt_signature' => $mockNotification['bt_signature'],
            'bt_payload' => $mockNotification['bt_payload'],
        ]);

        $this->assertTrue($resp->status() === 200);
    }

    public function testRouteWebhookKindSubscriptionChargeSuccessfullyOnTransactionExists()
    {
        $subscription = $this->generateSubscription($this->generatePackage(), $this->generateAccount(), PaymentMethod::BRAINTREE);
        $subscription->setExternalId(666);
        \EntityManager::flush($subscription);

        $transaction = $this->generateTransaction($subscription, PaymentMethod::BRAINTREE);
        $transaction->setProviderTransactionId('2-ksfljuen');
        $transaction->setBankTransactionId('2-ksfljuen');
        \EntityManager::flush($transaction);

        \PaymentManager::shouldReceive('subscriptionPayment')->never();

        $mockNotification = WebhookTesting::sampleNotification(WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY, 666);
        $resp = $this->post(static::ROUTE, [
            'bt_signature' => $mockNotification['bt_signature'],
            'bt_payload' => $mockNotification['bt_payload'],
        ]);

        $this->assertTrue($resp->status() === 200);
    }
}
