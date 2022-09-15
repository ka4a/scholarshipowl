<?php namespace App\Doctrine\Types\RecurrenceConfigType;

use Illuminate\Support\Carbon;

class WeeklyConfig implements IRecurrenceConfig
{
    const TYPE = 'weeklyScholarship';

    const KEY_START_DAY = 'startDay';

    const KEY_DEADLINE_DAY = 'deadlineDay';

    const KEY_OCCURRENCES = 'occurrences';

    const KEY_STARTS_AFTER_DEADLINE = 'startsAfterDeadline';

    /**
     * @var int
     */
    protected $startDay;

    /**
     * @var int
     */
    protected $deadlineDay;

    /**
     * @var int
     */
    protected $occurrences;

    /**
     * @var bool
     */
    protected $startsAfterDeadline;

    /**
     * WeeklyScholarship constructor.
     * @param int $startDay
     * @param int $deadlineDay
     * @param int $occurrences
     * @param bool $startsAfterDeadline
     */
    public function __construct(
        int $startDay,
        int $deadlineDay,
        int $occurrences,
        bool $startsAfterDeadline = false
    ) {
        $this->startDay = $startDay;
        $this->deadlineDay = $deadlineDay;
        $this->occurrences = $occurrences;
        $this->startsAfterDeadline = $startsAfterDeadline;
    }

    /**
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getStartDate(\DateTime $from = null, $occurrence = 1)
    {
        $from = $from ?: new \DateTime();
        if ($this->startsAfterDeadline) {
            return Carbon::instance($this->getDeadlineDate($from, $occurrence - 1))
                ->addDay()
                ->startOfDay();
        }

        return Carbon::instance($from)
            ->startOfWeek()
            ->addDay($this->startDay - 1)
            ->addWeek($occurrence - 1)
            ->startOfDay();
    }

    /**
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getDeadlineDate(\DateTime $from = null, $occurrence = 1)
    {
        $from = $from ?: new \DateTime();
        $date = Carbon::instance($from)
            ->startOfWeek()
            ->addDay($this->deadlineDay - 1)
            ->addWeek($occurrence - 1);

        if ($occurrence <= 1 && $this->startDay > $this->deadlineDay) {
            $date->addWeek();
        }

        return $date->endOfDay();
    }

    /**
     * @return string
     */
    public function getRecurringType()
    {
        return AdvancedConfig::PERIOD_TYPE_WEEK;
    }

    /**
     * @return int
     */
    public function getRecurringValue()
    {
        return 1;
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
            static::KEY_START_DAY => $this->startDay,
            static::KEY_DEADLINE_DAY => $this->deadlineDay,
            static::KEY_OCCURRENCES => $this->occurrences,
            static::KEY_STARTS_AFTER_DEADLINE => $this->startsAfterDeadline,
        ];
    }
}
