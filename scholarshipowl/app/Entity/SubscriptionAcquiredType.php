<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * SubscriptionAcquiredType
 *
 * @ORM\Table(name="subscription_acquired_type")
 * @ORM\Entity
 */
class SubscriptionAcquiredType
{
    use Dictionary;

    const PURCHASED = 1;
    const WELCOME = 2;
    const REFERRAL = 3;
    const REFERRED = 4;
    const MISSION = 5;
    const FREEBIE = 6;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="subscription_acquired_type_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=63, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;
}

