<?php

namespace ScholarshipOwl\Domain\Payment;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Payment\Package;

use ScholarshipOwl\Domain\Subscription;
use ScholarshipOwl\Domain\Transaction;

/**
 * Interface IMessage
 * @package ScholarshipOwl\Domain\Payment
 */
interface IMessage
{

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @return array
     */
    public function getAll();

    /**
     * @return mixed
     */
    public function getAmount();

    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @return Package
     */
    public function getPackage();

    /**
     * Return internal subscription if some external id provided.
     *
     * @return Subscription
     */
    public function getSubscription();

    /**
     * @return Account
     */
    public function getAccount();

    /**
     * Lazy loading of transaction entity.
     *
     * @return null|Transaction
     */
    public function getTransaction();

    /**
     * @return mixed
     */
    public function getPaymentMethod();

    /**
     * @return int
     */
    public function getPaymentType();

    /**
     * @return mixed
     */
    public function getSource();

    /**
     * @return mixed
     */
    public function getProvidedTransactionId();

    /**
     * @return mixed
     */
    public function getBankTransactionId();

    /**
     * @return mixed
     */
    public function isMobile();

    /**
     * @return array
     */
    public function getTrackingParams();

    /**
     * @return null|string
     */
    public function getExternalSubscriptionId();

    /**
     * @return string
     */
    public function getCreditCardType();

    /**
     * @return int
     */
    public function getTransactionStatusId();

}
