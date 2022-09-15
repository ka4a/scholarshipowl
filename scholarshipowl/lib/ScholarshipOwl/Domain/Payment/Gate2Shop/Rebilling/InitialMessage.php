<?php

namespace ScholarshipOwl\Domain\Payment\Gate2Shop\Rebilling;

use ScholarshipOwl\Domain\Payment\Gate2Shop\Helper;
use ScholarshipOwl\Domain\Payment\Gate2Shop\Message;

class InitialMessage extends Message
{

    /**
     * Rebilling amount dynamic rebilling parameter if supplied.
     */
    const G2S_AMOUNT = 'initial_amount';

    const G2S_REBILLING_AMOUNT = 'rebilling_initial_amount';

    /**
     * The ID of recurrent membership that was created.
     */
    const G2S_MEMBERSHIP_ID = 'membershipId';

    /**
     * Optional. A unique 64 bits number, which identifies the fiscal transaction in the G2S payment gateway.
     * If no fiscal transaction actually occurred the ppp_status will be FAIL and this will be empty.
     */
    const G2S_REBILLING_INITIAL_TRANSACTION_ID = 'rebilling_initial_transaction_id';
    const G2S_BANK_TRANSACTION_ID = 'rebilling_initial_transaction_id';

    /**
     * A message returned by the Rebilling web service. Check in case of error
     */
    const GS2_REBILLING_MESSAGE = 'rebillingMessage';

    /**
     * @return float
     * @throws \ScholarshipOwl\Domain\Payment\Gate2Shop\Exception
     */
    public function getAmount()
    {
        if ($this->amount === null) {
            $amount = $this->get(static::G2S_REBILLING_AMOUNT);

            $this->amount = !empty($amount) ? $amount : null;
        }

        return parent::getAmount();
    }

    /**
     * @return null|string
     */
    public function getExternalSubscriptionId()
    {
        $externalId = $this->get(static::G2S_MEMBERSHIP_ID);

        return !empty($externalId) ? $externalId : null;
    }

    /**
     * Is transaction success
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->get(static::G2S_PPP_STATUS) === 'OK';
    }

    /**
     * @return bool
     */
    public function validateChecksum()
    {
        return $this->get(static::GS2_RESPONSE_CHECKSUM) === Helper::buildChecksum(array(
            Helper::getSecretKey(),
            $this->get(static::G2S_MEMBERSHIP_ID),
            $this->get(static::GS2_STATUS),
        ));
    }
}