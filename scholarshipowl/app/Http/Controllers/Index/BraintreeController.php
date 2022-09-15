<?php namespace App\Http\Controllers\Index;

use App\Entity\Account;
use App\Entity\BraintreeAccount;
use App\Entity\Package;
use App\Entity\PaymentMethod;
use App\Entity\Repository\TransactionRepository;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\Transaction;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\TransactionPaymentType as PaymentType;

use App\Exceptions\Braintree\BraintreePaymentException;
use App\Exceptions\Braintree\SubscriptionBraintreePaymentException;
use App\Exceptions\Braintree\TransactionBraintreePaymentException;
use App\Exceptions\Braintree\WebhookBraintreePaymentException;
use App\Http\Controllers\PaymentController as BasePaymentController;
use App\Payment\Braintree\BraintreeTransactionData;
use App\Payment\Braintree\CustomerRepository;
use App\Payment\Braintree\PaymentMethodRepository;
use App\Payment\PaymentException;

use App\Providers\PaymentServiceProvider;
use ScholarshipOwl\Domain\Log\PaymentMessage as LogPaymentMessage;

use Braintree;
use Braintree\WebhookNotification;

use App\Http\Traits\JsonResponses;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BraintreeController extends BasePaymentController
{
    use JsonResponses;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken()
    {
        return $this->jsonSuccessResponse(['token' => \Braintree\ClientToken::generate()]);
    }

    /**
     * POST Action after success braintree payment
     *
     * @param Account $account
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function postIndexAction(Account $account, Request $request)
    {
        if (!$request->has('payment_method_nonce') || !$request->has('package_id')) {
            return $this->jsonErrorResponse('Oops, something went wrong. Please, refresh the page and try again.');
        }

        if ($account->getActiveSubscriptions()->count() >= 2) {
            $accountId = $account->getAccountId();
            \Log::alert(":SuspiciousActivity: User with id: $accountId trying to buy more than 2 subscriptions. ");
            return $this->jsonErrorResponse('You already have 2 active subscriptions');
        }

        try {
            $deviceData = $request->get('device_data', '');
            /** @var Package $package */
            $package = \EntityManager::findById(Package::class, $request->get('package_id'));

            $braintreePaymentMethod = PaymentMethodRepository::create(
                CustomerRepository::find($account),
                $account->getProfile(),
                $request->get('payment_method_nonce'),
                $deviceData
            );

            if ($package->isRecurrent()) {
                $this->braintreeSubscribe($braintreePaymentMethod->token, $account, $package);
            } else {
                $this->braintreeTransaction($braintreePaymentMethod->token, $account, $package, $deviceData);
            }

        } catch (BraintreePaymentException $e) {
            \Log::error($e);
            return $this->jsonErrorResponse(sprintf(
                '%s.</br>' .
                'Please check the details and try again or choose a different payment method.',
                $e->getGateMessage()->message));
        }

        return $this->paymentPopupResponse($request, $package, $account);
    }

    /**
     * @param null $id
     *
     * @throws Braintree\Exception\InvalidSignature
     */
    public function webhookAction($id = null)
    {
        LogPaymentMessage::log(\Request::all(), PaymentMethod::BRAINTREE);

        if ($id) {
            /** @var BraintreeAccount $braintreeAccount */
            $braintreeAccount = \EntityManager::findById(BraintreeAccount::class, $id);
            PaymentServiceProvider::setBraintreeConfigurations($braintreeAccount);
        }

        $webhook = Braintree\WebhookNotification::parse(\Request::get('bt_signature'), \Request::get('bt_payload'));

        abort_if($webhook->kind === Braintree\WebhookNotification::CHECK, 200, 'Success check');

        if (!$webhook->subscription || !$webhook->subscription->id || empty($webhook->subscription->transactions)) {
            throw new WebhookBraintreePaymentException($webhook);
        }

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = \EntityManager::getRepository(Subscription::class);
        $subscription = $subscriptionRepository->findByExternalId($webhook->subscription->id, PaymentMethod::BRAINTREE);

        switch ($webhook->kind) {
            case WebhookNotification::SUBSCRIPTION_CANCELED:
                break;

            case WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY:
                $transaction = $webhook->subscription->transactions[0];
                $transactionData = $this->getTransactionData($transaction);

                /** @var TransactionRepository $transactionRepository */
                $transactionRepository = \EntityManager::getRepository(Transaction::class);

                if (!$transactionRepository->findByTransactionData($transactionData)) {
                    \PaymentManager::subscriptionPayment($subscription, $transactionData);
                }
                break;

            case WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY:
                break;

            case WebhookNotification::SUBSCRIPTION_EXPIRED:
                break;

            case WebhookNotification::SUBSCRIPTION_TRIAL_ENDED:
                break;

            case WebhookNotification::SUBSCRIPTION_WENT_ACTIVE:
                break;

            case WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE:
                break;

            default:
                break;
        }
    }

    /**
     * @param string $paymentMethodToken
     * @param Account $account
     * @param Package $package
     * @param $deviceData
     * @throws TransactionBraintreePaymentException
     */
    protected function braintreeTransaction(string $paymentMethodToken, Account $account, Package $package, $deviceData)
    {

        /** @var Braintree\Result\Successful $result */
        $result = Braintree\Transaction::sale([
            'paymentMethodToken' => $paymentMethodToken,
            'amount' => $package->getPrice(),
            'options' => [
                'submitForSettlement' => True
            ],
            'deviceData' => $deviceData
        ]);

        if (!$result->success || !$result->transaction) {
            throw new TransactionBraintreePaymentException($result, $account->getAccountId());
        }

        \PaymentManager::applyPackageOnAccount(
            $account,
            $package,
            SubscriptionAcquiredType::PURCHASED,
            $this->getTransactionData($result->transaction),
            PaymentMethod::BRAINTREE
        );
    }

    /**
     * @param string $paymentMethodToken
     * @param Account $account
     * @param Package $package
     * @throws SubscriptionBraintreePaymentException
     */
    protected function braintreeSubscribe(string $paymentMethodToken, Account $account, Package $package)
    {
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $paymentMethodToken,
            'planId' => $package->getBraintreePlan()
        ]);

        if (!$result->success || !$result->subscription) {
            throw new SubscriptionBraintreePaymentException($result, $account->getAccountId());
        }

        \PaymentManager::applyPackageOnAccount(
            $account,
            $package,
            SubscriptionAcquiredType::PURCHASED,
            !empty($result->subscription->transactions) ?
                $this->getTransactionData($result->subscription->transactions[0]) : null,
            PaymentMethod::BRAINTREE,
            $result->subscription->id
        );
    }

    /**
     * @param Braintree\Transaction $transaction
     *
     * @return BraintreeTransactionData
     */
    protected function getTransactionData(Braintree\Transaction $transaction)
    {
        $creditCardDetails = is_object($transaction->creditCardDetails) ? $transaction->creditCardDetails : false;
        return new BraintreeTransactionData(
            \Request::all(),
            floatval($transaction->amount),
            BraintreeTransactionData::getBraintreeAccount()->getId().'-'.$transaction->id,
            BraintreeTransactionData::getBraintreeAccount()->getId().'-'.$transaction->id,
            $this->isMobile() ? Transaction::DEVICE_MOBILE : Transaction::DEVICE_DESKTOP,
            $creditCardDetails && $creditCardDetails->cardType ? PaymentType::CREDIT_CARD : PaymentType::PAYPAL,
            $creditCardDetails && $creditCardDetails->cardType ? $creditCardDetails->cardType : null
        );
    }
}
