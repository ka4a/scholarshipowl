<?php namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CapPeriod
{
    /**
     * @var int
     *
     * @ORM\Column(name="cap_amount", type="integer")
     */
    protected $capAmount = 0;

    /**
     * @var null|string
     *
     * @ORM\Column(name="cap_type", type="string", nullable=true)
     */
    protected $capType;

    /**
     * @var int
     *
     * @ORM\Column(name="cap_value", type="integer")
     */
    protected $capValue = 0;

    /**
     * @param int $capAmount
     *
     * @return $this
     */
    public function setCapAmount(int $capAmount)
    {
        $this->capAmount = $capAmount;
        return $this;
    }

    /**
     * @return int
     */
    public function getCapAmount()
    {
        return $this->capAmount;
    }

    /**
     * @param string $capType
     *
     * @return $this
     */
    public function setCapType($capType)
    {
        $this->capType = $capType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCapType()
    {
        return $this->capType;
    }

    /**
     * @param int $capValue
     *
     * @return $this
     */
    public function setCapValue($capValue)
    {
        $this->capValue = $capValue;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCapValue()
    {
        return $this->capValue;
    }

    /**
     * @return \DateInterval
     */
    public function getCapPeriod()
    {
        return \DateInterval::createFromDateString($this->getCapValue().' '.$this->getCapType());
    }
}
