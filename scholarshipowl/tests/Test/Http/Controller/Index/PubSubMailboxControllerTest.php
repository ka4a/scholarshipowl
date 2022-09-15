<?php

namespace Test\Http\Controller\Index;

use App\Events\Email\NewEmailEvent;
use App\Services\Mailbox\Email;
use App\Services\Mailbox\MailboxStubDriver;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;

class PubSubMailboxControllerTest extends TestCase
{
    use WithoutMiddleware;
    use WithoutEvents;
    use EntityGenerator;

    public function testTriggerInboxEmailEvent()
    {
        $secretKey = config('services.mailbox.api_key');

        $account = $this->generateAccount();
        $mailbox = $account->getUsername();

        /** @var MailboxStubDriver $mailboxDriver */
        $mailboxDriver = app(MailboxStubDriver::class);
        /** @var Email $mail */
        $mail = $mailboxDriver->generateEmails([$mailbox])['inbox'][$mailbox][0];

        $payload = [
            'message' => [
                'attributes' => [
                    'message_id' => $mail->getMessageId(),
                    'mailbox' => $mailbox,
                ],
                'data' => [],
            ]
        ];

        // see the event fired and the message acked
        $resp = $this->postJson(route('pubsub.mailbox.triggerInboxEmailEvent', $secretKey), $payload);
        $fired = $this->getFiredEvents([NewEmailEvent::class]);
        $this->assertTrue(in_array(NewEmailEvent::class, $fired));

        // see the event not fired for the second tome but the message is acked
        $this->firedEvents = [];
        $resp = $this->postJson(route('pubsub.mailbox.triggerInboxEmailEvent', $secretKey), $payload);
        $fired = $this->getFiredEvents([NewEmailEvent::class]);
        $this->assertTrue(!in_array(NewEmailEvent::class, $fired));

        $this->assertTrue($resp->status() === 204);
    }

    public function testDoNotTriggerInboxEmailEventForApplicationInboxEmail()
    {
        $secretKey = config('services.mailbox.api_key');

        $account = $this->generateAccount('test@application-inbox.com');
        $mailbox = $account->getUsername();

        /** @var MailboxStubDriver $mailboxDriver */
        $mailboxDriver = app(MailboxStubDriver::class);
        /** @var Email $mail */
        $mail = $mailboxDriver->generateEmails([$mailbox])['inbox'][$mailbox][0];

        $payload = [
            'message' => [
                'attributes' => [
                    'message_id' => $mail->getMessageId(),
                    'mailbox' => $mailbox,
                ],
                'data' => [],
            ]
        ];

        // see the event fired and the message acked
        $resp = $this->postJson(route('pubsub.mailbox.triggerInboxEmailEvent', $secretKey), $payload);
        $fired = $this->getFiredEvents([NewEmailEvent::class]);
        $this->assertFalse(in_array(NewEmailEvent::class, $fired));
        $this->assertTrue($resp->status() === 204);
    }
}
