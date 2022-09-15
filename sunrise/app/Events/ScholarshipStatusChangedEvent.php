<?php namespace App\Events;


use App\Entities\Scholarship;
use Illuminate\Foundation\Events\Dispatchable;

class ScholarshipStatusChangedEvent
{
    use Dispatchable;

    /**
     * @var string
     */
    protected $scholarshipId;

    /**
     * ScholarshipStatusChangedEvent constructor.
     * @param Scholarship|string $scholarship
     */
    public function __construct($scholarship)
    {
        $this->scholarshipId = ($scholarship instanceof Scholarship) ? $scholarship->getId() : $scholarship;
    }

    /**
     * @return string
     */
    public function getScholarshipId(): string
    {
        return $this->scholarshipId;
    }
}