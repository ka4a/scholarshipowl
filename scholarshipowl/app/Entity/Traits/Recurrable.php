<?php namespace App\Entity\Traits;

use App\Contracts\Recurrable as RecurrableContract;
use Carbon\Carbon;

trait Recurrable
{
    /**
     * @var string
     *
     * @ORM\Column(name="recurring_type", type="string", nullable=true, unique=false)
     */
    protected $recurringType;

    /**
     * @var integer
     *
     * @ORM\Column(name="recurring_value", type="smallint", precision=0, scale=0, nullable=true, unique=false)
     */
    protected $recurringValue;

    /**
     * @var \DateInterval
     */
    protected $recurringInterval;

    /**
     * @var array
     */
    public static $recurrenceTypes = [
        RecurrableContract::PERIOD_TYPE_DAY   => 'Day',
        RecurrableContract::PERIOD_TYPE_WEEK  => 'Week',
        RecurrableContract::PERIOD_TYPE_MONTH => 'Month',
        RecurrableContract::PERIOD_TYPE_YEAR  => 'Year',
    ];

    /**
     * @return \DateInterval
     */
    public function getRecurringInterval()
    {
        if ($this->recurringInterval === null) {
            $this->recurringInterval = new \DateInterval(
                sprintf('P%s%s', $this->getRecurringValue(), strtoupper($this->getRecurringType()[0]))
            );
        }

        return $this->recurringInterval;
    }

    /**
     * @return int
     */
    public function getRecurringType()
    {
        return $this->recurringType;
    }

    /**
     * @param string $recurringType
     *
     * @return $this
     */
    public function setRecurringType($recurringType)
    {
        $this->recurringType = $recurringType;
        $this->recurringInterval = null;

        return $this;
    }

    /**
     * @return int
     */
    public function getRecurringValue()
    {
        return $this->recurringValue;
    }

    /**
     * @param int $recurringValue
     *
     * @return $this
     */
    public function setRecurringValue($recurringValue)
    {
        $this->recurringValue = $recurringValue;
        $this->recurringInterval = null;

        return $this;
    }
}
