<?php


namespace App\Events\Scholarship;


use App\Entity\Scholarship;
use App\Events\Event;

class ScholarshipRecurredEvent extends Event
{
    /**
     * @var Scholarship $scholarship
     */
    public $scholarship;
    
    /**
     * @var Scholarship $newScholarship
     */
    public $newScholarship;

    /**
     * ScholarshipRecurredEvent constructor.
     *
     * @param Scholarship $scholarship
     * @param Scholarship $newScholarship
     */
    public function __construct(Scholarship $scholarship, Scholarship $newScholarship)
    {
        $this->scholarship = $scholarship;
        $this->newScholarship = $newScholarship;
    }
}