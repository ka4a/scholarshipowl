<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Annotations\Restricted;
/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\TransactionRepository")
 */
class Transaction
{
    const DEVICE_DESKTOP = "desktop";
    const DEVICE_MOBILE = "mobile";

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $transactionId;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2, nullable=false, unique=false)
     * @Restricted()
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="response_data", type="text", length=16777215, precision=0, scale=0, nullable=true, unique=false)
     * @Restricted()
     */
    private $responseData;

    /**
     * @var string
     *
     * @ORM\Column(name="provider_transaction_id", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     * @Restricted()
     */
    private $providerTransactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_transaction_id", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     * @Restricted()
     */
    private $bankTransactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="credit_card_type", type="string", length=31, precision=0, scale=0, nullable=true, unique=false)
     * @Restricted()
     */
    private $creditCardType;

    /**
     * @var string
     *
     * @ORM\Column(name="failed_reason", type="string", length=1023, precision=0, scale=0, nullable=true, unique=false)
     */
    private $failedReason;

    /**
     * @var string
     *
     * @ORM\Column(name="device", type="string", precision=0, scale=0, nullable=false, unique=false)
     * @Restricted()
     */
    private $device;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="recurrent_number", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $recurrentNumber;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method_id", referencedColumnName="payment_method_id", nullable=true)
     * })
     */
    private $paymentMethod;

    /**
     * @var TransactionPaymentType
     *
     * @ORM\OneToOne(targetEntity="App\Entity\TransactionPaymentType")
     * @ORM\JoinColumn(name="payment_type_id", referencedColumnName="transaction_payment_type_id", nullable=false)
     */
    private $paymentType;

    /**
     * @var Subscription
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Subscription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription_id", referencedColumnName="subscription_id", nullable=true)
     * })
     */
    private $subscription;

    /**
     * @var TransactionStatus
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TransactionStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transaction_status_id", referencedColumnName="transaction_status_id", nullable=true)
     * })
     */
    private $transactionStatus;

    /**
     * Transaction constructor.
     *
     * @param Subscription       $subscription
     * @param int|PaymentMethod  $paymentMethod
     * @param                    $paymentType
     * @param float              $amount
     * @param array              $data
     * @param string             $device
     * @param \DateTime|null     $createdDate
     * @param int                $transactionStatus
     */
    public function __construct(
        Subscription      $subscription,
                          $paymentMethod,
                          $paymentType,
        float             $amount,
        array             $data,
        string            $device = Transaction::DEVICE_DESKTOP,
        \DateTime         $createdDate = null,
                          $transactionStatus = TransactionStatus::SUCCESS
    ) {
        $this->setSubscription($subscription);
        $this->setAccount($subscription->getAccount());
        $this->setPaymentMethod($paymentMethod);
        $this->setPaymentType($paymentType);
        $this->setAmount($amount);
        $this->setDevice($device);
        $this->setTransactionStatus($transactionStatus);
        $this->setResponseData(json_encode($data));
        $this->setCreatedDate($createdDate ?: new \DateTime());
    }

    /**
     * Get transactionId
     *
     * @return integer
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set responseData
     *
     * @param string $responseData
     *
     * @return Transaction
     */
    public function setResponseData($responseData)
    {
        $this->responseData = $responseData;

        return $this;
    }

    /**
     * Get responseData
     *
     * @return string
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * Set providerTransactionId
     *
     * @param string $providerTransactionId
     *
     * @return Transaction
     */
    public function setProviderTransactionId($providerTransactionId)
    {
        $this->providerTransactionId = $providerTransactionId;

        return $this;
    }

    /**
     * Get providerTransactionId
     *
     * @return string
     */
    public function getProviderTransactionId()
    {
        return $this->providerTransactionId;
    }

    /**
     * Set bankTransactionId
     *
     * @param string $bankTransactionId
     *
     * @return Transaction
     */
    public function setBankTransactionId($bankTransactionId)
    {
        $this->bankTransactionId = $bankTransactionId;

        return $this;
    }

    /**
     * Get bankTransactionId
     *
     * @return string
     */
    public function getBankTransactionId()
    {
        return $this->bankTransactionId;
    }

    /**
     * Set creditCardType
     *
     * @param string $creditCardType
     *
     * @return Transaction
     */
    public function setCreditCardType($creditCardType)
    {
        $this->creditCardType = $creditCardType;

        return $this;
    }

    /**
     * Get creditCardType
     *
     * @return string
     */
    public function getCreditCardType()
    {
        return $this->creditCardType;
    }

    /**
     * Set failedReason
     *
     * @param string $failedReason
     *
     * @return Transaction
     */
    public function setFailedReason($failedReason)
    {
        $this->failedReason = $failedReason;

        return $this;
    }

    /**
     * Get failedReason
     *
     * @return string
     */
    public function getFailedReason()
    {
        return $this->failedReason;
    }

    /**
     * Set device
     *
     * @param string $device
     *
     * @return Transaction
     */
    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Transaction
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set recurrentNumber
     *
     * @param integer $recurrentNumber
     *
     * @return Transaction
     */
    public function setRecurrentNumber($recurrentNumber)
    {
        $this->recurrentNumber = $recurrentNumber;

        return $this;
    }

    /**
     * Get recurrentNumber
     *
     * @return integer
     */
    public function getRecurrentNumber()
    {
        return $this->recurrentNumber;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return Transaction
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set paymentMethod
     *
     * @param int|PaymentMethod $paymentMethod
     *
     * @return Transaction
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = PaymentMethod::convert($paymentMethod);

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param int|TransactionPaymentType $paymentType
     *
     * @return $this
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = TransactionPaymentType::convert($paymentType);

        return $this;
    }

    /**
     * @return TransactionPaymentType
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set subscription
     *
     * @param Subscription $subscription
     *
     * @return Transaction
     */
    public function setSubscription(Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set transactionStatus
     *
     * @param int|TransactionStatus $transactionStatus
     *
     * @return Transaction
     */
    public function setTransactionStatus($transactionStatus)
    {
        $this->transactionStatus = TransactionStatus::convert($transactionStatus);

        return $this;
    }

    /**
     * Get transactionStatus
     *
     * @return TransactionStatus
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getTransactionStatus()->is(TransactionStatus::SUCCESS);
    }
}

