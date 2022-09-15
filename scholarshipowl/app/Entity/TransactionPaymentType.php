<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * TransactionPaymentType
 *
 * @ORM\Table(name="transaction_payment_type")
 * @ORM\Entity
 */
class TransactionPaymentType
{
    use Dictionary;

    const CREDIT_CARD = 1;
    const PAYPAL = 2;

    public static $paymentTypes = [
        self::CREDIT_CARD => 'Credit Card',
        self::PAYPAL => 'PayPal',
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_payment_type_id", type="integer", nullable=false)
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

