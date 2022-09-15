<?php namespace App\Events;

use App\Entities\Scholarship;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * After scholarship expired this event is triggered.
 */
class ScholarshipDeadlineEvent
{
    use Dispatchable;

    /**
     * @var int
     */
    protected $scholarshipId;

    /**
     * Create a new event instance.
     *
     * @param Scholarship $scholarship
     */
    public function __construct(Scholarship $scholarship)
    {
        $this->scholarshipId = $scholarship->getId();
    }

    /**
     * @return int
     */
    public function getScholarshipId()
    {
        return $this->scholarshipId;
    }
}
