<?php

namespace App\Events\Scholarship;

class ScholarshipUpdatedEvent extends ScholarshipEvent
{
    /**
     * Indicates whether update caused eligibility changes
     *
     * @var bool
     */
    public $isEligibilityUpdated = false;

    /**
     * Indicates whether a state was updated (status, isActive)
     *
     * @var bool
     */
    public $isStatusUpdated = false;

    /**
     * ScholarshipUpdatedEvent constructor.
     *
     * @param \App\Entity\Scholarship|int $scholarship
     * @param bool $isEligibilityUpdated
     */
    public function __construct($scholarship, $isEligibilityUpdated = false, $isStatusUpdated = false)
    {
        parent::__construct($scholarship);

        $this->isEligibilityUpdated = $isEligibilityUpdated;
        $this->isStatusUpdated = $isStatusUpdated;
    }

    /**
     * Clear scholarship entity on serialize.
     */
    public function __sleep()
    {
        $fields = parent::__sleep();

        return array_merge($fields, ['isEligibilityUpdated', 'isStatusUpdated']);
    }
}
