<?php namespace Tests\Feature;

use App\Doctrine\Types\RecurrenceConfigType\AdvancedConfig;
use App\Doctrine\Types\RecurrenceConfigType\MonthlyConfig;
use App\Doctrine\Types\RecurrenceConfigType\OneTimeConfig;
use App\Doctrine\Types\RecurrenceConfigType\WeeklyConfig;
use App\Events\ScholarshipDeadlineEvent;
use App\Events\ScholarshipRecurredEvent;
use App\Repositories\ScholarshipRepository;
use App\Entities\Scholarship;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ScholarshipRecurringMechanismTest extends TestCase
{
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function test_advanced_config_recurrence()
    {
        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            AdvancedConfig::KEY_TYPE => AdvancedConfig::TYPE,
            AdvancedConfig::KEY_START_DATE => Carbon::now()->startOfMonth()->format('c'),
            AdvancedConfig::KEY_DEADLINE_DATE => Carbon::now()->endOfMonth()->format('c'),
            AdvancedConfig::KEY_PERIOD_VALUE => 2,
            AdvancedConfig::KEY_PERIOD_TYPE => AdvancedConfig::PERIOD_TYPE_MONTH,
            AdvancedConfig::KEY_OCCURRENCES => 3,
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->startOfMonth();
        $expectedDeadline = Carbon::now()->endOfMonth()->endOfDay();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);

        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow(2);
        $expectedDeadline->addMonthNoOverflow(2);
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(2, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);

        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow(2);
        $expectedDeadline->addMonthNoOverflow(2);
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(3, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);

        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function test_monthly_scholarship_recurrence()
    {
        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            MonthlyConfig::KEY_TYPE => MonthlyConfig::TYPE,
            MonthlyConfig::KEY_START_DATE => 1,
            MonthlyConfig::KEY_DEADLINE_DATE => 30,
            MonthlyConfig::KEY_OCCURRENCES => 2,
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->startOfMonth()->day(1)->startOfDay();
        $expectedDeadline = Carbon::now()->startOfMonth()->day(min(30, $expectedStart->daysInMonth))->endOfDay();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow();
        $expectedDeadline->addMonthNoOverflow()->day(min(30, $expectedStart->daysInMonth))->endOfDay();
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(2, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            MonthlyConfig::KEY_TYPE => MonthlyConfig::TYPE,
            MonthlyConfig::KEY_START_DATE => 18,
            MonthlyConfig::KEY_DEADLINE_DATE => 2,
            MonthlyConfig::KEY_OCCURRENCES => 3,
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->startOfMonth()->day(18)->startOfDay();
        $expectedDeadline = Carbon::now()->startOfMonth()->addMonthNoOverflow()->day(2)->endOfDay();

        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow();
        $expectedDeadline->addMonthNoOverflow();
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(2, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow();
        $expectedDeadline->addMonthNoOverflow();
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(3, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            MonthlyConfig::KEY_TYPE => MonthlyConfig::TYPE,
            MonthlyConfig::KEY_START_DATE => 19,
            MonthlyConfig::KEY_DEADLINE_DATE => 18,
            MonthlyConfig::KEY_STARTS_AFTER_DEADLINE => true,
            MonthlyConfig::KEY_OCCURRENCES => 2,
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->startOfMonth()->day(19)->startOfDay();
        $expectedDeadline = Carbon::now()->addMonthNoOverflow()->startOfMonth()->day(18)->endOfDay();

//         dd($expectedStart, $expectedDeadline, $scholarship->getStart(), $scholarship->getDeadline());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow();
        $expectedDeadline->addMonthNoOverflow();

//        dd($expectedStart, $expectedDeadline, $scholarship->getStart(), $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(2, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            MonthlyConfig::KEY_TYPE => MonthlyConfig::TYPE,
            MonthlyConfig::KEY_START_DATE => 1,
            MonthlyConfig::KEY_DEADLINE_DATE => 30,
            MonthlyConfig::KEY_STARTS_AFTER_DEADLINE => true,
            MonthlyConfig::KEY_DEADLINE_END_OF_MONTH => true,
            MonthlyConfig::KEY_OCCURRENCES => 2,
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->startOfMonth();
        $expectedDeadline = Carbon::now()->endOfMonth();

//         dd($expectedStart, $expectedDeadline, $scholarship->getStart(), $scholarship->getDeadline());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow();
        $expectedDeadline->addMonthNoOverflow()->endOfMonth();

//        dd($expectedStart, $expectedDeadline, $scholarship->getStart(), $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(2, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            MonthlyConfig::KEY_TYPE => MonthlyConfig::TYPE,
            MonthlyConfig::KEY_START_DATE => 12,
            MonthlyConfig::KEY_DEADLINE_DATE => 30,
            MonthlyConfig::KEY_DEADLINE_END_OF_MONTH => true,
            MonthlyConfig::KEY_OCCURRENCES => 2,
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->day(12)->startOfDay();
        $expectedDeadline = Carbon::now()->endOfMonth();

//         dd($expectedStart, $expectedDeadline, $scholarship->getStart(), $scholarship->getDeadline());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addMonthNoOverflow();
        $expectedDeadline->addMonthNoOverflow()->endOfMonth();

//        dd($expectedStart, $expectedDeadline, $scholarship->getStart(), $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(2, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));

    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function test_weekly_scholarship_recurrence()
    {
        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            WeeklyConfig::KEY_TYPE => WeeklyConfig::TYPE,
            WeeklyConfig::KEY_START_DAY => 2, // Monday
            WeeklyConfig::KEY_DEADLINE_DAY => 5, // Thursday
            WeeklyConfig::KEY_OCCURRENCES => 2,
        ]);

        $scholarship1 = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->startOfWeek()->next(Carbon::MONDAY)->startOfDay();
        $expectedDeadline = Carbon::now()->startOfWeek()->next(Carbon::THURSDAY)->endOfDay();

        $this->assertEquals($expectedDeadline, $scholarship1->getDeadline());
        $this->assertEquals($expectedStart, $scholarship1->getStart());
        $this->assertTrue($scholarship1->getDeadline() > $scholarship1->getStart());
        $this->assertEquals(1, Carbon::instance($scholarship1->getStart())->dayOfWeek);
        $this->assertEquals(4, Carbon::instance($scholarship1->getDeadline())->dayOfWeek);
        $this->assertEquals(1, $scholarship1->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship1->getDeadline())->addDay(), [$scholarship1->getId()]);

        $scholarship2 = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addWeek();
        $expectedDeadline->addWeek();

        $this->assertNotNull($scholarship2);
        $this->assertEquals($expectedDeadline, $scholarship2->getDeadline());
        $this->assertEquals($expectedStart, $scholarship2->getStart());
        $this->assertTrue($scholarship2->getDeadline() > $scholarship2->getStart());
        $this->assertEquals(1, Carbon::instance($scholarship2->getStart())->dayOfWeek);
        $this->assertEquals(4, Carbon::instance($scholarship2->getDeadline())->dayOfWeek);
        $this->assertEquals(2, $scholarship2->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship2->getDeadline())->addDay(), [$scholarship2->getId()]);

        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            WeeklyConfig::KEY_TYPE => WeeklyConfig::TYPE,
            WeeklyConfig::KEY_START_DAY => 5,
            WeeklyConfig::KEY_DEADLINE_DAY => 2,
            WeeklyConfig::KEY_OCCURRENCES => 3,
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedStart = Carbon::now()->startOfWeek()->next(Carbon::THURSDAY)->startOfDay();
        $expectedDeadline = Carbon::now()->addWeek()->startOfWeek()->next(Carbon::MONDAY)->endOfDay();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(Carbon::THURSDAY, Carbon::instance($scholarship->getStart())->dayOfWeek);
        $this->assertEquals(Carbon::MONDAY, Carbon::instance($scholarship->getDeadline())->dayOfWeek);
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addWeek();
        $expectedDeadline->addWeek();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(Carbon::THURSDAY, Carbon::instance($scholarship->getStart())->dayOfWeek);
        $this->assertEquals(Carbon::MONDAY, Carbon::instance($scholarship->getDeadline())->dayOfWeek);
        $this->assertEquals(2, $scholarship->getOccurrence());

//        dd($scholarship->getStart(), $scholarship->getDeadline());
        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addWeek();
        $expectedDeadline->addWeek();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(Carbon::THURSDAY, Carbon::instance($scholarship->getStart())->dayOfWeek);
        $this->assertEquals(Carbon::MONDAY, Carbon::instance($scholarship->getDeadline())->dayOfWeek);
        $this->assertEquals(3, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));

        $template = $this->generateScholarshipTemplate();
        $template->setRecurrenceConfig([
            WeeklyConfig::KEY_TYPE => WeeklyConfig::TYPE,
            WeeklyConfig::KEY_DEADLINE_DAY => 7,
            WeeklyConfig::KEY_START_DAY => 1,
            WeeklyConfig::KEY_OCCURRENCES => 3,
            WeeklyConfig::KEY_STARTS_AFTER_DEADLINE => true
        ]);

        $scholarship = $this->sm()->publish($template);

        $expectedDeadline = Carbon::now()->startOfWeek()->next(Carbon::SATURDAY)->endOfDay();
        $expectedStart = Carbon::now()->startOfWeek();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(Carbon::SUNDAY, Carbon::instance($scholarship->getStart())->dayOfWeek);
        $this->assertEquals(Carbon::SATURDAY, Carbon::instance($scholarship->getDeadline())->dayOfWeek);
        $this->assertEquals(1, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addWeek();
        $expectedDeadline->addWeek();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(Carbon::SUNDAY, Carbon::instance($scholarship->getStart())->dayOfWeek);
        $this->assertEquals(Carbon::SATURDAY, Carbon::instance($scholarship->getDeadline())->dayOfWeek);
        $this->assertEquals(2, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $expectedStart->addWeek();
        $expectedDeadline->addWeek();

        $this->assertEquals($expectedDeadline, $scholarship->getDeadline());
        $this->assertEquals($expectedStart, $scholarship->getStart());
        $this->assertTrue($scholarship->getDeadline() > $scholarship->getStart());
        $this->assertEquals(Carbon::SUNDAY, Carbon::instance($scholarship->getStart())->dayOfWeek);
        $this->assertEquals(Carbon::SATURDAY, Carbon::instance($scholarship->getDeadline())->dayOfWeek);
        $this->assertEquals(3, $scholarship->getOccurrence());

        $this->sm()->maintain(Carbon::instance($scholarship->getDeadline())->addDay(), [$scholarship->getId()]);
        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));


    }

    /**
     * @throws \Exception
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function test_one_time_scholarship_no_recurring()
    {
        $now = new \DateTime();
        $deadline = Carbon::now()->addDay(20);
        $template = $this->generateScholarshipTemplate();

        $template->setRecurrenceConfig([
            OneTimeConfig::KEY_TYPE => OneTimeConfig::TYPE,
            OneTimeConfig::KEY_START => $now->format('c'),
            OneTimeConfig::KEY_DEADLINE => $deadline->format('c'),
        ]);

        $scholarship = $this->sm()->publish($template);

        Event::fakeFor(function() use ($deadline, $scholarship) {
            $this->sm()->maintain($deadline->copy()->addDay(2), [$scholarship->getId()]);
            Event::assertDispatched(ScholarshipDeadlineEvent::class,
                function(ScholarshipDeadlineEvent $event) use ($scholarship) {
                    return $event->getScholarshipId() === $scholarship->getId();
                }
            );
        }, [
            ScholarshipDeadlineEvent::class
        ]);

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);

        $this->assertNull($scholarshipRepository->findSinglePublishedByTemplate($template));
    }

    /**
     * @throws \Exception
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function test_paused_recurring_mechanism()
    {
        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);

        $template->setPaused(true);
        $this->em()->flush($template);

        $this->sm()->maintain((new \DateTime('+ 5 day')), [$scholarship->getId()]);

        /** @var ScholarshipRepository $repository */
        $repository = $this->em()->getRepository(Scholarship::class);

        $this->assertNull($repository->findSinglePublishedByTemplate($template));
    }

    /**
     * @throws \Exception
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function test_recur_scholarship()
    {
        /** @var Scholarship $new */
        $new = null;
        $template = $this->generateScholarshipTemplate();
        $template2 = $this->generateScholarshipTemplate();

        $scholarship = $this->sm()->publish($template);

        $scholarship2 = $this->sm()->publish($template2);
        $scholarship2->setDeadline(new \DateTime('+ 30 days'));
        $template->setTitle('New title after publish!');

        $this->em()->flush();

        Event::listen(ScholarshipRecurredEvent::class, function(ScholarshipRecurredEvent $event) use (&$new) {
            $new = $this->scholarships->find($event->getScholarshipId());
        });

        $this->sm()->maintain((new \DateTime('+ 5 day')), [$scholarship->getId(), $scholarship2->getId()]);

        $this->assertNotNull($new, 'New recurring scholarship not found');
        $this->assertEquals($scholarship->getTemplate()->getId(), $new->getTemplate()->getId());
        $this->assertTrue($scholarship->isExpired());
        $this->assertFalse($new->isExpired());

        $this->assertEquals('New title after publish!', $new->getTitle());
        $this->assertNotEquals($scholarship->getTitle(), $new->getTitle());

        $this->assertFalse($scholarship2->isExpired(), 'Wrong scholarship unpublished!');
    }
}
