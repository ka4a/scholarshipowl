<?php

namespace ScholarshipOwl\Domain\Repository;

use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Domain\Subscription;
use ScholarshipOwl\Domain\Transaction;

class TransactionRepository
{

    /**
     * @param Subscription $subscription
     * @return Transaction[]
     */
    public function findBySubscription(Subscription $subscription)
    {
        $transactions = array();
        $rawTransactions = \DB::table(IDDL::TABLE_TRANSACTION)
            ->where('subscription_id', $subscription->getSubscriptionId())
            ->get();

        if (is_array($rawTransactions)) {
            foreach ($rawTransactions as $rawTransaction) {
                $transaction = new Transaction((array) $rawTransaction);
                $transactions[$transaction->getTransactionId()] = $transaction;
            }
        }

        return $transactions;
    }

    /**
     * @param string $bankTransactionId
     * @return null|Transaction
     */
    public function findByBankTransactionId($bankTransactionId)
    {
        $transaction = null;

        if ($bankTransactionId) {
            $rawResult = \DB::table(IDDL::TABLE_TRANSACTION)
                ->where('bank_transaction_id', '=', $bankTransactionId)
                ->first();

            $transaction = $rawResult ? new Transaction((array) $rawResult) : null;
        }

        return $transaction;
    }

    /**
     * @param $providerTransactionId
     * @return null|Transaction
     */
    public function findByProviderTransactionId($providerTransactionId)
    {
        $transaction = null;

        if ($providerTransactionId) {
            $rawResult = \DB::table(IDDL::TABLE_TRANSACTION)
                ->where('provider_transaction_id', '=', $providerTransactionId)
                ->first();

            $transaction = $rawResult ? new Transaction((array) $rawResult) : null;
        }

        return $transaction;
    }

}