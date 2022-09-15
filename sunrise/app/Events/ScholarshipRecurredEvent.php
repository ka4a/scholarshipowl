<?php namespace App\Events;

use App\Entities\Scholarship;
use Illuminate\Foundation\Events\Dispatchable;

class ScholarshipRecurredEvent
{
    use Dispatchable;

    /**
     * @var Scholarship $newScholarship
     */
    public $scholarshipId;

    /**
     * ScholarshipRecurredEvent constructor.
     *
     * @param Scholarship $new
     */
    public function __construct(Scholarship $new)
    {
        $this->scholarshipId = $new->getId();
    }

    /**
     * @return Scholarship|int
     */
    public function getScholarshipId()
    {
        return $this->scholarshipId;
    }
}
