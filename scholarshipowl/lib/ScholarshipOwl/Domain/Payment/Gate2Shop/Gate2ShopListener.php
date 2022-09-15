<?php

namespace ScholarshipOwl\Domain\Payment\Gate2Shop;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Payment\Gate2Shop\Gate2ShopTransactionData;

use Illuminate\Queue\Jobs\Job;
use ScholarshipOwl\Domain\Payment\Event\PaymentRemoteEvent;
use ScholarshipOwl\Domain\Payment\Gate2Shop\Rebilling\InitialMessage;
use ScholarshipOwl\Domain\Payment\Gate2Shop\Rebilling\RecurrentMessage;
use ScholarshipOwl\Domain\Payment\IMessage;
use ScholarshipOwl\Domain\Payment\FailedPaymentException;

class Gate2ShopListener implements PaymentRemoteEvent
{

    /**
     * @param Message $message
     * @param Job     $job
     *
     * @throws FailedPaymentException
     */
    public function fireEventFromMessage(IMessage $message, Job $job = null)
    {
        if ($message instanceof RecurrentMessage) {
            if ($message->isSuccess()) {
                if ($message->getSubscription()) {
                    \PaymentManager::subscriptionPayment(
                        $this->getSubscription($message),
                        new Gate2ShopTransactionData($message)
                    );
                }
            } else {
                \PaymentManager::subscriptionPaymentFailed($this->getSubscription($message));
            }

        } elseif ($message instanceof InitialMessage) {

            if ($message->isSuccess()) {
                if (!$message->getSubscription()) {
                    \PaymentManager::applyPackageOnAccount(
                        $this->getAccount($message),
                        $this->getPackage($message),
                        SubscriptionAcquiredType::PURCHASED,
                        new Gate2ShopTransactionData($message),
                        $message->getPaymentMethod(),
                        $message->getExternalSubscriptionId()
                    );
                }
            } else {
                throw new FailedPaymentException("Failed create recurrent payments profile.");
            }

        } else {

            if ($message->isSuccess()) {
                \PaymentManager::applyPackageOnAccount(
                    $this->getAccount($message),
                    $this->getPackage($message),
                    SubscriptionAcquiredType::PURCHASED,
                    new Gate2ShopTransactionData($message)
                );
            } else {
                throw new FailedPaymentException("Failed create simple payment subscription.");
            }

        }
    }

    /**
     * @param IMessage $message
     *
     * @return Subscription
     */
    protected function getSubscription(IMessage $message)
    {
        return \EntityManager::findById(Subscription::class, $message->getSubscription()->getSubscriptionId());
    }

    /**
     * @param IMessage $message
     *
     * @return Account
     * @throws \Exception
     */
    protected function getAccount(IMessage $message)
    {
        return \EntityManager::findById(Account::class, $message->getAccount()->getAccountId());
    }

    /**
     * @param IMessage $message
     *
     * @return Package
     * @throws \Exception
     */
    protected function getPackage(IMessage $message)
    {
        return \EntityManager::findById(Package::class, $message->getPackage()->getPackageId());
    }
}
