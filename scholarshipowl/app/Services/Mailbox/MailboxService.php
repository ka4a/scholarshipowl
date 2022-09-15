<?php

namespace App\Services\Mailbox;

use App\Contracts\GenericResponseContract;
use App\Entity\Account;
use App\Extensions\GenericResponse;
use App\Http\Misc\Paginator;

class MailboxService
{
    /**
     * @var MailboxDriverInterface
     */
    protected $driver;

    /**
     * MailboxService constructor.
     * @param MailboxDriverInterface $driver
     */
    public function __construct(MailboxDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param Email $email
     */
    public  function saveSentEmail(Email $email)
    {
        $this->driver->saveSentEmail($email);
    }

    /**
     * @param array $filter Key-value pairs of fields to filter by (database fields)
     * @param Paginator|null $paginator
     * @return GenericResponseContract
     */
    public function fetchEmails(array $filter = [], Paginator $paginator = null)
    {
        $isInbox = (!isset($filter['folder']) || strtolower($filter['folder']) === 'inbox');

        if ($paginator && $isInbox) {
            $paginatorCorrected = clone $paginator;

            $offset = $paginator->getOffset();
            $limit = max(1, $paginator->getLimit());
            // for the first page we'll add welcome email to the beginning
            if ($isInbox && $paginator->getPage() === 1) {
                $limit--;
                $paginatorCorrected->setLimit($limit);
            } else if ($isInbox) {
                $offset--;
                $paginatorCorrected->setOffset();
            }
        }

        if (isset($filter['mailbox'])) {
            $mailbox = strtolower($filter['mailbox']);
            unset($filter['mailbox']);
        } else {
            /** @var Account $account */
            $account = \Auth::user();
            $mailbox = strtolower($account->getUsername());
        }

        $result = $this->driver->fetchEmails($mailbox, $filter, $paginatorCorrected ?? $paginator);
        $data = $result->getData();

        if ($isInbox && $paginator && $paginator->getPage() === 1) {
            array_unshift($data, $this->emulateWelcomeInboxEmail());
            $meta = $result->getMeta();
            $meta['count']++;
            $meta['start'] = $paginator->getOffset();
            $meta['limit'] = $paginator->getLimit();

            $result->setData($data);
            $result->setMeta($meta);
        }

        return $result;
    }

    /**
     * @param int $emailId
     * @return Email|null
     */
    public function fetchEmailById(int $emailId): ?Email
    {
        /** @var Account $account */
        $account = \Auth::user();
        $resp = $this->driver->fetchEmails(strtolower($account->getUsername()), ['email_id' => $emailId]);
        $data = $resp->getData();

        return $data[0] ?? null;
    }

    /**
     * @param string $mailbox
     * @param string $messageId
     * @return Email|null
     */
    public function fetchEmailByMessageId(string $mailbox, string $messageId): ?Email
    {
        $resp = $this->driver->fetchEmails(strtolower($mailbox), ['message_id' => $messageId]);
        $data = $resp->getData();

        return $data[0] ?? null;
    }

    /**
     * @return GenericResponseContract
     */
    public function countEmails(Account $account = null)
    {
        if (!$account instanceof Account) {
            /** @var Account $account */
            $account = \Auth::user();
        }
        $username = strtolower($account->getUsername());
        $result = $this->driver->countEmails([$username]);

        /** @var EmailCount $data */
        $data = $result->getData()[$username];
        $data->incrementInboxTotal();
        if ($account->getIsReadInbox()) {
            $data->incrementInboxRead();
        } else {
            $data->incrementInboxUnread();
        }

        $result->setData($data);

        return $result;
    }

    /**
     * Returns GenericResponse where data is an array of EmailCount items indexed by mailbox (username)
     *
     * @param array $mailboxes Username list
     * @return GenericResponseContract
     */
    public function countMultiple(array $mailboxes, $skipWelcomeEmail = false)
    {
        $mailboxes = array_map('strtolower', $mailboxes);
        $result = $this->driver->countEmails($mailboxes);
        $data = $result->getData();

        if (!$skipWelcomeEmail) {
            $accounts = \EntityManager::getRepository(Account::class)->findBy(['username' => $mailboxes]);
            $accountsByUsername = [];
            foreach ($accounts as $account) {
                $accountsByUsername[strtolower($account->getUsername())] = $account;
            }

            foreach ($data as $mailbox => & $info) {
                /** @var Account $account */
                $account = $accountsByUsername[$mailbox] ?? null;
                if ($account) {
                    /** @var EmailCount $info */
                    $info = $data[strtolower($account->getUsername())];
                    $info->incrementInboxTotal();
                    if ($account->getIsReadInbox()) {
                        $info->incrementInboxRead();
                    } else {
                        $info->incrementInboxUnread();
                    }
                }
            }
        }

        return $result->setData($data);
    }

    /**
     * @param array $usernameList
     * @return array
     */
    public function getUnreadMessagesList(array $usernameList)
    {
        $usernameList = array_map('strtolower', $usernameList);
        $result = array_fill_keys(array_values($usernameList), '');

        try {
            $welcomeMessages = \EntityManager::createQueryBuilder()
                ->select('LOWER(a.username), a.createdDate')
                ->from(Account::class, 'a', 'a.username')
                ->where('a.username IN (:usernameList)')
                ->andWhere('a.isReadInbox = 0')
                ->setParameter('usernameList', $usernameList)
                ->getQuery()
                ->getArrayResult();

            $result = [];
            foreach ($usernameList as $username) {
                $result[$username] = '';
                if (isset($welcomeMessages[$username])) {
                    $result[$username] .= "DANIEL [CEO] - Welcome - {$welcomeMessages[$username]['createdDate']->format('m/d/Y')}\n";
                }
            }

            $emails = $this->driver->fetchMultiple($usernameList);

            foreach ($emails as $email) {
                $result[$email->getMailbox()] .= "{$email->getSender()} - {$email->getSubject()} - {$email->getDate()->format('m/d/Y')}\n";
            }
        } catch (\Throwable $e) {
            \Log::error($e);
        }

        return $result;
    }

    public function markAsRead(Email $email)
    {
        $this->driver->markAsRead($email);
    }

     /**
     * @return Email
     */
    public function emulateWelcomeInboxEmail(Account $account = null)
    {
        if (!$account instanceof Account) {
            /** @var Account $account */
            $account = \Auth::user();
        }

        return new Email(
            0,
            $account->getUsername(),
            Email::FOLDER_INBOX,
            'Welcome',
            view('emails/user/mailbox_welcome_template', ['username' => $account->getProfile()->getFirstName()])->render(),
            '"Kenny Sandorffy"',
            'Kenny Sandorffy',
            null,
            null,
            $account->getCreatedDate(),
            $account->getIsReadInbox()
        );
    }
}

