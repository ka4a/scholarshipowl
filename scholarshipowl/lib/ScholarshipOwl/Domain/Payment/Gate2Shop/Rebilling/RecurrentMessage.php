<?php

namespace ScholarshipOwl\Domain\Payment\Gate2Shop\Rebilling;

use ScholarshipOwl\Domain\Payment\Gate2Shop\Helper;

class RecurrentMessage extends InitialMessage
{


    /**
     * Recurring charge amount.
     */
    const G2S_AMOUNT = 'amount';

    /**
     * The state of the subscription (membership) which is taken, after the transaction has been processed.
     * Can be ACTIVE, INACTIVE, CANCELED, WAITING_CANCELATION, WAITING_DISABLE
     */
    const G2S_MEMBERSHIP_STATE = 'membershipState';

    /**
     * Transaction ID of the recurring charge.
     */
    const G2S_GATEWAY_TRANSACTION_ID = 'gatewayTransactionId';
    const G2S_BANK_TRANSACTION_ID = 'gatewayTransactionId';

    /**
     * Transaction status of the recurring charge.
     * Possible values are: APPROVED, DECLINED and ERROR
     */
    const G2S_GATEWAY_TRANSACTION_STATUS = 'gatewayTransactionStatus';

    /**
     * Is transaction success
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->get(static::G2S_GATEWAY_TRANSACTION_STATUS) === 'APPROVED';
    }

    /**
     * @return \ScholarshipOwl\Data\Entity\Payment\Package
     * @throws \ScholarshipOwl\Domain\Payment\Gate2Shop\Exception
     */
    public function getPackage()
    {
        if ($this->package === null && ($subscription = $this->getSubscription())) {
            $this->setData(static::G2S_PACKAGE_ID, $subscription->getPackage()->getPackageId());
        }

        return parent::getPackage();
    }

    /**
     * @return \ScholarshipOwl\Data\Entity\Account\Account
     * @throws \ScholarshipOwl\Domain\Payment\Gate2Shop\Exception
     */
    public function getAccount()
    {
        if ($this->account === null && ($subscription = $this->getSubscription())) {
            $this->setData(static::G2S_ACCOUNT_ID, $subscription->getAccount()->getAccountId());
        }

        return parent::getAccount();
    }

    /**
     * @return mixed
     * @throws \ScholarshipOwl\Domain\Payment\Gate2Shop\Exception
     */
    public function getProvidedTransactionId()
    {
        return $this->getBankTransactionId();
    }

    /**
     * @return bool
     */
    public function validateChecksum()
    {
        return $this->get(static::GS2_RESPONSE_CHECKSUM) === Helper::buildChecksum(array(
            $this->get(static::G2S_MEMBERSHIP_ID),
            $this->get(static::G2S_GATEWAY_TRANSACTION_ID),
            $this->get(static::G2S_GATEWAY_TRANSACTION_STATUS),
            $this->get(static::G2S_AMOUNT),
            $this->get(static::G2S_CURRENCY),
        ));
    }

}