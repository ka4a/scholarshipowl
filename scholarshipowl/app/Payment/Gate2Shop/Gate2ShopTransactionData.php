<?php namespace App\Payment\Gate2Shop;

use App\Entity\PaymentMethod;
use App\Entity\Transaction;
use App\Entity\TransactionPaymentType;
use App\Payment\AbstractTransactionData;
use ScholarshipOwl\Domain\Payment\Gate2Shop\Message;

class Gate2ShopTransactionData extends AbstractTransactionData
{

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod
    {
        return PaymentMethod::find(PaymentMethod::CREDIT_CARD);
    }

    /**
     * Gate2ShopTransactionData constructor.
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
            TransactionPaymentType::CREDIT_CARD,
            $message->getCreditCardType(),
            $message->getTransactionStatusId(),
            $message->getDate()
        );
    }

}
