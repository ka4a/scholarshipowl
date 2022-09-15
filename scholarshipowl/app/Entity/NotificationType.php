<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationType
 *
 * @ORM\Table(name="notification_type")
 * @ORM\Entity
 */
class NotificationType
{
    use Dictionary;

    /**
     * New new scholarships become active.
     */
    const NOTIFICATION_NEW_ELIGIBLE_SCHOLARSHIP = 1;

    /**
     * New email in application inbox
     */
    const NOTIFICATION_NEW_EMAIL = 2;

    /**
     * Send notification to user if he has absent for long (defined) period of time.
     * e.g: user is not active for 7 days.
     */
    const NOTIFICATION_LONG_TIME_NOT_SEE = 3;

    /**
     * Send notification to user if he has more scholarships then one week ago.
     */
    const NOTIFICATION_SCHOLARSHIPS_UPDATE = 4;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
}

