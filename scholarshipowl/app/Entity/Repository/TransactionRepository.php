<?php namespace App\Entity\Repository;

use App\Entity\PaymentMethod;
use App\Entity\Transaction;
use App\Payment\ITransactionData;

class TransactionRepository extends EntityRepository
{
    /**
     * @param ITransactionData $transactionData
     *
     * @return null|Transaction
     */
    public function findByTransactionData(ITransactionData $transactionData) {
        return $this->findByTransactionIds(
            $transactionData->getPaymentMethod(),
            $transactionData->getBankTransactionId(),
            $transactionData->getProvidedTransactionId()
        );
    }

    /**
     * @param int|PaymentMethod $paymentMethod
     * @param string            $bankTransactionId
     * @param string            $providerTransactionId
     *
     * @return null|Transaction
     */
    public function findByTransactionIds($paymentMethod, $bankTransactionId, $providerTransactionId = null)
    {
        return $this->findOneBy([
            'paymentMethod' => $paymentMethod,
            'bankTransactionId' => $bankTransactionId,
            'providerTransactionId' => $providerTransactionId ?: $bankTransactionId,
        ]);
    }
}
