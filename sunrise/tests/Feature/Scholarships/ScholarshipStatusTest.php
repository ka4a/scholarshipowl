<?php namespace Tests\Feature\Scholarships;

use App\Doctrine\Types\RecurrenceConfigType\OneTimeConfig;
use App\Entities\Scholarship;
use Carbon\Carbon;
use Tests\TestCase;

class ScholarshipStatusTest extends TestCase
{
    public function test_scholarship_status_updated_after_start()
    {
        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig(
            new OneTimeConfig(
                Carbon::create()->addDay(1),
                Carbon::create()->addDay(2)
            )
        );

        $scholarship = $this->sm()->publish($template);

        $this->assertEquals(Scholarship::STATUS_UNPUBLISHED, $scholarship->getStatus());

        $this->sm()->maintain(Carbon::create()->addDay(1)->addHour(), [$scholarship->getId()]);

        $this->assertEquals(Scholarship::STATUS_PUBLISHED, $scholarship->getStatus());

        $this->sm()->maintain(Carbon::create()->addDay(3)->addHour(), [$scholarship->getId()]);

        $this->assertEquals(Scholarship::STATUS_EXPIRED, $scholarship->getStatus());
    }
}