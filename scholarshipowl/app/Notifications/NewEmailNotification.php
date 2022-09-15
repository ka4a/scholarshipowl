<?php namespace App\Notifications;

use App\Entity\Account;
use App\Services\Mailbox\Email;
use App\Entity\NotificationType;

class NewEmailNotification extends AbstractNotification
{
    /**
     * @var Email
     */
    protected $email;

    /**
     * NewEmailNotification constructor.
     *
     * @param Email $email
     */
    public function __construct(Email $email)
    {
        parent::__construct();

        $this->email = $email;
    }

    /**
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return NotificationType::NOTIFICATION_NEW_EMAIL;
    }

    /**
     * @param string $content
     * @param Account $account
     *
     * @return string
     */
    public function mapTags(string $content, Account $account): string
    {
        return map_tags_provider($content, [
            ['account', $account],
            ['email', $this->getEmail()],
        ]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return parent::getData() + [
                'emailId' => $this->email->getEmailId(),
                'action'  => 'action.show.mailbox',
            ];
    }
}
