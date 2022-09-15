<?php

namespace App\Events\Scholarship;

class ScholarshipCreatedEvent extends ScholarshipEvent
{
    /**
     * @var bool
     */
    public $isEligibilityUpdated = false;

    /**
     * ScholarshipUpdatedEvent constructor.
     *
     * @param \App\Entity\Scholarship|int $scholarship
     */
    public function __construct($scholarship)
    {
        parent::__construct($scholarship);
    }
}
