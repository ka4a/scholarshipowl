<?php

namespace App\Services\Mailbox;

use App\Contracts\GenericResponseContract;
use App\Http\Misc\Paginator;

interface MailboxDriverInterface
{
    /**
     * Add new Sent (outgoing) email
     *
     * @param Email $mail
     * @return mixed
     */
    public function saveSentEmail(Email $mail);

    /**
     * Mark email as read
     *
     * @param Email $mail
     * @return mixed
     */
    public function markAsRead(Email $mail);

    /**
     * @param array $mailbox Array of mailboxes (usernames)
     * @return GenericResponseContract
     */
    public function countEmails(array $mailboxes): GenericResponseContract;

    /**
     * @param string $mailbox
     * @param array $filter
     * @param Paginator $paginator
     * @return GenericResponseContract
     */
    public function fetchEmails(string $mailbox, array $filter, Paginator $paginator): GenericResponseContract;

    /**
     * @param array $mailboxes
     * @param array $filter
     * @param bool $group
     * @return array
     */
    public function fetchMultiple(array $mailboxes, array $filter, $group = false): array;
}