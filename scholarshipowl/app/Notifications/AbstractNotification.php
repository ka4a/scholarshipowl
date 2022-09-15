<?php namespace App\Notifications;

use App\Channels\OneSignalChannel;
use App\Contracts\OnesignalNotificationContract;
use App\Entity\Account;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

abstract class AbstractNotification implements OnesignalNotificationContract, ShouldQueue
{
    use Queueable;

    /**
     * The unique identifier for the notification.
     *
     * @var string
     */
    public $id;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->queue = 'notification';
    }

    /**
     * @param string  $content
     * @param Account $account
     *
     * @return string
     */
    public function mapTags(string $content, Account $account) : string
    {
        return map_tags_provider($content, [
            ['account', $account],
        ]);
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        return [
            'notification_type' => $this->getType(),
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return [OneSignalChannel::class];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
