<?php namespace App\Doctrine\Types\RecurrenceConfigType;

use Carbon\Carbon;

class AdvancedConfig implements IRecurrenceConfig
{

    const TYPE = 'advanced';

    const KEY_START_DATE = 'start';

    const KEY_DEADLINE_DATE = 'deadline';

    const KEY_PERIOD_TYPE = 'periodType';

    const KEY_PERIOD_VALUE = 'periodValue';

    const KEY_OCCURRENCES = 'occurrences';

    const PERIOD_TYPE_DAY = "day";

    const PERIOD_TYPE_WEEK = "week";

    const PERIOD_TYPE_MONTH = "month";

    const PERIOD_TYPE_YEAR = "year";

    /**
     * @var array
     */
    public static $recurrenceTypes = [
        self::PERIOD_TYPE_DAY   => 'Day',
        self::PERIOD_TYPE_WEEK  => 'Week',
        self::PERIOD_TYPE_MONTH => 'Month',
        self::PERIOD_TYPE_YEAR  => 'Year',
    ];

    /**
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @var \DateTime
     */
    protected $deadlineDate;

    /**
     * @var
     */
    protected $periodType;

    /**
     * @var int
     */
    protected $periodValue;

    /**
     * @var int
     */
    protected $occurrences;

    /**
     * AdvancedConfigScholarship constructor.
     * @param \DateTime $startDate
     * @param \DateTime $deadlineDate
     * @param string $periodType
     * @param int $periodValue
     * @param int $occurrences
     */
    public function __construct(
        \DateTime $startDate,
        \DateTime $deadlineDate,
        string $periodType,
        int $periodValue,
        int $occurrences = null
    ) {
        $this->startDate = $startDate;
        $this->deadlineDate = $deadlineDate;
        $this->periodType = $periodType;
        $this->periodValue = $periodValue;
        $this->occurrences = $occurrences;
    }

    /**
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getStartDate(\DateTime $from = null, $occurrence = 1)
    {
        $start = Carbon::instance($from ?: $this->startDate);

        $interval = $this->getInterval();
        for ($i = 0; $i < $occurrence - 1; $i++) {
            if ($this->periodType === static::PERIOD_TYPE_MONTH) {
                $start->addMonthNoOverflow($this->periodValue);
            } else {
                $start->add($interval);
            }
        }

        return $start->startOfDay();
    }

    /**
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getDeadlineDate(\DateTime $from = null, $occurrence = 1)
    {
        $deadline = Carbon::instance($from ?: $this->deadlineDate);

        $interval = $this->getInterval();
        for ($i = 0; $i < $occurrence - 1; $i++) {
            if ($this->periodType === static::PERIOD_TYPE_MONTH) {
                $deadline->addMonthNoOverflow($this->periodValue);
            } else {
                $deadline->add($interval);
            }
        }

        return $deadline->endOfDay();
    }

    /**
     * @return string
     */
    public function getRecurringType()
    {
        return $this->periodType;
    }

    /**
     * @return int
     */
    public function getRecurringValue()
    {
        return $this->periodValue;
    }

    /**
     * @return bool
     */
    public function isRecurrable()
    {
        return true;
    }

    /**
     * @return int
     */
    public function getOccurrences()
    {
        return $this->occurrences;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            static::KEY_TYPE => static::TYPE,
            static::KEY_START_DATE => $this->startDate->format('Y-m-d'),
            static::KEY_DEADLINE_DATE => $this->deadlineDate->format('Y-m-d'),
            static::KEY_PERIOD_TYPE => $this->periodType,
            static::KEY_PERIOD_VALUE => $this->periodValue,
            static::KEY_OCCURRENCES => $this->occurrences,
        ];
    }

    /**
     * @return \DateInterval
     */
    protected function getInterval()
    {
        return new \DateInterval(sprintf('P%s%s', $this->periodValue, strtoupper($this->periodType[0])));
    }
}
