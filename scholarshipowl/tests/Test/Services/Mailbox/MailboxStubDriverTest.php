<?php

namespace Test\Services;


use App\Extensions\GenericResponse;
use App\Services\Mailbox\MailboxStubDriver;
use App\Testing\TestCase;

class MailboxStubDriverTest extends TestCase
{
    /**
     * @var MailboxStubDriver
     */
    protected $driver;

    public function setUp(): void
    {
        parent::setUp();

        // no need to truncate DB for these tests
        static::$truncate = [];

        $this->driver = app(MailboxStubDriver::class);
    }

    public function testGenerateEmails()
    {
        $mailbox = 'test';
        $result = $this->driver->generateEmails([$mailbox]);

        $this->assertTrue(isset($result['inbox'][$mailbox]));
        $this->assertTrue(isset($result['sent'][$mailbox]));

        $mailbox2 = 'test2';
        $result = $this->driver->generateEmails([$mailbox, $mailbox2]);
        $this->assertTrue(isset($result['inbox'][$mailbox]));
        $this->assertTrue(isset($result['sent'][$mailbox]));
        $this->assertTrue(isset($result['inbox'][$mailbox2]));
        $this->assertTrue(isset($result['sent'][$mailbox2]));
    }

    public function testFetchMultiple()
    {
        $mailbox = 'test';
        $mailbox2 = 'test2';
        $result = $this->driver->fetchMultiple([$mailbox, $mailbox2], [], true);
        $this->assertTrue(isset($result[$mailbox]));
        $this->assertTrue(isset($result[$mailbox2]));

        $resultFiltered = $this->driver->fetchMultiple([$mailbox, $mailbox2], ['folder' => 'sent'], true);
        $this->assertTrue(count($result[$mailbox]) !== count($resultFiltered[$mailbox]));
        $this->assertTrue(count($result[$mailbox2]) !== count($resultFiltered[$mailbox2]));
    }

    public function testFetchEmails()
    {
        $mailbox = 'test';

        $result = $this->driver->fetchEmails($mailbox);
        $this->assertTrue($result instanceof GenericResponse);
        $meta = $result->getMeta();

        $resultFiltered = $this->driver->fetchEmails($mailbox, ['folder' => 'sent']);
        $this->assertTrue($resultFiltered instanceof GenericResponse);
        $metaFiltered = $resultFiltered->getMeta();
        $this->assertTrue($metaFiltered['count'] < $meta['count']);
    }

}
