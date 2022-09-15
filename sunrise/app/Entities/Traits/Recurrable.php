<?php namespace App\Entities\Traits;

use App\Contracts\Recurrable as RecurrableContract;
use Doctrine\ORM\Mapping as ORM;

trait Recurrable
{

    /**
     * @var \DateInterval
     */
    protected $recurringInterval;

    /**
     * @return bool
     */
    public function isRecurrable()
    {
        return $this->recurringType !== null && $this->recurringValue !== null;
    }

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

}
