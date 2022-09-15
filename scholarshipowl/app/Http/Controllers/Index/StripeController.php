<?php namespace App\Http\Controllers\Index;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\PaymentMethod;
use App\Entity\Repository\EntityRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Repository\TransactionRepository;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\Transaction;
use App\Entity\TransactionPaymentType;
use App\Http\Controllers\PaymentController as BasePaymentController;
use App\Payment\Stripe\StripeTransaction;
use App\Services\PaymentManager;
use App\Services\StripeService;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeController extends BasePaymentController
{
    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PaymentManager
     */
    protected $pm;

    /**
     * @var EntityRepository
     */
    protected $packages;

    /**
     * @var TransactionRepository
     */
    protected $transactions;

    /**
     * @var SubscriptionRepository
     */
    protected $subscriptions;

    /**
     * PaymentController constructor.
     *
     * @param EntityManager  $em
     * @param PaymentManager $pm
     * @param StripeService  $stripe
     */
    public function __construct(EntityManager $em, PaymentManager $pm, StripeService $stripe)
    {
        parent::__construct();

        $this->em = $em;
        $this->pm = $pm;
        $this->stripeService = $stripe;
        $this->packages = $em->getRepository(Package::class);
        $this->transactions = $em->getRepository(Transaction::class);
        $this->subscriptions = $em->getRepository(Subscription::class);
    }

    /**
     * @param Request $request
     * @param Account $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyPackage(Request $request, Account $account)
    {
        $this->validate($request, [
            'stripe_token'   => 'required',
            'package_id'      => 'required|exists:App\Entity\Package,packageId',
        ]);

        try {
            $stripeToken = $request->get('stripe_token');
            /** @var Package $package */
            $package = $this->packages->findById($request->get('package_id'));
            $externalId = null;

            if ($package->isRecurrent()) {
                $subscribe = $this->stripeService->subscribe($account, $package, $stripeToken);
                if(empty($subscribe)){
                    throw new \Exception('Empty Stripe subscribe');
                }

                $this->pm->applyPackageOnAccount(
                    $account,
                    $package,
                    SubscriptionAcquiredType::PURCHASED,
                    null,
                    PaymentMethod::STRIPE,
                    $subscribe['id']
                );

            } else {
                $charge = $this->stripeService->charge($account, $package, $stripeToken);
                $transactionData = new StripeTransaction([],
                    $charge['amount']/ 100,
                    $charge['id'],
                    $charge['id'],
                    $this->isMobile() ? Transaction::DEVICE_MOBILE : Transaction::DEVICE_DESKTOP,
                    TransactionPaymentType::CREDIT_CARD
                );

                $this->pm->applyPackageOnAccount($account, $package,
                    SubscriptionAcquiredType::PURCHASED,
                    $transactionData ?? null,
                    PaymentMethod::STRIPE,
                    null
                );
            }
        } catch (\Exception $e) {
            \Log::error($e);

            return $this->jsonErrorResponse(sprintf($this->getErrorMessage($e)));
        }

        return $this->paymentPopupResponse($request, $package, $account);
    }

    /**
     * Webhook processing
     */
    public function webhook(Request $request)
    {
        if(!is_testing()) {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('stripe-signature'),
                $this->stripeService->getEndpointSecret()
            );
        } else {
            $event = json_decode($request->getContent(), true);
        }

        switch ($event['type']) {
            case 'invoice.payment_succeeded':
                if ($event['data']['object']['amount_due'] > 0) {
                    $transaction = new StripeTransaction([],
                        $event['data']['object']['amount_due'] / 100,
                        $event['data']['object']['id'],
                        $event['data']['object']['id'],
                        $this->isMobile() ? Transaction::DEVICE_MOBILE : Transaction::DEVICE_DESKTOP,
                        TransactionPaymentType::CREDIT_CARD
                    );
                    $subscriptionId = $event['data']['object']['lines']['data'][0]['id'];
                    if (!$this->transactions->findByTransactionData($transaction)) {
                        $subscription = $this->subscriptions->findByExternalId($subscriptionId, PaymentMethod::STRIPE);
                        $this->pm->subscriptionPayment($subscription, $transaction);
                    }
                }
                break;
            default:
                break;
        }
    }

    /**
     * Return correct string error message from error_code
     * @param $stripeError
     *
     * @return string
     */
    protected function getErrorMessage($stripeError)
    {
        $defaultErrorMessage
            = "Your transaction could not be completed.</br>Please check the details and try again or choose a different payment method.";

        $stripeErrorCode = $stripeError->getErrorCode();

        if($stripeErrorCode == "do_not_honor" || $stripeErrorCode == "service_not_allowed"){
            $defaultErrorMessage = "Your card was declined. Please, contact your bank or try a different payment method";
        }elseif($stripeErrorCode == "transaction_not_allowed"){
            $defaultErrorMessage = "Your card does not support this type of transaction. Please, try a different card or a different payment method";
        }else{
            $defaultErrorMessage = $stripeError->getMessage();
        }

        return $defaultErrorMessage;
    }
}
