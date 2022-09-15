<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * TransactionStatus
 *
 * @ORM\Table(name="transaction_status")
 * @ORM\Entity
 */
class TransactionStatus
{
    use Dictionary;

    const SUCCESS = 1;
    const FAILED = 2;
    const VOID = 3;
    const REFUND = 4;
    const CHARGEBACK = 5;
    const OTHER = 6;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="transaction_status_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;
}

