<?php namespace App\Contracts;

interface Recurrable
{
    const PERIOD_TYPE_DAY = "day";
    const PERIOD_TYPE_WEEK = "week";
    const PERIOD_TYPE_MONTH = "month";
    const PERIOD_TYPE_YEAR = "year";

    /**
     * @return bool
     */
    public function isRecurrable();

    /**
     * @return \DateInterval
     */
    public function getRecurringInterval();

    /**
     * @return int
     */
    public function getRecurringType();

    /**
     * @param string $recurringType
     *
     * @return $this
     */
    public function setRecurringType($recurringType);

    /**
     * @return int
     */
    public function getRecurringValue();

    /**
     * @param int $recurringValue
     *
     * @return $this
     */
    public function setRecurringValue($recurringValue);
}
