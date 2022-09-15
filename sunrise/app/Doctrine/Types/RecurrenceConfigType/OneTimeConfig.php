<?php namespace App\Doctrine\Types\RecurrenceConfigType;

use Carbon\Carbon;

class OneTimeConfig implements IRecurrenceConfig
{
    const TYPE = 'oneTime';

    const KEY_START = 'start';

    const KEY_DEADLINE = 'deadline';

    /**
     * @var \DateTime
     */
    protected $start;

    /**
     * @var \DateTime
     */
    protected $deadline;

    /**
     * OneTimeScholarship constructor.
     * @param \DateTime $start
     * @param \DateTime $deadline
     */
    public function __construct(\DateTime $start, \DateTime $deadline)
    {
        $this->start = $start;
        $this->deadline = $deadline;
    }

    /**
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getStartDate(\DateTime $from = null, $occurrence = null)
    {
        return Carbon::instance($this->start)->startOfDay();
    }

    /**
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getDeadlineDate(\DateTime $from = null, $occurrence = null)
    {
        return Carbon::instance($this->deadline)->endOfDay();
    }

    /**
     * @return null
     */
    public function getRecurringValue()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getRecurringType()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isRecurrable()
    {
        return false;
    }

    /**
     * @return int
     */
    public function getOccurrences()
    {
        return 1;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            static::KEY_TYPE => static::TYPE,
            static::KEY_START => $this->start->format('Y-m-d'),
            static::KEY_DEADLINE => $this->deadline->format('Y-m-d'),
        ];
    }
}
