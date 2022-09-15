<?php namespace App\Entity\Queue;

use App\Contracts\QueueRecord as QueueRecordContract;
use App\Entity\Traits\QueueRecord;

use Doctrine\ORM\Mapping as ORM;

/**
 * QueueDefault
 *
 * @ORM\Table(name="queue_default")
 * @ORM\Entity
 */
class QueueDefault implements QueueRecordContract
{
    use QueueRecord;

    /**
     * @var integer
     *
     * @ORM\Column(name="queue_default_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $queueDefaultId;

    /**
     * @var string
     *
     * @ORM\Column(name="queue", type="string", length=255, nullable=false)
     */
    private $queue;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="text", nullable=false)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * QueueDefault constructor.
     *
     * @param string        $queue
     * @param string|array  $data
     * @param string        $status
     */
    public function __construct(string $queue, $data, string $status = QueueRecordContract::STATUS_PENDING)
    {
        $this->setData(is_array($data) ? json_encode($data) : $data);
        $this->setQueue($queue);
        $this->setStatus($status);
    }

    /**
     * Get queueDefaultId
     *
     * @return integer
     */
    public function getId()
    {
        return $this->queueDefaultId;
    }

    /**
     * @param $queue
     *
     * @return QueueDefault
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return QueueDefault
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return QueueDefault
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
     * Set data
     *
     * @param string $data
     *
     * @return QueueDefault
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return QueueDefault
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return QueueDefault
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

