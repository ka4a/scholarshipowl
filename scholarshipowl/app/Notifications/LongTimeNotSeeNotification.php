<?php namespace App\Notifications;

use App\Entity\NotificationType;

class LongTimeNotSeeNotification extends AbstractNotification
{
    /**
     * @return int
     */
    public function getType() : int
    {
        return NotificationType::NOTIFICATION_LONG_TIME_NOT_SEE;
    }
}
