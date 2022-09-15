<?php namespace App\Payment\PayPal;

use App\Entity\PaymentMethod;
use App\Entity\Transaction;
use App\Entity\TransactionPaymentType;
use App\Payment\AbstractTransactionData;
use ScholarshipOwl\Domain\Payment\PayPal\Message;

class PayPalTransactionData extends AbstractTransactionData
{
    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod
    {
        return PaymentMethod::find(PaymentMethod::PAYPAL);
    }

    /**
     * PayPalTransactionData constructor.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        parent::__construct(
            $message->getAll(),
            $message->getAmount(),
            $message->getBankTransactionId(),
            $message->getProvidedTransactionId(),
            $message->isMobile() ? Transaction::DEVICE_MOBILE : Transaction::DEVICE_DESKTOP,
            TransactionPaymentType::PAYPAL,
            null,
            $message->getTransactionStatusId(),
            $message->getDate()
        );
    }
}
