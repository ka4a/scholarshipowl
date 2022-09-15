<?php

namespace ScholarshipOwl\Domain\Payment\PayPal;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Payment\Exception\PaymentBeforeSubscriptionException;
use App\Payment\PayPal\PayPalTransactionData;

use Illuminate\Queue\Jobs\Job;
use ScholarshipOwl\Domain\Payment\Event\PaymentRemoteEvent;
use ScholarshipOwl\Domain\Payment\IMessage;
use ScholarshipOwl\Domain\Repository\TransactionRepository;

class PayPalListener implements PaymentRemoteEvent
{

    /**
     * Fire event depends on received message.
     *
     * @param IMessage $message
     * @param Job|null $job
     *
     * @return $this
     * @throws PaymentBeforeSubscriptionException
     */
    public function fireEventFromMessage(IMessage $message, Job $job = null)
    {
        if ($paypalSubscriptionId = $message->getExternalSubscriptionId()) {

            switch ($message->getTransactionType()) {

                case "subscr_signup": // This Instant Payment Notification is for a subscription sign -up.
                    if (!$message->getSubscription()) {
                        \PaymentManager::applyPackageOnAccount(
                            $this->getAccount($message),
                            $this->getPackage($message),
                            SubscriptionAcquiredType::PURCHASED,
                            null,
                            $message->getPaymentMethod(),
                            $message->getExternalSubscriptionId()
                        );
                    }

                    break;

                case "subscr_payment"://  This Instant Payment Notification is for a subscription payment.
                    if ($message->getSubscription()) {
                        \PaymentManager::subscriptionPayment(
                            $this->getSubscription($message),
                            new PayPalTransactionData($message)
                        );
                    } elseif ($job) {
                        $job->release(10);
                    } else {
                        throw new PaymentBeforeSubscriptionException(
                            sprintf("Payment come before subscription: %s", $message->getExternalSubscriptionId())
                        );
                    }
                    break;

                case "subscr_failed": //  This Instant Payment Notification is for a subscription payment failure.
                    \PaymentManager::subscriptionPaymentFailed($this->getSubscription($message));
                    break;
                case "subscr_cancel": //  This Instant Payment Notification is for a subscription cancellation.
                case "subscr_eot":    //  This Instant Payment Notification is for a subscription's end of term.
                    break;

                case "subscr_modify": //  This Instant Payment Notification is for a subscription modification.
                    break;
                default:
                    break;
            }

        } else {

            switch ($message->getTransactionType()) {
                case "web_accept":
                    if ($message->get('payment_type') == 'instant') {
                        switch($message->get('payment_status')){
                            case 'Completed':
                                \PaymentManager::applyPackageOnAccount(
                                    $this->getAccount(),
                                    $this->getPackage(),
                                    SubscriptionAcquiredType::PURCHASED,
                                    new PayPalTransactionData($message)
                                );
                                break;

                            case 'Refunded':
                                break;

                            default:
                                break;
                        }
                    }
                    break;

                default:
                    break;
            }

        }

        return $this;
    }

    /**
     * @param Message $message
     *
     * @return Subscription
     */
    protected function getSubscription(Message $message)
    {
        return \EntityManager::findById(Subscription::class, $message->getSubscription()->getSubscriptionId());
    }

    /**
     * @param Message $message
     *
     * @return Account
     * @throws \Exception
     */
    protected function getAccount(Message $message)
    {
        return \EntityManager::findById(Account::class, $message->getAccount()->getAccountId());
    }

    /**
     * @param Message $message
     *
     * @return Package
     * @throws \Exception
     */
    protected function getPackage(Message $message)
    {
        return \EntityManager::findById(Package::class, $message->getPackage()->getPackageId());
    }
}
