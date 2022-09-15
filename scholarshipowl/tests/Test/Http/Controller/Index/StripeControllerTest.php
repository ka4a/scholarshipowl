<?php namespace Test\Http\Controller\Index;

use App\Entity\FeatureSet;
use App\Entity\PaymentMethod;
use App\Http\Controllers\Index\StripeController;
use App\Services\PaymentManager;
use App\Services\StripeService;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Token;


class StripeControllerTest extends TestCase
{
    use WithoutMiddleware;
    use EntityGenerator;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'subscription';
        static::$truncate[] = 'transaction';
    }

    public function testStripeApplyingActions(){
        FeatureSet::set($fset = $this->generateFeatureSet());
        $this->withSession(['payment_return' => true]);
        Stripe::setApiKey("pk_test_EJQTqrrWoe68a1W6M79zpLS5");

        $token = new Token('tok_1BZ1vdBP89lJVtQS25e1EckF', array(
            "card" => array(
                "number" => "4242424242424242",
                "exp_month" => 1,
                "exp_year" => 2022,
                "cvc" => "314"
            )
        ));

        $account = $this->generateAccount();
        $pck1 = $this->generatePackage()->setStripePlan('monthly_stripe');
        \EntityManager::persist($pck1);
        \EntityManager::flush($pck1);
        $this->actingAs($account);

        $mockStripeService = $this->createMock(StripeService::class);
        $mockStripeService->expects($this->any())->method('subscribe')->will($this->returnValue($this->getFakeStripeSubscription()));

        $data = ['stripe_token' => $token['id'], 'package_id' => $pck1->getPackageId()];

        /**
         * @var $controller StripeController
         */
        $pm = app(PaymentManager::class);
        $controller = new StripeController($this->em, $pm, $mockStripeService);

        $path = $this->app['url']->route('stripe.post', $data);
        $request = Request::create( $path, 'POST', $data );
        $request->setLaravelSession($this->app['session']->driver());
        $result = $controller->applyPackage($request, $account);
        $this->assertDatabaseHas('subscription', [
            'payment_method_id' => PaymentMethod::STRIPE,
        ]);
    }

    public function testRouteWebhookSubscriptionChargeSuccessfully()
    {
        $externalId = 'sub_00000000000000';
        $account = $this->generateAccount();
        $account->getProfile()->setState(1);
        $subscription = $this->generateSubscription($this->generatePackage(), $account, PaymentMethod::STRIPE);
        $subscription->setExternalId($externalId);
        $this->em->flush($subscription);
        $this->em->persist($subscription);

        $testEventData = file_get_contents(app_path() . '/Testing/Stripe/invoice.payment_succeeded.json');
        $resp = $this->call('POST', route('stripe.webhook'), [], [], [], [], $testEventData);
        $this->assertTrue($resp->status() === 200);

        $this->assertDatabaseHas('subscription', [
            'external_id' => $externalId,
            'payment_method_id' => PaymentMethod::STRIPE,
            'recurrent_count' => 1,
        ]);
    }


    protected function getFakeStripeSubscription(){
        return array (
            'id' => 'sub_Bwvepu9yvt6NNE',
            'object' => 'subscription',
            'application_fee_percent' => NULL,
            'billing' => 'charge_automatically',
            'cancel_at_period_end' => false,
            'canceled_at' => NULL,
            'created' => 1513277471,
            'current_period_end' => 1513882271,
            'current_period_start' => 1513277471,
            'customer' => 'cus_Bwve8zwdT4ZU7S',
            'days_until_due' => NULL,
            'discount' => NULL,
            'ended_at' => NULL,
            'items' =>
                array (
                    'object' => 'list',
                    'data' =>
                        array (
                            0 =>
                                array (
                                    'id' => 'si_Bwve2tiFdIUyXF',
                                    'object' => 'subscription_item',
                                    'created' => 1513277472,
                                    'metadata' =>
                                        array (
                                        ),
                                    'plan' =>
                                        array (
                                            'id' => 'monthly_stripe',
                                            'object' => 'plan',
                                            'amount' => 1000,
                                            'created' => 1509963648,
                                            'currency' => 'usd',
                                            'interval' => 'month',
                                            'interval_count' => 1,
                                            'livemode' => false,
                                            'metadata' =>
                                                array (
                                                ),
                                            'name' => 'monthly_stripe',
                                            'statement_descriptor' => NULL,
                                            'trial_period_days' => 7,
                                        ),
                                    'quantity' => 1,
                                ),
                        ),
                    'has_more' => false,
                    'total_count' => 1,
                    'url' => '/v1/subscription_items?subscription=sub_Bwvepu9yvt6NNE',
                ),
            'livemode' => false,
            'metadata' =>
                array (
                ),
            'plan' =>
                array (
                    'id' => 'monthly_stripe',
                    'object' => 'plan',
                    'amount' => 1000,
                    'created' => 1509963648,
                    'currency' => 'usd',
                    'interval' => 'month',
                    'interval_count' => 1,
                    'livemode' => false,
                    'metadata' =>
                        array (
                        ),
                    'name' => 'monthly_stripe',
                    'statement_descriptor' => NULL,
                    'trial_period_days' => 7,
                ),
            'quantity' => 1,
            'start' => 1513277471,
            'status' => 'trialing',
            'tax_percent' => NULL,
            'trial_end' => 1513882271,
            'trial_start' => 1513277471,
        );
    }
}
