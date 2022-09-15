<?php

namespace ScholarshipOwl\Domain;

use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Data\Service\Payment\TransactionService;
use ScholarshipOwl\Data\Entity\Payment\Subscription as SubscriptionEntity;
use ScholarshipOwl\Data\Entity\Payment\Transaction as TransactionEntity;

class Transaction extends TransactionEntity
{

    /**
     * @param null|TransactionEntity $row
     */
    public function __construct($row = null)
    {
        if ($row instanceof TransactionEntity) {
            $rawData = $row->getRawData();
            $row = empty($rawData) ? $row->toArray() : $row->getRawData();
        }

        parent::__construct($row);
    }

    /**
     * @param $number
     *
     * @return int
     */
    public function updateRecurrentNumber($number)
    {
        $this->setRecurrentNumber($number);

        return \DB::table(IDDL::TABLE_TRANSACTION)->where('transaction_id', '=', $this->getTransactionId())
            ->update(['recurrent_number' => $this->getRecurrentNumber()]);
    }

}
