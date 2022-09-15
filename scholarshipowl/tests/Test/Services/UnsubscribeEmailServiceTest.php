<?php

namespace Test\Services;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;
use App\Services\UnsubscribeEmailService;
use App\Testing\TestCase;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class UnsubscribeEmailServiceTest extends TestCase
{

    /**
     * @var UnsubscribeEmailService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(UnsubscribeEmailService::class);
    }

    public function testUpdateCsvList()
    {
        static::$truncate[] = 'unsubscribed_email';

        $account = $this->generateAccount('test1@email.com');

        $this->get(route('unsubscribe').'?email='.$account->getEmail());

        $this->assertDatabaseHas('unsubscribed_email', [
            'email' => $account->getEmail()
        ]);

        $file = $this->service->updateCsvList();

        $this->assertTrue(strpos($file, UnsubscribeEmailService::UNSUBSCRIBED_FILENAME) !== false);
    }
}
