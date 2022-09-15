<?php

namespace App\Services\Mailbox;

use App\Contracts\GenericResponseContract;
use App\Extensions\GenericResponse;
use App\Http\Misc\Paginator;
use App\Entity\Account;

/**
 * Supposed to be used with instances running on localhost
 *
 * Class MailboxStubDriver
 * @package App\Services\Mailbox
 */
class MailboxStubDriver implements MailboxDriverInterface
{
    /**
     * @param Email $mail
     */
    public function saveSentEmail(Email $mail)
    {

    }

    /**
     * @param Email $mail
     */
    public function markAsRead(Email $mail)
    {

    }

    /**
     * @param string $mailboxes
     * @return EmailCount[] Indexed by mailbox (username)
     */
    public function countEmails(array $mailboxes): GenericResponseContract
    {
        $result = [];

        foreach ($mailboxes as $mailbox) {
            $result['data'][$mailbox] = EmailCount::populate([
                'inbox' => [
                    'read' => 2,
                    'unread' => 0,
                    'total' => 2
                ],
                'sent' => [
                    'read' => 1,
                    'unread' => 0,
                    'total' => 1
                ]
            ]);
        }

        return GenericResponse::populate($result);
    }

    /**
     * @param string $mailbox
     * @param array $filter
     * @param Paginator|null $paginator
     * @return GenericResponseContract
     */
    public function fetchEmails(string $mailbox, array $filter = [], Paginator $paginator = null): GenericResponseContract
    {
        $emails = self::generateEmails([$mailbox]);
        $data = array_merge(($emails['inbox'][$mailbox] ?? []), ($emails['sent'][$mailbox] ?? []));

        if (!empty($filter)) {
            foreach ($filter as $field => $val) {
                if ($field === 'folder') {
                    $val = ucfirst(strtolower($val));
                }
                $getter = 'get'.implode('', array_map('ucfirst', explode('_', $field)));
                foreach($data as $k => $item) {
                    if ($item->$getter() != $val) {
                        unset($data[$k]);
                    }
                }
            }
        }

        return GenericResponse::populate([
            'data' => array_values($data),
            'meta' => [
                'count' => count($data),
                'start' => $paginator ? $paginator->getOffset() : 0,
                'limit' => $paginator ? $paginator->getLimit() : 1000,
            ],
        ]);
    }

    /**
     * @param array $mailboxes
     * @param array $filter
     * @param bool $group Group results by mailbox
     * @return array Email[] or if group = true then Email[[mailbox] => []]
     */
    public function fetchMultiple(array $mailboxes, array $filter = [], $group = false): array
    {
        $emails = self::generateEmails($mailboxes);
        $folder = strtolower($filter['folder'] ?? 'Inbox');

        if ($group) {
            $result = $emails[$folder];
        } else {
            $result = [];
            foreach ($emails[$folder] as $mailbox => $items) {
                /** @var EmailCount $email */
                foreach ($items as $email) {
                    $result[] = $email;
                }
            }
        }

        return $result;
    }

    /**
     * @param array $mailboxes
     * @return array
     */
    public static function generateEmails(array $mailboxes)
    {
        $result = [];

        foreach ($mailboxes as $mailbox) {
            $result['inbox'][$mailbox][] = Email::populate([
                'email_id' => 1,
                'mailbox' => $mailbox,
                'scholarship_id' => null,
                'folder' => 'Inbox',
                'message_id' => '38a8b4e6b709e216006bde5dff7ec491',
                'subject' => 'This is a test email',
                'body' => '<div>This is a hardcoded text for the email stub number 1</div>',
                'sender' => 'some-scholarship-provider@scholarshipowl.com',
                'recipient' => "{$mailbox}@inbox-test.scholarshipowl.tech",
                'is_read' => 1,
                'date' => '2019-10-18T07:21:13.000Z'
            ]);

            $result['inbox'][$mailbox][] = Email::populate([
                'email_id' => 2,
                'mailbox' => $mailbox,
                'scholarship_id' => null,
                'folder' => 'Inbox',
                'message_id' => 'sd73id8iui39sdfu39df72u444932i67',
                'subject' => 'Yet another test email',
                'body' => '<div>This is a hardcoded text for the email stub number 2</div>',
                'sender' => 'another-scholarship-provider@scholarshipowl.com',
                'recipient' => "{$mailbox}@inbox-test.scholarshipowl.tech",
                'is_read' => 1,
                'date' => '2019-10-19T07:23:05.000Z'
            ]);

            $result['sent'][$mailbox][] = Email::populate([
                'email_id' => 3,
                'mailbox' => $mailbox,
                'scholarship_id' => 1,
                'folder' => 'Sent',
                'message_id' => 'i4dlje4i488u3dr294t7832rhu3u5dn4',
                'subject' => 'Application test email',
                'body' => '<div>This is a hardcoded text for the sent email stub number 1</div>',
                'sender' => "{$mailbox}@inbox-test.scholarshipowl.tech",
                'recipient' => 'some-scholarship-provider@scholarshipowl.com',
                'is_read' => 1,
                'date' => '2019-10-18T07:21:13.000Z'
            ]);
        }

        return $result;
    }
}