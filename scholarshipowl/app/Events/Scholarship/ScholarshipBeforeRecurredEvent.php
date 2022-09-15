<?php


namespace App\Events\Scholarship;


use App\Entity\Scholarship;

class ScholarshipBeforeRecurredEvent
{
    /**
     * @var Scholarship $scholarship
     */
    public $scholarship;

    /**
     * ScholarshipBeforeRecurredEvent constructor.
     *
     * @param Scholarship $scholarship
     */
    public function __construct(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
    }
}