<?php namespace App\Entity;

use App\Entity\Traits\DelayPeriod;
use App\Entity\Traits\CapPeriod;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * Class OnesignalNotification
 * @ORM\Entity()
 * @ORM\Table(name="onesignal_notification")
 */
class OnesignalNotification
{
    use CapPeriod;
    use DelayPeriod;
    use Timestamps;

    const APP_WEB = 'web';
    const APP_MOBILE = 'mobile';

    const CAP_PERIOD_TYPE_MINUTES = 'minute';
    const CAP_PERIOD_TYPE_HOUR = 'hour';
    const CAP_PERIOD_TYPE_DAY = 'day';
    const CAP_PERIOD_TYPE_WEEK = 'week';
    const CAP_PERIOD_TYPE_MONTH = 'month';
    const CAP_PERIOD_TYPE_YEAR = 'year';

    /**
     * @var array
     */
    public static $periodTypes = [
        self::CAP_PERIOD_TYPE_MINUTES   => 'Minute',
        self::CAP_PERIOD_TYPE_HOUR      => 'Hour',
        self::CAP_PERIOD_TYPE_DAY       => 'Day',
        self::CAP_PERIOD_TYPE_WEEK      => 'Week',
        self::CAP_PERIOD_TYPE_MONTH     => 'Month',
        self::CAP_PERIOD_TYPE_YEAR      => 'Year',
    ];

    /**
     * @var NotificationType
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="NotificationType")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="app", type="string")
     */
    private $app;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = false;

    /**
     * @var null|string
     *
     * @ORM\Column(name="template_id", type="string", nullable=true)
     */
    private $templateId;

    /**
     * @var null|string
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    private $title;

    /**
     * @var null|string
     *
     * @ORM\Column(name="content", type="string", nullable=true)
     */
    private $content;

    /**
     * OnesignalNotification constructor.
     *
     * @param string               $app
     * @param int|NotificationType $type
     */
    public function __construct($app, $type)
    {
        $this->setApp($app);
        $this->setType($type);
    }

    /**
     * @param int|NotificationType $id
     *
     * @return $this
     */
    public function setType($id)
    {
        $this->type = NotificationType::convert($id);

        return $this;
    }

    /**
     * @return NotificationType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $app
     *
     * @return $this
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param string $onesignalTemplateId
     *
     * @return $this
     */
    public function setTemplateId(string $onesignalTemplateId)
    {
        $this->templateId = $onesignalTemplateId;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        return $this->content;
    }
}
