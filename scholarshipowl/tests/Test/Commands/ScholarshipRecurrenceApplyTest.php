<?php namespace Test\Commands;

use App\Contracts\Recurrable;
use App\Entity\ApplicationStatus;
use App\Entity\Profile;
use App\Services\ApplicationService;
use App\Services\ScholarshipService;
use App\Testing\TestCase;
use Carbon\Carbon;

class ScholarshipRecurrenceApplyTest extends TestCase
{

    /**
     * @var ScholarshipService
     */
    protected $service;

    /**
     * @var ApplicationService
     */
    protected $application;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ScholarshipService::class);
        $this->application = $this->app->make(ApplicationService::class);

    }

    public function testAutomaticApplyToScholarshipsOnDeadline()
    {
        static::$truncate = ['application', 'eligibility_cache', 'scholarship', 'application_text'];

        $account = $this->generateAccount("test2@test.com");
        $this->generateSubscription(null, $account);
        $account->getProfile()->setRecurringApplication(Profile::RECURRENT_APPLY_ON_DEADLINE);

        $scholarship = $this->generateScholarship()
            ->setIsActive(true)
            ->setIsRecurrent(true)
            ->setRecurringType(Recurrable::PERIOD_TYPE_DAY)
            ->setRecurrenceStartNow(true)
            ->setRecurringValue(1)
            ->setStartDate(Carbon::now()->subDay(1))
            ->setExpirationDate(Carbon::now()->subHours(6));

        $this->generateApplicationText($this->generateRequirementText($scholarship), null, 'test text', $account);

        $application = $this->application->applyScholarship($account, $scholarship);
        $newScholarship = $this->service->recur($scholarship);

        $this->assertDatabaseHas('application_text', [
            'account_id' => $account->getAccountId(),
            'scholarship_id' => $newScholarship->getScholarshipId(),
            'requirement_text_id' => $newScholarship->getRequirementTexts()[0]->getId(),
        ]);

        \Artisan::call('scholarships:recurrence-apply');

        $this->assertDatabaseHas('application', [
            'scholarship_id' => $newScholarship->getScholarshipId(),
            'account_id' => $account->getAccountId(),
            'application_status_id' => ApplicationStatus::PENDING,
        ]);
    }
}
