<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OnesignalNotificationSent
 *
 * @ORM\Table(name="onesignal_notification_sent", indexes={@ORM\Index(name="onesignal_notification_sent_app_id_foreign", columns={"app_id"})})
 * @ORM\Entity
 */
class OnesignalNotificationSent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=36, nullable=false)
     */
    private $userId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="app", type="string")
     */
    private $app;

    /**
     * @var NotificationType
     *
     * @ORM\ManyToOne(targetEntity="NotificationType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * OnesignalNotificationSent constructor.
     *
     * @param string                $app
     * @param string                $userId
     * @param NotificationType|int  $type
     */
    public function __construct($app, string $userId, $type)
    {
        $this->type = NotificationType::convert($type);
        $this->userId = $userId;
        $this->app = $app;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return NotificationType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

