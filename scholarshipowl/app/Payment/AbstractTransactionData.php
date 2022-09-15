<?php namespace App\Payment;

use App\Entity\PaymentMethod;
use App\Entity\TransactionPaymentType;
use App\Entity\TransactionStatus;

abstract class AbstractTransactionData implements ITransactionData
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var TransactionPaymentType
     */
    protected $paymentType;

    /**
     * @var int
     */
    protected $transactionStatusId;

    /**
     * @var string
     */
    protected $bankTransactionId;

    /**
     * @var string
     */
    protected $providedTransactionId;

    /**
     * @var \DateTime
     */
    protected $createdDate;

    /**
     * @var string|null
     */
    protected $creditCardType;

    /**
     * @var string|null
     */
    protected $device;

    /**
     * @return PaymentMethod
     */
    abstract public function getPaymentMethod(): PaymentMethod;

    /**
     * AbstractTransactionData constructor.
     *
     * @param array          $data
     * @param float          $amount
     * @param string         $bankTransactionId
     * @param string         $providedTransactionId
     * @param string         $device
     * @param int            $paymentType
     * @param string|null    $creditCardType
     * @param int            $transactionStatus
     * @param \DateTime|null $createdData
     */
    public function __construct(
        array     $data,
        float     $amount,
        string    $bankTransactionId,
        string    $providedTransactionId,
        string    $device,
        int       $paymentType,
        string    $creditCardType = null,
        int       $transactionStatus = TransactionStatus::SUCCESS,
        \DateTime $createdData = null
    ) {
        $this->setData($data);
        $this->setAmount($amount);
        $this->setDevice($device);
        $this->setPaymentType($paymentType);
        $this->setCreditCardType($creditCardType);
        $this->setTransactionStatusId($transactionStatus);
        $this->setProvidedTransactionId($providedTransactionId);
        $this->setBankTransactionId($bankTransactionId);
        $this->setCreatedDate($createdData ?: new \DateTime());
    }

    /**
     * @param int|TransactionPaymentType  $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = TransactionPaymentType::convert($paymentType);
    }

    /**
     * @return TransactionPaymentType
     */
    public function getPaymentType(): TransactionPaymentType
    {
        return $this->paymentType;
    }

    /**
     * @param int $transactionStatusId
     *
     * @return $this
     */
    public function setTransactionStatusId($transactionStatusId)
    {
        $this->transactionStatusId = $transactionStatusId;

        return $this;
    }

    /**
     * @return int
     */
    public function getTransactionStatusId(): int
    {
        return $this->transactionStatusId;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param string $bankTransactionId
     *
     * @return $this
     */
    public function setBankTransactionId($bankTransactionId)
    {
        $this->bankTransactionId = $bankTransactionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBankTransactionId(): string
    {
        return $this->bankTransactionId;
    }

    /**
     * @param $providedTransactionId
     *
     * @return $this
     */
    public function setProvidedTransactionId($providedTransactionId)
    {
        $this->providedTransactionId = $providedTransactionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvidedTransactionId(): string
    {
        return $this->providedTransactionId;
    }

    /**
     * @param string $creditCardType
     *
     * @return $this
     */
    public function setCreditCardType($creditCardType)
    {
        $this->creditCardType = $creditCardType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCreditCardType()
    {
        return $this->creditCardType;
    }

    /**
     * @param $createdDate
     *
     * @return $this
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param string $device
     *
     * @return $this
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }
}
