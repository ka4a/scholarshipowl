<?php namespace App\Notifications;

use App\Entity\Account;
use App\Entity\NotificationType;

class ScholarshipsUpdateNotification extends AbstractNotification
{
    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * Create a new notification instance.
     *
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        parent::__construct();

        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getType() : int
    {
        return NotificationType::NOTIFICATION_SCHOLARSHIPS_UPDATE;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function mapTags(string $content, Account $account) : string
    {
        return map_tags_provider($content, [
            ['account', $account],
            ['amount' => $this->amount],
        ]);
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return parent::getData() + [
                'action'  => 'action.show.scholarships',
            ];
    }
}
