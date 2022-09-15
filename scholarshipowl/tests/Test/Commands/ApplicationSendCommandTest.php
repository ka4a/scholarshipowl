<?php

namespace Test\Commands;

use App\Entity\AccountLoginToken;
use App\Entity\Scholarship;
use App\Services\Account\AccountLoginTokenService;
use App\Services\ApplicationService;
use App\Testing\TestCase;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ApplicationSendCommandTest extends TestCase
{
    /**
     * @var ApplicationService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(ApplicationService::class);

        static::$truncate = ['eligibility_cache'];
    }

    public function testApplicationSentEmailInUserInbox()
    {
        $emailBody = "Text email body";
        $emailSubject = "Text email subject";

        $this->actingAs($account = $this->generateAccount());
        $scholarship = $this->generateScholarship();
        $scholarship->setApplicationType(Scholarship::APPLICATION_TYPE_EMAIL);
        $scholarship->setEmail('test@example.com');
        $scholarship->setEmailMessage($emailBody);
        $scholarship->setEmailSubject($emailSubject);
        \EntityManager::persist($scholarship);
        \EntityManager::flush($scholarship);

        $application = $this->generateApplication($scholarship, $account);

        $this->service->sendApplication($application);
    }
}
