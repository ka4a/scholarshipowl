<?php

namespace ScholarshipOwl\Domain;

use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Entity\Payment\Subscription as SubscriptionEntity;

use ScholarshipOwl\Domain\Repository\TransactionRepository;

class Subscription extends SubscriptionEntity
{

    protected $transactions = null;

    /**
     * @param null|SubscriptionEntity $row
     */
    public function __construct($row = null)
    {
        if ($row instanceof SubscriptionEntity) {
            $rawData = $row->getRawData();
            $row = empty($rawData) ? $row->toArray() : $row->getRawData();
        }

        parent::__construct($row);
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions()
    {
        if ($this->transactions === null) {
            $this->transactions = (new TransactionRepository())->findBySubscription($this);
        }

        return $this->transactions;
    }

    /**
     * Update subscription renewal date after payment
     *
     * @param \DateTime $paymentDate
     *
     * @return bool
     */
    public function renewSubscriptionAfterPayment(\DateTime $paymentDate = null)
    {
        $result = false;

        if ($package = $this->getPackage()) {
            list(, $newRenewalDate) = static::countSubscriptionEndDate($package, $paymentDate);

            if ($newRenewalDate && $newRenewalDate !== $this->getRenewalDate()) {
                $result = \DB::table(IDDL::TABLE_SUBSCRIPTION)
                    ->where('subscription_id', '=', $this->getSubscriptionId())
                    ->update(array(
                        'subscription_status_id' => SubscriptionStatus::ACTIVE,
                        'renewal_date' => $newRenewalDate,
                    ));
            }
        }

        return (bool) $result;
    }

    /**
     * @param int  $increment
     *
     * @return int
     */
    public function incrementSubscriptionCount($increment = 1)
    {
        $this->setRecurrentCount(($this->getRecurrentCount() ?: 0) + $increment);

        return \DB::table(IDDL::TABLE_SUBSCRIPTION)->where('subscription_id', '=', $this->getSubscriptionId())
            ->update(['recurrent_count' => $this->getRecurrentCount()]);
    }

    /**
     * //TODO: Move this method to package
     *
     * Return end and renewal date after counting them.
     * @param Package $package
     * @param null $now
     * @return array
     */
    static public function countSubscriptionEndDate(Package $package, $now = null)
    {
        $endDate = "0000-00-00 00:00:00";
        $renewalDate = "0000-00-00 00:00:00";

        if ($now instanceof \DateTime) {
            $now = $now->format("Y-m-d H:i:s");
        }

        if ($now === null) {
            $now = date("Y-m-d H:i:s");
        }

        switch ($package->getExpirationType()) {

            case Package::EXPIRATION_TYPE_DATE:
                $endDate = $package->getExpirationDate();
                break;

            case Package::EXPIRATION_TYPE_NO_EXPIRY:
                // If No Expiry (End = NOW + 20 Years)
                $endDate = date("Y-m-d H:i:s", strtotime("+" . 20 . " year", strtotime($now)));
                break;

            case Package::EXPIRATION_TYPE_PERIOD:
                $periodValue = $package->getExpirationPeriodValue();

                if($package->getExpirationPeriodType() == Package::EXPIRATION_PERIOD_TYPE_DAY) {
                    $endDate = date("Y-m-d H:i:s", strtotime("+" . $periodValue . " day", strtotime($now)));
                }
                if($package->getExpirationPeriodType() == Package::EXPIRATION_PERIOD_TYPE_WEEK) {
                    $endDate = date("Y-m-d H:i:s", strtotime("+" . $periodValue . " week", strtotime($now)));
                }
                else if($package->getExpirationPeriodType() == Package::EXPIRATION_PERIOD_TYPE_MONTH) {
                    $endDate = date("Y-m-d H:i:s", strtotime("+" . $periodValue . " month", strtotime($now)));
                }
                else if($package->getExpirationPeriodType() == Package::EXPIRATION_PERIOD_TYPE_YEAR) {
                    $endDate = date("Y-m-d H:i:s", strtotime("+" . $periodValue . " year", strtotime($now)));
                }
                break;

            case Package::EXPIRATION_TYPE_RECURRENT:
                $expirationPeriodType = $package->getExpirationPeriodType();
                $expirationPeriodValue = $package->getExpirationPeriodValue();

                if($expirationPeriodType == Package::EXPIRATION_PERIOD_TYPE_DAY) {
                    $renewalDate = date("Y-m-d H:i:s", strtotime("+" . $expirationPeriodValue . " day", strtotime($now)));
                }
                if($expirationPeriodType == Package::EXPIRATION_PERIOD_TYPE_WEEK) {
                    $renewalDate = date("Y-m-d H:i:s", strtotime("+" . $expirationPeriodValue . " week", strtotime($now)));
                }
                else if($expirationPeriodType == Package::EXPIRATION_PERIOD_TYPE_MONTH) {
                    $renewalDate = date("Y-m-d H:i:s", strtotime("+" . $expirationPeriodValue . " month", strtotime($now)));
                }
                else if($expirationPeriodType == Package::EXPIRATION_PERIOD_TYPE_YEAR) {
                    $renewalDate = date("Y-m-d H:i:s", strtotime("+" . $expirationPeriodValue . " year", strtotime($now)));
                }

                break;

            default:
                break;

        }

        return array($endDate, $renewalDate);
    }

    /**
     * @return bool
     */
    public function isRecurrent()
    {
        return $this->getExpirationType() === Package::EXPIRATION_TYPE_RECURRENT;
    }

}
