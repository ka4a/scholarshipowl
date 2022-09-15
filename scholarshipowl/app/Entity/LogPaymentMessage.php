<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * LogPaymentMessage
 *
 * @ORM\Table(name="log_payment_message")
 * @ORM\Entity
 */
class LogPaymentMessage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="log_payment_message_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $logPaymentMessageId;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="verified", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $verified;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="created_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createdDate;

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
     * Get logPaymentMessageId
     *
     * @return integer
     */
    public function getLogPaymentMessageId()
    {
        return $this->logPaymentMessageId;
    }

    /**
     * @param int|PaymentMethod $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethodId($paymentMethod)
    {
        $this->paymentMethod = PaymentMethod::convert($paymentMethod);

        return $this;
    }

    /**
     * Get paymentMethodId
     *
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return LogPaymentMessage
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set verified
     *
     * @param string $verified
     *
     * @return LogPaymentMessage
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * Get verified
     *
     * @return string
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return LogPaymentMessage
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
}

