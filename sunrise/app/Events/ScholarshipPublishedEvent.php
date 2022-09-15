<?php namespace App\Events;

use App\Entities\Scholarship;
use Illuminate\Foundation\Events\Dispatchable;

class ScholarshipPublishedEvent
{
    use Dispatchable;

    /**
     * @var string
     */
    protected $scholarshipId;

    /**
     * ScholarshipPublishedEvent constructor.
     * @param Scholarship $scholarship
     */
    public function __construct(Scholarship $scholarship)
    {
        $this->scholarshipId = $scholarship->getId();
    }

    /**
     * @return string
     */
    public function getScholarshipId()
    {
        return $this->scholarshipId;
    }
}
