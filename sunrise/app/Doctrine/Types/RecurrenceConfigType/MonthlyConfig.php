<?php namespace App\Doctrine\Types\RecurrenceConfigType;

use Carbon\Carbon;
use DateTime;
use Exception;

class MonthlyConfig implements IRecurrenceConfig
{
    const TYPE = 'monthlyScholarship';

    const KEY_START_DATE = 'startDate';

    const KEY_DEADLINE_DATE = 'deadlineDate';

    const KEY_DEADLINE_END_OF_MONTH = 'deadlineEndOfMonth';

    const KEY_STARTS_AFTER_DEADLINE = 'startsAfterDeadline';

    const KEY_OCCURRENCES = 'occurrences';

    const KEY_EXCEPTIONS = 'exceptions';

    const KEY_EXCEPTIONS_MONTH = 'month';

    /**
     * @var int
     */
    protected $startDay;

    /**
     * @var int
     */
    protected $deadlineDay;

    /**
     * @var bool
     */
    protected $deadlineEndOfMonth = false;

    /**
     * @var bool
     */
    protected $startsAfterDeadline;

    /**
     * @var int
     */
    protected $occurrences;

    /**
     * @var array
     */
    protected $exceptions;

    /**
     * MonthlyConfig constructor.
     * @param int $startDay
     * @param int $deadlineDay
     * @param bool $deadlineEndOfMonth
     * @param bool $startsAfterDeadline
     * @param int|null $occurrences
     * @param array $exceptions
     */
    public function __construct(
        int $startDay,
        int $deadlineDay,
        bool $deadlineEndOfMonth,
        bool $startsAfterDeadline = false,
        int $occurrences = null,
        array $exceptions = [])
    {
        $this->startDay = $startDay;
        $this->deadlineDay = $deadlineDay;
        $this->deadlineEndOfMonth = $deadlineEndOfMonth;
        $this->startsAfterDeadline = $startsAfterDeadline;
        $this->occurrences = $occurrences;
        $this->exceptions = $exceptions;
    }

    /**
     * @param DateTime|null $from
     * @param int $occurrence
     * @return Carbon
     * @throws Exception
     */
    public function getStartDate(DateTime $from = null, $occurrence = 1)
    {
        $from = $from ?: new DateTime();
        if ($this->startsAfterDeadline) {
            $start = $this->getDeadlineDate($from, $occurrence - 1)->addDay();
        } else {
            $start = Carbon::instance($from)
                ->addMonthNoOverflow($occurrence - 1)
                ->day($this->startDay);
        }

        $key = static::KEY_EXCEPTIONS_MONTH;
        foreach ($this->exceptions as $exception) {
            if (isset($exception[$key]) && intval($exception[$key]) === $start->month) {
                if (isset($exception[static::KEY_START_DATE])) {
                    $start->day($exception[static::KEY_START_DATE]);
                }
            }
        }

        return $start->startOfDay();
    }

    /**
     * @param DateTime|null $from
     * @param int $occurrence
     * @return Carbon
     * @throws Exception
     */
    public function getDeadlineDate(DateTime $from = null, $occurrence = 1)
    {
        $from = $from ?: new DateTime();
        $deadline = Carbon::instance($from)
            ->addMonthNoOverflow($occurrence - 1);

        if ($this->startDay > $this->deadlineDay && ($occurrence == 1 || $deadline->day !== $this->deadlineDay)) {
            $deadline->addMonthNoOverflow();
        }

        if ($this->deadlineEndOfMonth) {
            $deadline->endOfMonth();
        } else {
            $deadline->day(min($deadline->daysInMonth, $this->deadlineDay));
        }

        $key = static::KEY_EXCEPTIONS_MONTH;
        foreach ($this->exceptions as $exception) {
            if (isset($exception[$key]) && intval($exception[$key]) === $deadline->month) {
                if (isset($exception[static::KEY_DEADLINE_DATE])) {
                    $deadline->day($exception[static::KEY_DEADLINE_DATE]);
                }
            }
        }


        return $deadline->endOfDay();
    }

    /**
     * @return string
     */
    public function getRecurringType()
    {
        return AdvancedConfig::PERIOD_TYPE_MONTH;
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
            static::KEY_START_DATE => $this->startDay,
            static::KEY_DEADLINE_DATE => $this->deadlineDay,
            static::KEY_DEADLINE_END_OF_MONTH => $this->deadlineEndOfMonth,
            static::KEY_STARTS_AFTER_DEADLINE => $this->startsAfterDeadline,
            static::KEY_OCCURRENCES => $this->occurrences,
            static::KEY_EXCEPTIONS => $this->exceptions
        ];
    }
}
