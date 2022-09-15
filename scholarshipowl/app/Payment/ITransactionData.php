<?php namespace App\Payment;

use App\Entity\PaymentMethod;
use App\Entity\TransactionPaymentType;
use App\Entity\TransactionStatus;

/**
 * Interface IPaymentData
 *
 * Should provide all data required for transaction create.
 */
interface ITransactionData
{
    /**
     * Transaction amount
     *
     * @return float
     */
    public function getAmount(): float;

    /**
     * Payment method
     *
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod;

    /**
     * @return TransactionPaymentType
     */
    public function getPaymentType(): TransactionPaymentType;

    /**
     * Provided transaction id
     *
     * @return string
     */
    public function getProvidedTransactionId(): string;

    /**
     * Bank transaction id
     *
     * @return string
     */
    public function getBankTransactionId(): string;

    /**
     * Transaction status
     *
     * @return int
     */
    public function getTransactionStatusId(): int;

    /**
     * All transaction data
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Transaction creation date.
     *
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime;

    /**
     * Return credit cart type (visa, mastercard, etc.) if known.
     *
     * @return string|null
     */
    public function getCreditCardType();

    /**
     * Return device type when transaction occurred.
     *
     * @return string
     */
    public function getDevice(): string;
}
