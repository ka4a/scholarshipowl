<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * SubscriptionStatus
 *
 * @ORM\Table(name="subscription_status")
 * @ORM\Entity
 */
class SubscriptionStatus
{
    use Dictionary;

    const ACTIVE = 1;
    const EXPIRED = 2;
    const CANCELED = 3;
    const SUSPENDED = 4;

    /**
     * @var int
     *
     * @ORM\Column(name="subscription_status_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;
}

