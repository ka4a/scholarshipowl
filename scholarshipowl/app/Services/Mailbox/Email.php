<?php

namespace App\Services\Mailbox;

use App\Contracts\MappingTags;
use App\Entity\Account;
use App\Entity\Scholarship;

class Email implements  \JsonSerializable, MappingTags
{
    const MAILBOX_DOMAIN = 'application-inbox.com';
    const FOLDER_INBOX = 'Inbox';
    const FOLDER_SENT  = 'Sent';

    /**
     * @var integer|null
     */
    private $emailId;


    /**
     * Identifier for user's mailbox. The same as account.username
     *
     * @var string
     */
    private $mailbox;

    /**
     * @var integer|null
     */
    private $scholarshipId;

    /**
     * @var string
     */
    private $folder;

    /**
     * @var string|null
     */
    private $messageId;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var boolean
     */
    private $isRead = false;

    public function __construct(
        ?int $emailId,
        string $mailbox,
        string $folder,
        string $subject,
        string $body,
        string $sender,
        string $recipient,
        string $messageId = null,
        string $scholarshipId = null,
        \DateTime $date = null,
        bool $isRead = null
    ) {
        $this->setEmailId($emailId);
        $this->setMailbox($mailbox);
        $this->setFolder($folder);
        $this->setSubject($subject);
        $this->setBody($body);
        $this->setSender($sender);
        $this->setRecipient($recipient);
        $this->setMessageId($messageId);
        $this->setScholarshipId($scholarshipId);
        $this->setDate($date ?: new \DateTime());
        $this->setIsRead((bool)$isRead);
    }

    /**
     * @param array $data
     * @return Email
     */
    public static function populate(array $data)
    {
        $model = new self(
            $data['email_id'] ?? ($data['emailId'] ?? null),
            $data['mailbox'],
            $data['folder'],
            $data['subject'],
            $data['body'],
            $data['sender'],
            $data['recipient'],
            $data['message_id'] ?? ($data['messageId'] ?? null),
            $data['scholarship_id'] ?? ($data['scholarshipId'] ?? null),
            isset($data['date']) ?
                ($data['date'] instanceof \DateTime ? $data['date'] : new \DateTime($data['date'])) : null,
            (bool)($data['is_read'] ?? ($data['isRead'] ?? new \DateTime()))
        );

        return $model;
    }

    /**
     * @return string
     */
    public function getMailbox()
    {
        return strtolower($this->mailbox);
    }

    /**
     * @param string $val
     * @return string
     */
    public function setMailbox(string $val)
    {
        return $this->mailbox = strtolower($val);
    }

    /**
     * @return integer
     */
    public function getEmailId()
    {
        return $this->emailId;
    }


    /**
     * @param int|null $val
     * @return $this
     */
    public function setEmailId(?int $val): self
    {
        $this->emailId = $val;

        return $this;
    }

    /**
     * @return object
     */
    public function getAccount()
    {
        return \EntityManager::getRepository(Account::class)->findOneBy(['username' => $this->getMailbox()]);
    }

    /**
     * @param integer $val
     * @return Email
     */
    public function setScholarshipId(?int $val): self
    {
        $this->scholarshipId = $val;

        return $this;
    }

    /**
     * @return integer
     */
    public function getScholarshipId()
    {
        return $this->scholarshipId;
    }

    /**
     * @return Scholarship|null
     */
    public function getScholarship()
    {
        if ($this->scholarshipId()) {
            return \EntityManager::getRepository(Scholarship::class)
                ->findOneBy(['scholarshipId' => $this->scholarshipId()]);
        }

        return null;
    }

    /**
     * @param string $val
     * @return Email
     */
    public function setFolder(string $val): self
    {
        $this->folder = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param string $val
     * @return Email
     */
    public function setMessageId($val): self
    {
        $this->messageId = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param string $subject
     * @return Email
     */
    public function setSubject(string $val): self
    {
        $this->subject = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $body
     * @return Email
     */
    public function setBody(string $val): self
    {
        $this->body = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getClearBody()
    {
        $body = $this->body;
        $body = preg_replace("|<style\b[^>]*>(.*?)</style>|s", "", $body);
        $body = preg_replace("/\*\|SUBJECT\|\*/s", "", $body);
        $body = strip_tags($body);
        $body = preg_replace('/\s+/S', " ", $body);
        $body = trim($body);

        return $body;
    }

    /**
     * @param string $sender
     * @return Email
     */
    public function setSender(string $val): self
    {
        $this->sender = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $val
     * @return Email
     */
    public function setRecipient(string $val): self
    {
        $this->recipient = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param \DateTime $date
     * @return Email
     */
    public function setDate(\DateTime $val): self
    {
        $this->date = $val;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param boolean $isRead
     * @return Email
     */
    public function setIsRead(bool $val): self
    {
        $this->isRead = $val;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return substr($this->getClearBody(), 0, 80) . '...';
    }

    /**
     * @return array
     */
    public function tags() : array
    {
        return [
            'subject' => $this->getSubject(),
            'sender' => $this->getSender(),
        ];
    }

    /**
     * Called on json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $body = mb_convert_encoding($this->getBody(), 'UTF-8', 'UTF-8');

        preg_match_all('/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+/', $this->getSender(), $matches);
        $senderEmail = $matches[0][0] ?? $this->getSender();

        preg_match_all("/^(.*?)</", $this->getSender(), $matches);
        $senderName = $matches[1][0] ?? $this->getSender();
        $senderName = str_replace('"', '', $senderName);
        $senderName = trim($senderName);

        return [
            'mailbox' => $this->getMailbox(),
            'emailId' => $this->getEmailId(),
            'scholarshipId' => $this->getScholarshipId(),
            'subject' => $this->getSubject(),
            'description' => mb_convert_encoding($this->getDescription(), 'UTF-8', 'UTF-8'),
            'body' => $body,
            'sender' => $senderEmail,
            'senderName' => $senderName,
            'recipient' => $this->getRecipient(),
            'date' => $this->getDate(),
            'folder' => $this->getFolder(),
            'isRead' => $this->getIsRead(),
            'messageId' => $this->getMessageId(),
            'clearBody' => $this->getClearBody()
        ];
    }
}

