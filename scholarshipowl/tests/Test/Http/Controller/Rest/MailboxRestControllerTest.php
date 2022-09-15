<?php

namespace Test\Http\Controller\Rest;

use App\Services\Mailbox\Email;
use App\Services\Mailbox\MailboxStubDriver;
use App\Testing\TestCase;

class MailboxRestControllerTest extends TestCase
{
    public function testIndex() {
        $this->actingAs($account = $this->generateAccount());
        $mailbox = $account->getUsername();

        $resp = $this->get(route('rest::v1.mailbox.index', ['folder' => 'Inbox']));

        $emails = MailboxStubDriver::generateEmails([$mailbox])['inbox'][$mailbox];
        $decodedResp = $this->decodeResponseJson($resp);

        $this->assertTrue((count($emails) + 1) === count($decodedResp['data'])); // +1 it's a welcome email added
        /** @var Email[] $emails */

        $this->seeJsonStructure($resp, [
            'data' => [
                1 => [
                    'mailbox',
                    'emailId',
                    'scholarshipId',
                    'subject',
                    'description',
                    'body',
                    'sender',
                    'senderName',
                    'recipient',
                    'date',
                    'folder',
                    'isRead',
                    'messageId',
                    'clearBody'
                ]
            ]
        ]);
    }

    public function testUpdateEmail()
    {
        $this->actingAs($account = $this->generateAccount());

        $email = $this->generateEmail($account);
        $mailbox = $account->getUsername();
        $email = MailboxStubDriver::generateEmails([$mailbox])['inbox'][$mailbox][0];

        $resp = $this->put(route('rest::v1.mailbox.update', $email->getEmailId()), ['isRead' => 1]);

        $this->assertTrue($resp->status() === 200);
    }
}