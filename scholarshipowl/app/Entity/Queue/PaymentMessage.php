<?php namespace App\Entity\Queue;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PaymentMessage
 * @package Entity\Queue
 *
 * @ORM\Entity
 * @ORM\Table(name="queue_payment_message")
 */
class PaymentMessage
{

    const STATUS_PENDING = 'Pending';

    const STATUS_RUNNING = 'Running';

    const STATUS_SUCCESS = 'Success';

    const STATUS_FAILED = 'Failed';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $queue_payment_message_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $listener;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $message;

    /**
     * @var string
     * 
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status_message = '';

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    protected $lastrun_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * PaymentMessage constructor.
     *
     * @param string $listener
     * @param string $message
     * @param string $status
     */
    public function __construct(string $listener, string $message, $status = self::STATUS_PENDING)
    {
        $this->setListener($listener);
        $this->setMessage($message);
        $this->setStatus($status);
        
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return int
     */
    public function getPaymentMessageId()
    {
        return $this->queue_payment_message_id;
    }

    /**
     * @param string $listener
     *
     * @return string
     */
    public function setListener(string $listener)
    {
        return $this->listener = $listener;
    }

    /**
     * @return mixed
     */
    public function getListener()
    {
        return $this->listener;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public function setMessage(string $message)
    {
        return $this->message = $message;
    }
    
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $status
     *
     * @return string
     */
    public function setStatus(string $status)
    {
        return $this->status = $status;
    }
    
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $message
     * 
     * @return string
     */
    public function setStatusMessage(string $message)
    {
        return $this->status_message = $message;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->status_message;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    public function setLastRunAt(\DateTime $dateTime)
    {
        return $this->lastrun_at = $dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getLastRunAt()
    {
        return $this->lastrun_at;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    public function setCreatedAt(\DateTime $dateTime)
    {
        return $this->created_at = $dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    public function setUpdatedAt(\DateTime $dateTime)
    {
        return $this->updated_at = $dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}
