<?php namespace App\Providers;

use App\Entity\BraintreeAccount;
use App\Entity\Repository\BraintreeAccountRepository;
use App\Payment\Braintree\BraintreeTransactionData;
use App\Payment\PaymentFactory;
use App\Payment\RemotePaymentManager;
use App\Services\PaymentManager;
use App\Services\RecurlyService;
use App\Services\StripeService;
use Doctrine\ORM\EntityManager;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

use Braintree\Configuration;

class PaymentServiceProvider extends ServiceProvider
{

    /**
     * @param BraintreeAccount $braintreeAccount
     */
    public static function setBraintreeConfigurations(BraintreeAccount $braintreeAccount)
    {
        Configuration::merchantId($braintreeAccount->getMerchantId());
        Configuration::publicKey($braintreeAccount->getPublicKey());
        Configuration::privateKey($braintreeAccount->getPrivateKey());

        if (!is_production()) {
            Configuration::merchantId(config('services.braintree.merchant_id'));
            Configuration::publicKey(config('services.braintree.public_key'));
            Configuration::privateKey(config('services.braintree.private_key'));
        }

        BraintreeTransactionData::setBraintreeAccount($braintreeAccount);
    }

    public function register()
    {
        $this->registerPaymentManager();
        $this->registerBraintree();
    }

    public function boot()
    {
        $this->bootBraintree();
    }

    protected function registerPaymentManager()
    {
        $this->app->alias(PaymentFactory::class, 'payment.factory');
        $this->app->singleton(PaymentFactory::class, function() {
            return new PaymentFactory();
        });

        $this->app->alias(RemotePaymentManager::class, 'payment.remote_manager');
        $this->app->singleton(RemotePaymentManager::class, function(Application $app) {
            return new RemotePaymentManager(
                $app->make('em'),
                $app->make('payment.factory'),
                $app->make('zendesk')
            );
        });

        $this->app->alias(RecurlyService::class, 'payment.recurly');
        $this->app->singleton(RecurlyService::class, function() {
            return new RecurlyService();
        });
        $this->app->singleton(StripeService::class, function() {
            return new StripeService();
        });

        $this->app->alias(PaymentManager::class, 'payment.manager');
        $this->app->singleton(PaymentManager::class, function(Container $app) {
            return new PaymentManager($app->make('em'), $app->make('events'), $app->make('payment.remote_manager'));
        });
    }

    protected function registerBraintree()
    {
        Configuration::environment(env('BRAINTREE_ENV', is_production() ? 'production' : 'sandbox'));
    }

    protected function bootBraintree()
    {
        /** @var EntityManager $em */
        $em = $this->app->make(EntityManager::class);

        /** @var BraintreeAccountRepository $repository */
        $repository = $em->getRepository(BraintreeAccount::class);

        if ($braintreeAccount = $repository->getDefault()) {
            static::setBraintreeConfigurations($braintreeAccount);
        }
    }
}
