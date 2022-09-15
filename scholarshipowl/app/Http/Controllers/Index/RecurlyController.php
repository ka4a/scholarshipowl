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
use App\Payment\Recurly\TransactionData;
use App\Services\PaymentManager;
use App\Services\RecurlyService;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RecurlyController extends BasePaymentController
{
    /**
     * @var RecurlyService
     */
    protected $recurly;

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
     * @param RecurlyService $stripe
     */
    public function __construct(EntityManager $em, PaymentManager $pm, RecurlyService $stripe)
    {
        parent::__construct();

        $this->em = $em;
        $this->pm = $pm;
        $this->recurly = $stripe;
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
            'recurly_token'   => 'required',
            'package_id'      => 'required|exists:App\Entity\Package,packageId',
        ]);

        try {

            /** @var Package $package */
            $package = $this->packages->findById($request->get('package_id'));
            $externalId = null;

            if ($package->isRecurrent()) {
                $subscription = $this->recurly->subscribe($request->get('recurly_token'), $account, $package);
                $externalId = $subscription->uuid;

                /**
                 * Retrieve transaction data
                 */
                if ($subscription->trial_ends_at === null) {
                    /** @var \Recurly_Invoice $invoice */
                    $invoice = $subscription->invoice->get();
                    if (!empty($invoice->transactions)) {
                        $transaction = $invoice->transactions->current();
                        $transactionData = new TransactionData([],
                            $invoice->total_in_cents / 100,
                            $invoice->uuid,
                            $invoice->uuid,
                            $this->isMobile() ? Transaction::DEVICE_MOBILE : Transaction::DEVICE_DESKTOP,
                            $transaction && $transaction->payment_method == 'credit_card' ?
                                TransactionPaymentType::CREDIT_CARD : TransactionPaymentType::PAYPAL
                        );
                    }
                }
            } else {
                $charge = $this->recurly->charge($request->get('recurly_token'), $account, $package);

                $transactionData = new TransactionData([],
                    $charge->unit_amount_in_cents / 100,
                    $charge->uuid,
                    $charge->uuid,
                    $this->isMobile() ? Transaction::DEVICE_MOBILE : Transaction::DEVICE_DESKTOP,
                    TransactionPaymentType::CREDIT_CARD
                );
            }

            $this->pm->applyPackageOnAccount($account, $package,
                SubscriptionAcquiredType::PURCHASED,
                $transactionData ?? null,
                \App\Entity\PaymentMethod::RECURLY,
                $externalId
            );

        } catch (\Exception $e) {
            \Log::error($e);

            return $this->jsonErrorResponse(sprintf(
                'Your transaction could not be completed.</br>' .
                'Please check the details and try again or choose a different payment method.'
            ));
        }

        return $this->paymentPopupResponse($request, $package, $account);
    }

    /**
     * Webhook processing
     *
     * @param Request $request
     */
    public function webhook(Request $request)
    {
        $allowIps = [
            '50.18.192.88',
            '52.8.32.100',
            '52.9.209.233',
            '50.0.172.150',
            '52.203.102.94',
            '52.203.192.184',
        ];

        if (is_production() && !in_array($request->getClientIp(), $allowIps)) {
            throw new AccessDeniedHttpException();
        }

        $notification = new \Recurly_PushNotification(file_get_contents ("php://input"));

        switch ($notification->type) {
            case 'successful_payment_notification':
                /** @var \Recurly_Transaction $transaction */
                $transaction = $notification->transaction;

                /** @var \Recurly_Invoice $invoice */
                // $invoice = $transaction->invoice;

                $transactionData = new TransactionData([],
                    $transaction->amount_in_cents / 100,
                    $transaction->invoice_id,
                    $transaction->invoice_id,
                    $this->isMobile() ? Transaction::DEVICE_MOBILE : Transaction::DEVICE_DESKTOP,
                    $transaction && $transaction->payment_method == 'credit_card' ?
                        TransactionPaymentType::CREDIT_CARD : TransactionPaymentType::PAYPAL
                );

                if (!$this->transactions->findByTransactionData($transactionData)) {
                    $subscription = $this->subscriptions
                        ->findByExternalId($transaction->subscription_id, PaymentMethod::RECURLY);

                    $this->pm->subscriptionPayment($subscription, $transactionData);
                }

                break;
            case 'new_subscription_notification':
                break;
            case 'updated_subscription_notification':
                break;
            case 'canceled_subscription_notification':
                break;
            case 'expired_subscription_notification':
                break;
            case 'renewed_subscription_notification':
                break;
            default:
                break;
        }
    }
}
