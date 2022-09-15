<?php

namespace Test\Commands;

use App\Console\Commands\ApplicationResendFailed;
use App\Entity\AccountLoginToken;
use App\Entity\ApplicationFailedTries;
use App\Entity\ApplicationStatus;
use App\Entity\Counter;
use App\Entity\Scholarship;
use App\Services\Account\AccountLoginTokenService;
use App\Services\ApplicationService;
use App\Testing\TestCase;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Console\Command;

class ApplicationResendFailedTest extends TestCase
{
    /**
     * @var ApplicationService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = app(ApplicationService::class);

        static::$truncate = [
            'application_failed_tries',
            'application',
            'scholarship'
        ];
    }

    public function testResend()
    {

        $senderMock = new ApplicationService\ApplicationSenderOnline();

        //we need to mock httpClient to be sure that response will be not 200
        $this->setMockHttpClient($senderMock,
            new Response(400, ['Content-Type' => 'application/json']));

        $appService = app(ApplicationService::class);
        $appService->setSenderOnline($senderMock);


        //this unit test checking exactly failing on application's sending process
        //so we need to disable logger to prevent adding wrong records in logs files;
        \Log::shouldReceive('error')
            ->andReturn('');

        \Log::shouldReceive('info')
            ->andReturn('');

        \Log::shouldReceive('debug')
            ->andReturn('');



        $account = $this->generateAccount();
        $scholarship = $this->generateScholarship();
        \EntityManager::flush($scholarship);

        $application = $this->generateApplication($scholarship, $account);
        $application->setApplicationStatus(ApplicationStatus::ERROR);
        \EntityManager::flush($application);

        $counter = new \App\Entity\Counter();
        $counter->setName("application");
        $counter->setCount(0);
        \EntityManager::persist($counter);
        \EntityManager::flush($counter);

        \Artisan::call('application:resend');
        $this->assertDatabaseHas('application_failed_tries', [
            'account_id' => $account->getAccountId(),
            'scholarship_id' => $scholarship->getScholarshipId(),
            'tries' => 3
        ]);

        /** @var ApplicationFailedTries $rtyItem */
        $tryItem = \EntityManager::getRepository(ApplicationFailedTries::class)->findOneBy([
            'accountId' => $account->getAccountId(),
            'scholarshipId' => $scholarship->getScholarshipId(),
        ]);

        $tryItem->setLastUpdate(Carbon::now()->subHour(ApplicationResendFailed::RESENT_PERIOD));
        \EntityManager::flush($tryItem);

        \Artisan::call('application:resend');
        $this->assertDatabaseHas('application_failed_tries', [
            'account_id' => $account->getAccountId(),
            'scholarship_id' => $scholarship->getScholarshipId(),
            'tries' => 2
        ]);

        $scholarship->setIsActive(false);
        \EntityManager::flush($scholarship);

        /** @var ApplicationFailedTries $rtyItem */
        $tryItem = \EntityManager::getRepository(ApplicationFailedTries::class)->findOneBy([
            'accountId' => $account->getAccountId(),
            'scholarshipId' => $scholarship->getScholarshipId(),
        ]);
        $tryItem->setLastUpdate(Carbon::now()->subHour(ApplicationResendFailed::RESENT_PERIOD * 2));
        \EntityManager::flush($tryItem);

        \Artisan::call('application:resend');
        $this->assertDatabaseHas('application_failed_tries', [
            'account_id' => $account->getAccountId(),
            'scholarship_id' => $scholarship->getScholarshipId(),
            'tries' => 0
        ]);
    }
}
