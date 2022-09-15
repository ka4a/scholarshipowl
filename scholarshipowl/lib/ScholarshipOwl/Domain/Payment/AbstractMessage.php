<?php

namespace ScholarshipOwl\Domain\Payment;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Payment\Package;

use ScholarshipOwl\Data\Entity\Payment\TransactionStatus;
use ScholarshipOwl\Domain\Repository\SubscriptionRepository;
use ScholarshipOwl\Domain\Repository\TransactionRepository;
use ScholarshipOwl\Domain\Subscription;
use ScholarshipOwl\Domain\Transaction;

/**
 * Class AbstractMessage
 * @package ScholarshipOwl\Domain\Payment
 */
abstract class AbstractMessage implements IMessage
{

    /**
     * @var null
     */
    protected $inputData = array();

    /**
     * @var mixed
     */
    protected $amount = null;

    /**
     * @var Package
     */
    protected $package = null;

    /**
     * @var Subscription
     */
    protected $subscription = null;

    /**
     * @var Account
     */
    protected $account = null;

    /**
     * @var Transaction
     */
    protected $transaction = null;

    /**
     * @var bool
     */
    protected $isMobile = false;

    /**
     * @var array
     */
    protected $trackingParams = null;

    /**
     * @var int
     */
    protected $transactionStatusId = TransactionStatus::SUCCESS;

    /**
     * @var string
     */
    protected $source = null;

    /**
     * @param array $data
     * @param bool $isMobile
     */
    public function __construct(array $data, $isMobile = false)
    {
        $this->setData($data);
        $this->setIsMobile($isMobile);
    }

    /**
     * @param mixed $key
     * @param null $value
     */
    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            $this->inputData = $key;
        } else {
            $this->inputData[$key] = $value;
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key = null)
    {
        return array_key_exists($key, $this->inputData) ? $this->inputData[$key] : null;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->inputData;
    }

    /**
     * @param $amount
     * @return void
     */
    public function setAmount($amount)
    {
        $this->amount = floatval(empty($amount) ? 0 : $amount);
    }

    /**
     * @param Package $package
     * @return mixed
     */
    public function setPackage(Package $package)
    {
        $this->package = $package;
    }

    /**
     * @param Subscription $subscription
     * @return mixed
     */
    public function setSubscription(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return null|Subscription
     */
    public function getSubscription()
    {
        if ($this->subscription === null) {

            $subscriptionRepository = new SubscriptionRepository();

            if ($externalSubscriptionId = $this->getExternalSubscriptionId()) {
                $this->subscription = $subscriptionRepository
                    ->findByExternalId($externalSubscriptionId, $this->getPaymentMethod());
            } elseif ($transaction = $this->getTransaction()) {
                $this->subscription = $subscriptionRepository
                    ->findById($transaction->getSubscription()->getSubscriptionId());
            }

        }

        return $this->subscription;
    }

    /**
     * @return null|Transaction
     */
    public function getTransaction()
    {
        if ($this->transaction === null) {

            $bankTransactionId = $this->getBankTransactionId();
            $providerTransactionId = $this->getProvidedTransactionId();
            $transactionRepository = new TransactionRepository();

            if ($transaction = $transactionRepository->findByBankTransactionId($bankTransactionId)) {
                $this->setTransaction($transaction);
            } elseif ($transaction = $transactionRepository->findByProviderTransactionId($providerTransactionId)) {
                $this->setTransaction($transaction);
            }

        }

        return $this->transaction;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @param Account $account
     * @return mixed
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return mixed
     */
    public function isMobile()
    {
        return $this->isMobile;
    }

    /**
     * @param bool $isMobile
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = (bool) $isMobile;
    }

    /**
     * @param array $params
     */
    public function setTrackingParams(array $params)
    {
        $this->trackingParams = $params;
    }

    /**
     * @return string
     */
    public function getCreditCardType()
    {
        return '';
    }

    /**
     * @return int
     */
    public function getTransactionStatusId()
    {
        return $this->transactionStatusId;
    }

    /**
     * @param int $transactionStatusId
     */
    public function setTransactionStatusId($transactionStatusId)
    {
        $this->transactionStatusId = $transactionStatusId;
    }

}