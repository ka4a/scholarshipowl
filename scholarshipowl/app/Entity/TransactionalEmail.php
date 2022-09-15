<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 24/5/2016
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TransactionalEmail
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="transactional_email", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="transactional_email_template_name_unique", columns={"template_name"})
 * })
 */
class TransactionalEmail
{
    const PERIOD_DAY = "day";
    const PERIOD_WEEK = "week";
    const PERIOD_HOUR = "hour";
    const PERIOD_MONTH = "month";
    const PERIOD_YEAR = "year";

    const DELAY_SECOND = 'second';
    const DELAY_MINUTE = 'minute';
    const DELAY_HOUR = 'hour';
    const DELAY_DAY = 'day';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $transactionalEmailId;

    /**
     * @var string
     *
     * @ORM\Column(name="event_name", type="string", length=60, nullable=false, unique=false)
     */
    protected $event_name;

    /**
     * @var string
     *
     * @ORM\Column(name="template_name", type="string", length=60, nullable=false, unique=true)
     */
    protected $template_name;

    /**
     * @var string
     *
     * @ORM\Column(name="from_email", type="string", length=128, nullable=false, unique=false)
     */
    protected $fromEmail = "ScholarshipOwl@scholarshipowl.com";

    /**
     * @var string
     *
     * @ORM\Column(name="from_name", type="string", length=128, nullable=false, unique=false)
     */
    protected $from_name;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=256, nullable=true, unique=false)
     */
    protected $subject;

    /**
     * @var integer
     *
     * @ORM\Column(name="sending_cap", type="smallint", options={"unsigned"=true}, nullable=false, unique=false)
     */
    protected $sending_cap = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="cap_period", type="string", nullable=false, unique=false)
     */
    protected $cap_period = "day";

    /**
     * @var int
     *
     * @ORM\Column(name="cap_value", type="integer", nullable=false, unique=false)
     */
    protected $cap_value = 1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, unique=false)
     */
    protected $active = false;

    /**
     * @var string
     *
     * @ORM\Column(name="delay_type", type="string", nullable=true, unique=false)
     */
    protected $delay_type;

    /**
     * @var int
     *
     * @ORM\Column(name="delay_value", type="integer", nullable=true, unique=false)
     */
    protected $delay_value;

    /**
     * @return array
     */
    public static function options()
    {
        $options = [];

        /** @var static $email */
        foreach (\EntityManager::getRepository(static::class)->findAll() as $email) {
            $options[$email->getTransactionalEmailId()] = $email->getEventName();
        }

        return $options;
    }

    /**
     * @return array
     */
    public static function delayOptions()
    {
        return [
            self::DELAY_SECOND => 'Seconds',
            self::DELAY_MINUTE => 'Minutes',
            self::DELAY_HOUR   => 'Hours',
            self::DELAY_DAY    => 'Days',
        ];
    }

    /**
     * TransactionalEmail constructor.
     *
     * @param string $eventName
     * @param string $templateName
     * @param string $fromName
     * @param string $subject
     * @param string $fromEmail
     * @param bool $isActive
     * @param int $sendingCap
     */
    public function __construct(
        string $eventName,
        string $templateName,
        string $fromName,
        string $subject,
        string $fromEmail='ScholarshipOwl@scholarshipowl.com',
        bool $isActive = false,
        int $sendingCap = 0)
    {
        $this->setEventName($eventName);
        $this->setTemplateName($templateName);
        $this->setFromName($fromName);
        $this->setSubject($subject);
        $this->setFromEmail($fromEmail);
        $this->setActive($isActive);
        $this->setSendingCap($sendingCap);
    }

    /**
     * @return integer
     */
    public function getTransactionalEmailId()
    {
        return $this->transactionalEmailId;
    }

    /**
     * @param integer $transactionalEmailId
     */
    public function setTransactionalEmailId($transactionalEmailId)
    {
        $this->transactionalEmailId = $transactionalEmailId;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->event_name;
    }

    /**
     * @param string $event_name
     */
    public function setEventName($event_name)
    {
        $this->event_name = $event_name;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->template_name;
    }

    /**
     * @param string $template_name
     */
    public function setTemplateName($template_name)
    {
        $this->template_name = $template_name;
    }

    /**
     * @return string
     */
    public function getFromEmail() {
        return $this->fromEmail;
    }

    /**
     * @param string $from
     */
    public function setFromEmail($fromEmail) {
        $this->fromEmail = $fromEmail;
    }


    /**
     * @return string
     */
    public function getFromName() {
        return $this->from_name;
    }

    /**
     * @param string $from_name
     */
    public function setFromName($from_name) {
        $this->from_name = $from_name;
    }

    /**
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @return int
     */
    public function getSendingCap()
    {
        return $this->sending_cap;
    }

    /**
     * @param int $sending_cap
     */
    public function setSendingCap($sending_cap)
    {
        $this->sending_cap = $sending_cap;
    }

    /**
     * @return string
     */
    public function getCapPeriod()
    {
        return $this->cap_period;
    }

    /**
     * @return int
     */
    public function getCapValue()
    {
        return $this->cap_value;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setCapValue($value)
    {
        $this->cap_value = $value;

        return $this;
    }

    /**
     * @return \DateInterval
     */
    public function getCapInterval()
    {
        return \DateInterval::createFromDateString(sprintf('%s %s', $this->getCapValue(), $this->getCapPeriod()));
    }

    /**
     * @param string $cap_period
     */
    public function setCapPeriod($cap_period)
    {
        $this->cap_period = $cap_period;
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
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param $delayType
     *
     * @return $this
     */
    public function setDelayType($delayType)
    {
        $this->delay_type = $delayType;

        return $this;
    }

    /**
     * @return string
     */
    public function getDelayType()
    {
        return $this->delay_type;
    }

    /**
     * @param $delayValue
     *
     * @return $this
     */
    public function setDelayValue($delayValue)
    {
        $this->delay_value = $delayValue;

        return $this;
    }

    /**
     * @return int
     */
    public function getDelayValue()
    {
        return $this->delay_value;
    }

    /**
     * @return \DateInterval
     */
    public function getDelayInterval()
    {
        if ($this->getDelayType() && $this->getDelayValue()) {
            return \DateInterval::createFromDateString(sprintf('%s %s', $this->getDelayValue(), $this->getDelayType()));
        }

        return false;
    }

    /**
     * @return array
     */
    public function getPeriodValues(){
        return array(
            self::PERIOD_HOUR => "Hour",
            self::PERIOD_DAY => "Day",
            self::PERIOD_WEEK => "Week",
            self::PERIOD_MONTH => "Month",
            self::PERIOD_YEAR => "Year",
        );
    }
}
