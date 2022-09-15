<?php
namespace App\Entity\Marketing;


use Doctrine\ORM\Mapping as ORM;

/**
 * MobilePushNotificationSettings
 *
 * @ORM\Table(name="mobile_push_notification_settings")
 * @ORM\Entity
 */
class MobilePushNotificationSettings
{
    /**
     * @var int
     *
     * @ORM\Column(name="push_notification_id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pushNotificationId;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_name", type="string", length=255, nullable=false)
     */
    private $notificationName;

    /**
     * @var string
     *
     * @ORM\Column(name="event_name", type="string", length=255, nullable=false)
     */
    private $eventName;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = '0';

    public function __construct($name, $eventName, bool $status)
    {
        $this->notificationName = $name;
        $this->eventName = $eventName;
        $this->active = $status;
    }

    /**
     * @return int
     */
    public function getPushNotificationId()
    {
        return $this->pushNotificationId;
    }

    /**
     * @return string
     */
    public function getNotificationName()
    {
        return $this->notificationName;
    }

    /**
     * @param string $notificationName
     */
    public function setNotificationName(string $notificationName)
    {
        $this->notificationName = $notificationName;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    //switch between statuses
    public function switchStatus()
    {
        $this->setActive(!$this->isActive());
    }

}
