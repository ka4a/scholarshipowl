<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddressUpdatedSubscriptions
 *
 * @ORM\Table(name="address_updated_subscriptions")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\UpdatedSubscriptionAddressRepository")
 */
class AddressUpdatedSubscriptions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="subscription_id", type="integer", nullable=false)
     */
    private $subscriptionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_type", type="integer", nullable=false)
     */
    private $paymentType;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * AddressUpdatedSubscriptions constructor.
     *
     * @param int       $subscriptionId
     * @param int       $paymentType
     * @param \DateTime $createdAt
     */
    public function __construct($subscriptionId, $paymentType,
        \DateTime $createdAt
    ) {
        $this->subscriptionId = $subscriptionId;
        $this->paymentType = $paymentType;
        $this->createdAt = $createdAt;
    }
}

