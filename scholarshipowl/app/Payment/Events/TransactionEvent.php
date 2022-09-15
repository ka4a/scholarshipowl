<?php namespace App\Payment\Events;

use App\Entity\Transaction;

class TransactionEvent
{

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * TransactionEvent constructor.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

}
