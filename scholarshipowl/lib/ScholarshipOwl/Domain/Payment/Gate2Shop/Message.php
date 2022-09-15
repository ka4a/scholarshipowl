<?php

namespace ScholarshipOwl\Domain\Payment\Gate2Shop;

use App\Entity\TransactionPaymentType;
use ScholarshipOwl\Data\Entity\Payment\PaymentMethod;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Domain\Payment\AbstractMessage;

class Message extends AbstractMessage
{

    /**
     * Operation execution status. OK or FAIL.
     */
    const GS2_STATUS = 'status';

    /**
     * The status of the PPP transaction – OK means transaction was approved.
     * FAIL means transaction was declined or there was an error.
     */
    const G2S_PPP_STATUS = 'ppp_status';

    const G2S_AMOUNT = 'totalAmount';

    const G2S_CURRENCY = 'currency';

    const G2S_CARD_COMPANY = 'cardCompany';

    const G2S_PROVIDER_TRANSACTION_ID = 'PPP_TransactionID';

    const G2S_BANK_TRANSACTION_ID = 'TransactionID';

    const GS2_RESPONSE_CHECKSUM = 'responsechecksum';

    /**
     * Package ID
     */
    const G2S_PACKAGE_ID = 'customField1';

    /**
     * Account ID
     */
    const G2S_ACCOUNT_ID = 'customField2';

    /**
     * Tracking params
     */
    const G2S_TRACKING_PARAMS_HASH = 'customField3';

    /***
     * @param array $data
     * @param bool|false $isMobile
     * @throws Exception
     */
    public function __construct(array $data, $isMobile = false)
    {
        parent::__construct($data, $isMobile);

        if (!$this->validateChecksum()) {
            throw new Exception("Failed on checksum validation.");
        }
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
     * @return int
     */
    public function getPaymentMethod()
    {
        return PaymentMethod::CREDIT_CARD;
    }

    /**
     * @return int
     */
    public function getPaymentType()
    {
        return TransactionPaymentType::CREDIT_CARD;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return new \DateTime();
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getAmount()
    {
        if ($this->amount === null) {
            if (null === ($this->amount = $this->get(static::G2S_AMOUNT))) {
                throw new Exception(sprintf("Can't extract amount (%s) from input data.", static::G2S_AMOUNT));
            }
        }

        return $this->amount;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getBankTransactionId()
    {
        if (null === ($bankTransactionId = $this->get(static::G2S_BANK_TRANSACTION_ID))) {
            throw new Exception("Can't extract bank transaction ID from input data.");
        }

        return $bankTransactionId;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getProvidedTransactionId()
    {
        if (null === ($providerTransactionId = $this->get(static::G2S_PROVIDER_TRANSACTION_ID))) {
            throw new Exception("Can't extract provider transaction id from input data.");
        }

        return $providerTransactionId;
    }

    /**
     * @return \ScholarshipOwl\Data\Entity\Payment\Package
     * @throws Exception
     */
    public function getPackage()
    {
        if ($this->package === null) {

            if (null === ($packageId = $this->get(static::G2S_PACKAGE_ID))) {
                throw new Exception("Can't extract package id from input data.");
            }

            $packageService = new PackageService();
            if (null === ($this->package = $packageService->getPackage($packageId))) {
                throw new Exception(sprintf("Package with id (%s) not found.", $packageId));
            }

        }

        return $this->package;
    }

    /**
     * @return \ScholarshipOwl\Data\Entity\Account\Account
     * @throws Exception
     */
    public function getAccount()
    {
        if ($this->account === null) {

            if (null === ($accountId = $this->get(static::G2S_ACCOUNT_ID))) {
                throw new Exception("Can't extract account id from input data.");
            }

            $account = \EntityManager::getRepository(\App\Entity\Account::class)
                ->findOneBy(['accountId' => $accountId]);

            if (!$account->getAccountId()) {
                throw new Exception(sprintf("Account with id (%s) not found.", $accountId));
            }

            $this->account = $account;
        }

        return $this->account;
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getTrackingParams()
    {
        if ($this->trackingParams === null) {

            if (null === ($trackingParamsHash = $this->get(static::G2S_TRACKING_PARAMS_HASH))) {
                throw new Exception("Can't extract tracking params hash from input data");
            }

            if (false === ($this->trackingParams = @unserialize(@base64_decode($trackingParamsHash)))) {
                throw new Exception(
                    sprintf("Failed decode tracking params: %s", var_export($trackingParamsHash, true))
                );
            }
        }

        return $this->trackingParams;
    }

    /**
     * @return string
     */
    public function getCreditCardType()
    {
        return $this->get(static::G2S_CARD_COMPANY);
    }

    /**
     * @throws Exception
     */
    public function getExternalSubscriptionId()
    {
        return null;
    }

    /**
     * TODO: Implement regular payment message checksum validation
     * @return bool
     */
    public function validateChecksum()
    {
        return (bool) $this->get(static::GS2_RESPONSE_CHECKSUM);
    }
}
