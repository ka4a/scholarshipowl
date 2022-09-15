<?php namespace App\Listeners;
use App\Entities\Organisation;
use App\Entities\User;
use App\Events\ApplicationWinnerFormFilledEvent;
use App\Notifications\WinnerDetailsUpdated;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

/**
 * Subscriber responsible for sending system notifications.
 */
class SystemNotificationsSubscriber implements ShouldQueue
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ChannelManager
     */
    protected $notifications;

    /**
     * SystemNotificationsSubscriber constructor.
     * @param ChannelManager $notifications
     * @param EntityManager $em
     */
    public function __construct(ChannelManager $notifications, EntityManager $em)
    {
        $this->em = $em;
        $this->notifications = $notifications;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function subscribe($dispatcher)
    {
        // $dispatcher->listen(ApplicationWinnerFormFilledEvent::class, static::class.'@onWinnerAdvancedFormFilled');
    }

    /**
     * Notify scholarship manager about winner filled advanced winner form.
     *
     * @param ApplicationWinnerFormFilledEvent $event
     */
    public function onWinnerAdvancedFormFilled(ApplicationWinnerFormFilledEvent $event)
    {
        $email = config('sunrise.positive_rewards.email');
        $notification = new WinnerDetailsUpdated($event->getApplicationWinnerId());
        Notification::route('mail', $email)->notify($notification);
    }
}
