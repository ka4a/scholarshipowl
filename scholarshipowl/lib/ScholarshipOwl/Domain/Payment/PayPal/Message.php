<?php

namespace ScholarshipOwl\Domain\Payment\PayPal;

use App\Entity\TransactionPaymentType;
use ScholarshipOwl\Data\Entity\Payment\PaymentMethod;
use ScholarshipOwl\Data\Service\Payment\PackageService;

use ScholarshipOwl\Domain\Payment\AbstractMessage;

use \Mdb\PayPal\Ipn\Message as IpnMessage;

/**
 * Class Message
 * @package ScholarshipOwl\Domain\Payment\PayPal
 */
class Message extends AbstractMessage
{

    /**
     * Start date or cancellation date depending on whether transaction is subscr_signup or subscr_cancel.
     * Format: HH:MM:SS DD Mmm YY, YYYY PST
     */
    const PP_SUBSCRIPTION_DATE = 'subscr_date';

    /**
     * For Mass Payments, the first IPN is the date/time when the record set is processed.
     * Format: HH:MM:SS DD Mmm YYYY PST
     * Length: 28 characters
     */
    const PP_PAYMENT_DATE = 'payment_date';

    /**
     * PayPal date format
     */
    const PP_DATE_FORMAT = 'HH:MM:SS DD Mmm YYYY PST';

    /**
     * In the case of a refund, reversal, or canceled reversal, this variable contains the txn_id of the original
     * transaction, while txn_id contains a new ID for the new transaction.
     * Length: 19 characters
     */
    const PP_PARENT_TXN_ID = 'parent_txn_id';

    /**
     * @var IpnMessage
     */
    protected $message;

    /**
     * @var array
     */
    protected $trackingParams;

    /**
     * @param array|IpnMessage $data
     * @param bool $isMobile
     * @param string $source
     * @throws \Exception
     */
    public function __construct($data, $isMobile = false, $source = null)
    {
        parent::__construct(array(), $isMobile);
        $this->message = ($data instanceof IpnMessage) ? $data : new IpnMessage($data);
        $this->setSource($source);
    }

    /**
     * Parse custom params sent to paypal in next format:
     *   packageId_accountId_params
     *
     * @param string $custom
     * @return array (packageId, accountId, params)
     */
    protected function extractFromCustom($custom)
    {
        $packageId = null;
        $accountId = null;
        $params = null;

        if ($customArray = explode('_', $custom)) {
            if (count($customArray) === 3) {
                $packageId = $customArray[0];
                $accountId = $customArray[1];
                $params = $customArray[2];
            }
        }

        return array($packageId, $accountId, $params);
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public function get($key = null)
    {
        $data = $this->message->get($key);

        return !empty($data) ? $data : null;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->message->getAll();
    }

    /**
     * @return \ScholarshipOwl\Data\Entity\Payment\Package
     * @throws \Exception
     */
    public function getPackage()
    {
        if ($this->package === null) {
            $packageId = $this->get('package_id') ?: $this->extractFromCustom($this->get('custom'))[0];

            $packageService = new PackageService();
            if (null === ($this->package = $packageService->getPackage($packageId))) {
                throw new \Exception(sprintf("Wrong package id provided: %s", $packageId));
            }
        }

        return $this->package;
    }

    /**
     * @return \ScholarshipOwl\Data\Entity\Account\Account
     * @throws \Exception
     */
    public function getAccount()
    {
        if ($this->account === null) {
            $accountId = $this->get('account_id') ?: $this->extractFromCustom($this->get('custom'))[1];

            if (null === ($this->account = \EntityManager::getRepository(\App\Entity\Account::class)
                    ->findOneBy(['accountId' => $accountId]))) {
                throw new \Exception(sprintf("Wrong account id provided: %s", $accountId));
            }
        }

        return $this->account;
    }

    /**
     * @return int
     */
    public function getPaymentMethod()
    {
        return PaymentMethod::PAYPAL;
    }

    /**
     * @return int
     */
    public function getPaymentType()
    {
        return TransactionPaymentType::PAYPAL;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        if ($this->amount === null) {
            $bankTransactionId = $this->getBankTransactionId();
            $this->setAmount(
                empty($bankTransactionId) ?
                    $this->get('amount3') :
                    $this->get('mc_gross')
            );
        }

        return $this->amount;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTrackingParams()
    {
        if ($this->trackingParams === null) {
            $rawParams = $this->get('tracking_params') ?: $this->extractFromCustom($this->get('custom'))[2];
            $params = @unserialize(@base64_decode($rawParams)) ?: array();
            if (is_array($params)) {
                $this->setTrackingParams($params);
            } else {
                throw new \Exception(sprintf("Wrong tracking params provided: %s", var_export($params, true)));
            }
        }

        return $this->trackingParams;
    }

    /**
     * @return mixed
     */
    public function getProvidedTransactionId()
    {
        return $this->getBankTransactionId();
    }

    /**
     * @return mixed
     */
    public function getBankTransactionId()
    {
        return $this->get('txn_id');
    }

    /**
     * @return mixed
     */
    public function getParentBankTransactionId()
    {
        return $this->get(self::PP_PARENT_TXN_ID);
    }

    /**
     * @return mixed
     */
    public function getExternalSubscriptionId()
    {
        return $this->get('subscr_id');
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        $date = $this->get(self::PP_SUBSCRIPTION_DATE) ?
            $this->get(self::PP_SUBSCRIPTION_DATE) :
            $this->get(self::PP_PAYMENT_DATE);

        return new \DateTime($date);
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->get('txn_type');
    }
}
