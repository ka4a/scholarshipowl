<?php

namespace Test\Services;


use App\Extensions\GenericResponse;
use App\Services\Mailbox\Email;
use App\Services\Mailbox\EmailCount;
use App\Services\Mailbox\MailboxAPIDriver;
use App\Services\Mailbox\MailboxStubDriver;
use App\Testing\TestCase;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;
use GuzzleHttp\Psr7\Response;
use Mockery as m;

class MailboxAPIDriverTest extends TestCase
{
    /**
     * @var MailboxAPIDriver
     */
    protected $driver;

    public function setUp(): void
    {
        parent::setUp();

        // no need to truncate DB for these tests
        static::$truncate = [];

        $this->app->singleton(PubSubClient::class, function () {
            $pubSub = m::mock(PubSubClient::class)->shouldReceive('topic')->zeroOrMoreTimes()
                ->andReturnUsing(function () {
                    $topic = m::mock(Topic::class)->shouldReceive('publish')->zeroOrMoreTimes()
                        ->andReturnUsing(function () {
                            return 'publish';
                        })->getMock();

                    return $topic;
                })->getMock();

            return $pubSub;
        });

        $this->driver = app(MailboxAPIDriver::class);
    }


    public function testSaveSentEmail()
    {
        $email = Email::populate([
            'mailbox' => 'test',
            'folder' => 'inbox',
            'subject' => 'Subject 1',
            'body' => 'Body 1',
            'sender' => 'Sender 1',
            'recipient' => 'Recipient 1',
            'message_id' => uniqid('email'),
        ]);

        // just check no errors happened
        $this->driver->saveSentEmail($email);
    }

    public function testMarkAsRead()
    {
        $email = Email::populate([
            'email_id' => 1,
            'mailbox' => 'test',
            'folder' => 'inbox',
            'subject' => 'Subject 1',
            'body' => 'Body 1',
            'sender' => 'Sender 1',
            'recipient' => 'Recipient 1',
            'message_id' => uniqid('email'),
        ]);

        // just check no errors happened
        $this->driver->markAsRead($email);
    }

    public function testCountEmails()
    {
        $mailbox1 = 'test';
        $mailbox2 = 'test2';

        $mailboxData1 = [
            'inbox' => [
                'read' => 2,
                'unread' => 5,
                'total' => 7,
            ],
            'sent' => [
                'read' => 2,
                'unread' => 2,
                'total' => 4,
            ]
        ];

        $mailboxData2 = [
            'inbox' => [
                'read' => 3,
                'unread' => 3,
                'total' => 6,
            ],
            'sent' => [
                'read' => 5,
                'unread' => 0,
                'total' => 5,
            ]
        ];

        $this->setMockHttpClient(
            $this->driver,
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    $mailbox1 => $mailboxData1,
                    $mailbox2 => $mailboxData2
                ]
            ]))
        );

        $result = $this->driver->countEmails([$mailbox1, $mailbox2], [], true);
        $this->assertTrue($result instanceof GenericResponse);

        /** @var EmailCount $emailCount1 */
        $emailCount1 = $result->getData()[$mailbox1];
        $this->assertTrue($emailCount1->getInboxTotal() === $mailboxData1['inbox']['total']);

        /** @var EmailCount $emailCount2 */
        $emailCount2 = $result->getData()[$mailbox2];
        $this->assertTrue($emailCount2->getInboxTotal() === $mailboxData2['inbox']['total']);
    }

    public function testFetchMultiple()
    {
        $mailbox = 'test';
        $mailbox2 = 'test2';
        $this->setMockHttpClient(
            $this->driver,
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    [
                        'mailbox' => $mailbox,
                        'folder' => 'inbox',
                        'subject' => 'Subject 1',
                        'body' => 'Body 1',
                        'sender' => 'Sender 1',
                        'recipient' => 'Recipient 1'
                    ],
                    [
                        'mailbox' => $mailbox2,
                        'folder' => 'inbox',
                        'subject' => 'Subject 2',
                        'body' => 'Body 2',
                        'sender' => 'Sender 2',
                        'recipient' => 'Recipient 2'
                    ]
                ]
            ]))
        );

        $result = $this->driver->fetchMultiple([$mailbox, $mailbox2], [], true);
        $this->assertTrue(array_key_exists($mailbox, $result));
        $this->assertTrue(count($result[$mailbox]) === 1);

        $this->assertTrue(array_key_exists($mailbox2, $result));
        $this->assertTrue(count($result[$mailbox2]) === 1);
    }

    public function testFetchEmails()
    {
        $mailbox = 'test';
        $this->setMockHttpClient(
            $this->driver,
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'data' => [
                    [
                        'mailbox' => $mailbox,
                        'folder' => 'inbox',
                        'subject' => 'Subject 1',
                        'body' => 'Body 1',
                        'sender' => 'Sender 1',
                        'recipient' => 'Recipient 1'
                    ],
                ]
            ]))
        );

        $result = $this->driver->fetchEmails($mailbox);
        $this->assertTrue($result instanceof GenericResponse);
        $this->assertTrue(count($result->getData()) === 1);

        // data suppose to have list of Email instances
        /** @var Email $email */
        $email = $result->getData()[0];
        $this->assertTrue($email instanceof Email);
        $this->assertTrue($email->getSubject() === 'Subject 1');
    }
}
