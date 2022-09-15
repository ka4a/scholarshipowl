<?php

namespace App\Console\Commands;

use App\Entity\AddressUpdatedSubscriptions;
use App\Entity\PaymentMethod;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Subscription;
use App\Jobs\BraintreeTransactionAddressUpdating;
use App\Jobs\StripeTransactionAddressUpdating;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;


class BillingAddressUpdating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:update-address 
                            {payment_system : The name of payment system. Can be stripe or braintree}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Submitting billing data from user's profile to old transaction";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SubscriptionRepository
     */
    protected $subscriptionRepository;

    protected $dates = [];

    protected $availablePaymentSystem = [
        'stripe',
        'braintree'
    ];

    protected $paymentSystemSettings = [
        'stripe' => [
            'paymentMethod' =>  PaymentMethod::STRIPE,
        ],
        'braintree' => [
            'paymentMethod' =>  PaymentMethod::BRAINTREE,
        ]
    ];


    /**
     * BillingAddressUpdating constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->subscriptionRepository = $em->getRepository(Subscription::class);

        $this->dates['monday'] = date( 'Y-m-d ', strtotime( 'monday this week' ))."00:00:01";
        $this->dates['sunday'] = date( 'Y-m-d ', strtotime( 'sunday this week' ))."23:59:59";

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $paymentSystem = $this->argument('payment_system');

        if(!in_array($paymentSystem, $this->availablePaymentSystem)){
            $this->error('Filled incorrect payment system! Can be: '. implode(', ',$this->availablePaymentSystem));
            return false;
        }

        $paymentSystemSettings = $this->paymentSystemSettings[$paymentSystem];
        $paymentMethod = $paymentSystemSettings['paymentMethod'];

        $skipped = 0;
        $logRepository = $this->em->getRepository(AddressUpdatedSubscriptions::class);
        $this->info(
            sprintf(
                '[%s] Started updating payment address', date('c')
            )
        );

        $subscriptionList = $this->subscriptionRepository->getActiveBuscriptionByPaymentMethod(
            $paymentMethod,
            $this->dates['monday'],
            $this->dates['sunday']
        );

        $subscriptionCount = count($subscriptionList);
        $this->info(
            sprintf(
                '[%s] Subscriptions count %s', date('c'),
                $subscriptionCount
            )
        );
        $bar = $this->output->createProgressBar($subscriptionCount);
        /**
         * @var Subscription $subscription
         */
        foreach ($subscriptionList as $subscription) {
            $log = $logRepository->findOneBy(['subscriptionId' => $subscription->getSubscriptionId()]);

            if(empty($log)){

                $subscription->getAccount()->__load();
                $subscription->getAccount()->getProfile()->__load();
                $paymentMethod == PaymentMethod::STRIPE ? StripeTransactionAddressUpdating::dispatch($subscription) : BraintreeTransactionAddressUpdating::dispatch($subscription);

                $log = new AddressUpdatedSubscriptions($subscription->getSubscriptionId(),
                    $paymentMethod,
                    Carbon::now()
                );
                $this->em->persist($log);
            }  else {
                $skipped ++;
            }
            $bar->advance();
        }
        $bar->finish();
        $this->em->flush();


        $this->info(
            sprintf(
                 "\n[%s] Skipped %s subscriptions.", date('c'), $skipped
            )
        );
        $this->info(
            sprintf(
                '[%s] Finished updating payment address', date('c')
            )
        );
    }

}

