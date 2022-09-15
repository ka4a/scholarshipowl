<?php namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DelayPeriod
{
    /**
     * @var null|string
     *
     * @ORM\Column(name="delay_type", type="string", nullable=true)
     */
    protected $delayType;

    /**
     * @var int
     *
     * @ORM\Column(name="delay_value", type="integer")
     */
    protected $delayValue = 0;

    /**
     * @param string $delayType
     *
     * @return $this
     */
    public function setDelayType($delayType)
    {
        $this->delayType = $delayType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDelayType()
    {
        return $this->delayType;
    }

    /**
     * @param int $delayValue
     *
     * @return $this
     */
    public function setDelayValue($delayValue)
    {
        $this->delayValue = $delayValue;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDelayValue()
    {
        return $this->delayValue;
    }

    /**
     * @return string
     */
    public function getDelayString()
    {
        return $this->getDelayValue().' '.$this->getDelayType();
    }
}
