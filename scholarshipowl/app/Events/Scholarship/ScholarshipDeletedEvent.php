<?php

namespace App\Events\Scholarship;

use App\Entity\Scholarship;

class ScholarshipDeletedEvent extends ScholarshipEvent
{
    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        if ($this->scholarship === null) {
            $this->scholarship = new Scholarship([]);
            $scholarshipRef = new \ReflectionObject($this->scholarship);
            $scholarshipRefProp = $scholarshipRef->getProperty('scholarshipId');
            $scholarshipRefProp->setAccessible(true);
            $scholarshipRefProp->setValue($this->scholarship, $this->id);
        }

        return $this->scholarship;
    }
}
