<?php

namespace App\Services\Mailbox;

class EmailCount implements  \JsonSerializable
{
    /**
     * @var integer
     */
    private $inboxRead;

    /**
     * @var integer
     */
    private $inboxUnread;

    /**
     * @var integer
     */
    private $inboxTotal;

    /**
     * @var integer
     */
    private $sentRead;

    /**
     * @var integer
     */
    private $sentUnread;

    /**
     * @var integer
     */
    private $sentTotal;



    public function __construct(
        int $inboxRead,
        int $inboxUnread,
        int $inboxTotal,
        int $sentRead,
        int $sentUnread,
        int $sentTotal
    ) {
        $this->setInboxRead($inboxRead);
        $this->setInboxUnread($inboxUnread);
        $this->setInboxTotal($inboxTotal);
        $this->setSentRead($sentRead);
        $this->setSentUnread($sentUnread);
        $this->setSentTotal($sentTotal);
    }

    /**
     * @param array $data
     * @return self
     */
    public static function populate(array $data)
    {
        $model = new self(
            $data['inbox']['read'] ?? ($data['inbox']['read'] ?? 0),
            $data['inbox']['unread'] ?? ($data['inbox']['unread'] ?? 0),
            $data['inbox']['total'] ?? ($data['inbox']['total'] ?? 0),
            $data['sent']['read'] ?? ($data['inbox']['read'] ?? 0),
            $data['sent']['unread'] ?? ($data['inbox']['unread'] ?? 0),
            $data['sent']['total'] ?? ($data['inbox']['total'] ?? 0)
        );

        return $model;
    }

    /**
     * @return string
     */
    public function getInboxRead()
    {
        return $this->inboxRead;
    }

    /**
     * @param int $val
     * @return self
     */
    public function setInboxRead(int $val)
    {
        $this->inboxRead = $val;

        return $this;
    }

    /**
     * @return $this
     */
    public function incrementInboxRead()
    {
        $this->inboxRead++;

        return $this;
    }

    /**
     * @return string
     */
    public function getInboxUnread()
    {
        return $this->inboxUnread;
    }

    /**
     * @param int $val
     * @return self
     */
    public function setInboxUnread(int $val)
    {
        $this->inboxUnread = $val;

        return $this;
    }

    /**
     * @return $this
     */
    public function incrementInboxUnread()
    {
        $this->inboxUnread++;

        return $this;
    }

    /**
     * @return string
     */
    public function getInboxTotal()
    {
        return $this->inboxTotal;
    }

    /**
     * @param int $val
     * @return self
     */
    public function setInboxTotal(int $val)
    {
        $this->inboxTotal = $val;

        return $this;
    }

    /**
     * @return $this
     */
    public function incrementInboxTotal()
    {
        $this->inboxTotal++;

        return $this;
    }

    /**
     * @return string
     */
    public function getSentRead()
    {
        return $this->sentRead;
    }

    /**
     * @param int $val
     * @return self
     */
    public function setSentRead(int $val)
    {
        $this->sentRead = $val;

        return $this;
    }

    /**
     * @return $this
     */
    public function incrementSentRead()
    {
        $this->sentRead++;

        return $this;
    }

    /**
     * @return string
     */
    public function getSentUnread()
    {
        return $this->sentUnread;
    }

    /**
     * @param int $val
     * @return self
     */
    public function setSentUnread(int $val)
    {
        $this->sentUnread = $val;

        return $this;
    }

    /**
     * @return $this
     */
    public function incrementSentUnread()
    {
        $this->isentUnread++;

        return $this;
    }

    /**
     * @return string
     */
    public function getSentTotal()
    {
        return $this->sentTotal;
    }

    /**
     * @param int $val
     * @return self
     */
    public function setSentTotal(int $val)
    {
        $this->sentTotal = $val;

        return $this;
    }

    /**
     * @return $this
     */
    public function incrementSentTotal()
    {
        $this->sentTotal++;

        return $this;
    }

    /**
     * Called on json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'inbox' => [
                'read' => $this->getInboxRead(),
                'unread' => $this->getInboxUnread(),
                'total' => $this->getInboxTotal(),
            ],
            'sent' => [
                'read' => $this->getSentRead(),
                'unread' => $this->getSentUnread(),
                'total' => $this->getSentTotal(),
            ],
        ];
    }
}

