<?php namespace App\Doctrine\Types\RecurrenceConfigType;

interface IRecurrenceConfig
{

    const KEY_TYPE = 'type';

    /**
     * Scholarship can run only 1 time.
     *
     * @return bool
     */
    public function isRecurrable();

    /**
     * Get start date of some specific occurrence from specific date.
     *
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getStartDate(\DateTime $from = null, $occurrence = 1);

    /**
     * Get deadline date of some specific occurrence from specific date.
     *
     * @param \DateTime $from
     * @param int $occurrence
     * @return \DateTime
     */
    public function getDeadlineDate(\DateTime $from = null, $occurrence = 1);

    /**
     * @return null|int
     */
    public function getRecurringValue();

    /**
     * @return null|string
     */
    public function getRecurringType();

    /**
     * @return int
     */
    public function getOccurrences();

    /**
     * @return array
     */
    public function toArray();
}
