<?php namespace App\Entity;

use App\Contracts\MappingTags;
use App\Contracts\Recurrable;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use ScholarshipOwl\Data\DateHelper;

use Doctrine\ORM\Mapping AS ORM;

/**
 * Class Subscription
 * @package App\Entities
 * @ORM\Entity(repositoryClass="App\Entity\Repository\SubscriptionRepository")
 * @ORM\Table(name="subscription")
 * @ORM\HasLifecycleCallbacks
 */
class Subscription implements MappingTags
{
    const CACHE_KEY_ACCOUNT_SUBSCRIPTION = 'account_subscription_%d';

    const SETTING_MEMBERSHIP_ACTIVE_TEXT = 'memberships.active_text';
    const SETTING_MEMBERSHIP_CANCELLED_TEXT = 'memberships.cancelled_text';
    const SETTING_MEMBERSHIP_FREE_TRIAL_ACTIVE_TEXT = 'memberships.free_trial_active_text';

    /**
     * Period in days after expiring date that we should wait for recurrent payment.
     */
    const EXPIRING_PERIOD = 11;
    const EXPIRING_PERIOD_FREE_TRIAL = 2;

    /**
     * Subscription was created from remote API call
     */
    const SOURCE_REMOTE = 'Remote';

    /**
     * Subscription was created on website action.
     */
    const SOURCE_WEBSITE = 'Frontend';

    /**
     * Subscription was created from Admin panel
     */
    const SOURCE_ADMIN = 'Admin';

    /**
     * Subscription was created automatically
     */
    const SOURCE_AUTO = 'Auto';

    /**
    *   Subscription is active
    */
    const ACTIVE = 'active';

    /**
    *   Subscription is expired
    */
    const EXPIRED = 'expired';

    /**
    *   Subscription is canceled
    */
    const CANCELLED = 'cancelled';

    /**
     * Trial subscription cancelled.
     */
    const CANCELLED_TRIAL = 'cancelled_trial';

    /**
    *   Subscription is suspended
    */
    const SUSPENDED = 'suspended';

    /**
     * @var integer
     *
     * @ORM\Column(name="subscription_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $subscriptionId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false, unique=false)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="scholarships_count", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $scholarshipsCount = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_scholarships_unlimited", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isScholarshipsUnlimited = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit", type="smallint", precision=0, scale=0, nullable=false, unique=false)
     */
    private $credit = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="renewal_date", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $renewalDate;

    /**
     * Date when subscription was canceled or expired
     *
     * @var \DateTime
     *
     * @ORM\Column(name="terminated_at", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $terminatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="active_until", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $activeUntil;

    /**
     * @var string
     *
     * @ORM\Column(name="expiration_type", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $expirationType;

    /**
     * @var string
     *
     * @ORM\Column(name="expiration_period_type", type="string", precision=0, scale=0, nullable=true, unique=false)
     */
    private $expirationPeriodType;

    /**
     * @var integer
     *
     * @ORM\Column(name="expiration_period_value", type="smallint", precision=0, scale=0, nullable=true, unique=false)
     */
    private $expirationPeriodValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true, unique=false)
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $source;

    /**
     * @var int
     *
     * @ORM\Column(name="external_id", type="string", length=255, precision=0, scale=0, nullable=true, unique=false)
     */
    private $externalId;

    /**
     * @var integer
     *
     * @ORM\Column(name="recurrent_count", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $recurrentCount;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="subscriptions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=false)
     * })
     */
    private $account;

    /**
     * @var Package
     *
     * @ORM\ManyToOne(targetEntity="Package")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="package_id", referencedColumnName="package_id", nullable=false)
     * })
     */
    private $package;

    /**
     * @var bool
     *
     * @ORM\Column(name="free_trial", type="boolean", nullable=false)
     */
    private $freeTrial = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="free_trial_end_date", type="datetime", nullable=true)
     */
    private $freeTrialEndDate;

    /**
     * @var SubscriptionAcquiredType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\SubscriptionAcquiredType", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription_acquired_type_id", referencedColumnName="subscription_acquired_type_id", nullable=true)
     * })
     */
    private $subscriptionAcquiredType;

    /**
     * @var SubscriptionStatus
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\SubscriptionStatus", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription_status_id", referencedColumnName="subscription_status_id", nullable=true)
     * })
     */
    private $subscriptionStatus;

    /**
     * @var PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method_id", referencedColumnName="payment_method_id", nullable=true)
     * })
     */
    private $paymentMethod;

    /**
    * @var string
    *
    * @ORM\Column(name="remote_status", type="string", precision=0, scale=0, unique=false, options={"default" = "active"})
    */
    private $remoteStatus = self::ACTIVE;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="remote_status_updated_at", type="datetime", nullable=true)
     */
    private $remoteStatusUpdatedAt;

    /**
     * @var ArrayCollection|Transaction[]
     *
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="subscription")
     */
    private $transactions;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_freemium", type="boolean", nullable=false)
     */
    private $isFreemium = false;

    /**
     * @var string
     *
     * @ORM\Column(name="freemium_recurrence_period", type="string", nullable=true)
     */
    private $freemiumRecurrencePeriod = Recurrable::PERIOD_TYPE_DAY;

    /**
     * @var integer
     *
     * @ORM\Column(name="freemium_recurrence_value", type="smallint", nullable=true)
     */
    private $freemiumRecurrenceValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="freemium_credits", type="smallint", nullable=true)
     */
    private $freemiumCredits;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="freemium_credits_updated_date", type="datetime", nullable=true)
     */
    private $freemiumCreditsUpdatedDate;

    /**
     * @return array
     */
    static public function remoteOptions()
    {
        return [
            static::ACTIVE    => 'Active',
            static::EXPIRED   => 'Expire',
            static::SUSPENDED => 'Suspended',
            static::CANCELLED  => 'Cancelled',
            static::CANCELLED_TRIAL => 'Cancelled Trial',
        ];
    }

    /**
     * @return array
     */
    public function tags() : array
    {
        return [
            'name'       => $this->getName(),
            'end_date'   => $this->getEndDate() ?
                $this->getEndDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : null,
            'start_date' => $this->getStartDate() ?
                $this->getStartDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : null,
            'renewal_date'   => $this->getRenewalDate() ?
                $this->getRenewalDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : null,
            'free_trial_end_date' => $this->getFreeTrialEndDate() ?
                $this->getFreeTrialEndDate()->format(DateHelper::DEFAULT_DATE_FORMAT) : null
        ];
    }

    /**
     * Subscription constructor.
     *
     * @param Package                  $package
     * @param SubscriptionAcquiredType $acquiredType
     * @param PaymentMethod            $paymentMethod
     * @param string|null              $externalId
     * @param mixed                    $subscriptionStatus
     * @param \DateTime|null           $startDate
     */
    public function __construct(
        Package                  $package,
        SubscriptionAcquiredType $acquiredType,
        PaymentMethod            $paymentMethod = null,
        string                   $externalId = null,
                                 $subscriptionStatus = SubscriptionStatus::ACTIVE,
        \DateTime                $startDate = null
    ) {
        $this->setPackage($package);
        $this->setSubscriptionStatus($subscriptionStatus);
        $this->setSubscriptionAcquiredType($acquiredType);

        if ($paymentMethod) {
            $this->setPaymentMethod($paymentMethod);
            $this->setExternalId($externalId);
        }

        if ($package->isRecurrent()) {
            $this->setRecurrentCount(0);
        }

        $startDate = $startDate ?: new \DateTime();
        list($renewalDate, $endDate) = $this->countSubscriptionRenewalAndEndDate($startDate);
        $this->setStartDate($startDate);
        $this->setRenewalDate($renewalDate);
        $this->setEndDate($endDate);

        $this->setPackagesInitialProps($package);

        $this->transactions = new ArrayCollection();
    }

    /**
     * Get subscriptionId
     *
     * @return integer
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Subscription
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Subscription
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set scholarshipsCount
     *
     * @param integer $scholarshipsCount
     *
     * @return Subscription
     */
    public function setScholarshipsCount($scholarshipsCount)
    {
        $this->scholarshipsCount = $scholarshipsCount;

        return $this;
    }

    /**
     * Get scholarshipsCount
     *
     * @return integer
     */
    public function getScholarshipsCount()
    {
        return $this->scholarshipsCount;
    }

    /**
     * Set isScholarshipsUnlimited
     *
     * @param boolean $isScholarshipsUnlimited
     *
     * @return Subscription
     */
    public function setIsScholarshipsUnlimited($isScholarshipsUnlimited)
    {
        $this->isScholarshipsUnlimited = $isScholarshipsUnlimited;

        return $this;
    }

    /**
     * Get isScholarshipsUnlimited
     *
     * @return boolean
     */
    public function getIsScholarshipsUnlimited()
    {
        return $this->isScholarshipsUnlimited;
    }

    /**
     * Set credit
     *
     * @param integer $credit
     *
     * @return Subscription
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
       return $this;
    }

    /**
     * Get credit
     *
     * @return integer
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Subscription
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Subscription
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return \DateTime
     */
    public function getTerminatedAt()
    {
        return $this->terminatedAt;
    }

    /**
     * @param \DateTime $val
     *
     * @return Subscription
     */
    public function setTerminatedAt($val)
    {
        $this->terminatedAt = $val;

        return $this;
    }

    /**
     * Set renewalDate
     *
     * @param \DateTime $renewalDate
     *
     * @return Subscription
     */
    public function setRenewalDate($renewalDate)
    {
        $this->renewalDate = $renewalDate;

        return $this;
    }

    /**
     * Update renewal date on subscription reactivation.
     *
     * @param \DateTime|null $now
     *
     * @return $this
     * @throws \Exception
     */
    public function updateRenewalDate(\DateTime $now = null)
    {
        list($renewalDate,) = $this->countSubscriptionRenewalAndEndDate($now);

        $this->setRenewalDate($renewalDate);

        return $this;
    }

    /**
     * Get renewalDate
     *
     * @return \DateTime
     */
    public function getRenewalDate()
    {
        return $this->renewalDate;
    }

    /**
     * @param \DateTime $value
     *
     * @return Subscription
     */
    public function setActiveUntil(\DateTime $value)
    {
        $this->activeUntil = $value;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getActiveUntil()
    {
        return $this->activeUntil;
    }


    /**
     * Set expirationType
     *
     * @param string $expirationType
     *
     * @return Subscription
     */
    public function setExpirationType($expirationType)
    {
        $this->expirationType = $expirationType;

        return $this;
    }

    /**
     * Get expirationType
     *
     * @return string
     */
    public function getExpirationType()
    {
        return $this->expirationType;
    }

    /**
     * Set expirationPeriodType
     *
     * @param string $expirationPeriodType
     *
     * @return Subscription
     */
    public function setExpirationPeriodType($expirationPeriodType)
    {
        $this->expirationPeriodType = $expirationPeriodType;

        return $this;
    }

    /**
     * Get expirationPeriodType
     *
     * @return string
     */
    public function getExpirationPeriodType()
    {
        return $this->expirationPeriodType;
    }

    /**
     * @return string
     */
    public function getExpirationPeriod()
    {
        return $this->getExpirationPeriodValue() === 1 ?
            $this->getExpirationPeriodType() :
            $this->getExpirationPeriodValue() .' '. $this->getExpirationPeriodType() . 's';
    }

    /**
     * Set expirationPeriodValue
     *
     * @param integer $expirationPeriodValue
     *
     * @return Subscription
     */
    public function setExpirationPeriodValue($expirationPeriodValue)
    {
        $this->expirationPeriodValue = $expirationPeriodValue;

        return $this;
    }

    /**
     * Get expirationPeriodValue
     *
     * @return integer
     */
    public function getExpirationPeriodValue()
    {
        return $this->expirationPeriodValue;
    }

    /**
     * Set priority
     *
     * @param boolean $priority
     *
     * @return Subscription
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return boolean
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Subscription
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod(PaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethodId
     *
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set externalId
     *
     * @param string $externalId
     *
     * @return Subscription
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set recurrentCount
     *
     * @param integer $recurrentCount
     *
     * @return Subscription
     */
    public function setRecurrentCount($recurrentCount)
    {
        $this->recurrentCount = $recurrentCount;

        return $this;
    }

    /**
     * Get recurrentCount
     *
     * @return integer
     */
    public function getRecurrentCount()
    {
        return $this->recurrentCount;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return Subscription
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set package
     *
     * @param Package $package
     *
     * @return Subscription
     */
    public function setPackage(Package $package = null)
    {
        $this->package = $package;

        $this->setName($package->getName());
        $this->setPrice($package->getPrice());
        $this->setCredit($package->getScholarshipsCount());
        $this->setScholarshipsCount($package->getScholarshipsCount());
        $this->setIsScholarshipsUnlimited($package->isScholarshipsUnlimited());
        $this->setExpirationType($package->getExpirationType());
        $this->setExpirationPeriodType($package->getExpirationPeriodType());
        $this->setExpirationPeriodValue($package->getExpirationPeriodValue());
        $this->setPriority($package->getPriority());

        return $this;
    }

    /**
     * Get package
     *
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return bool
     */
    public function getFreeTrial()
    {
        return $this->freeTrial;
    }

    /**
     * @return bool
     */
    public function isFreeTrial()
    {
        return $this->getFreeTrial();
    }

    /**
     * @param bool $freeTrial
     *
     * @return $this
     */
    public function setFreeTrial(bool $freeTrial)
    {
        $this->freeTrial = $freeTrial;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFreeTrialEndDate()
    {
        return $this->freeTrialEndDate;
    }

    /**
     * @param \DateTime $freeTrialEndDate
     *
     * @return $this
     */
    public function setFreeTrialEndDate(\DateTime $freeTrialEndDate)
    {
        $this->freeTrialEndDate = $freeTrialEndDate;

        return $this;
    }

    /**
     * Set subscriptionAcquiredType
     *
     * @param SubscriptionAcquiredType|int $subscriptionAcquiredType
     *
     * @return Subscription
     */
    public function setSubscriptionAcquiredType($subscriptionAcquiredType)
    {
        $this->subscriptionAcquiredType = SubscriptionAcquiredType::convert($subscriptionAcquiredType);

        return $this;
    }

    /**
     * Get subscriptionAcquiredType
     *
     * @return SubscriptionAcquiredType
     */
    public function getSubscriptionAcquiredType()
    {
        return $this->subscriptionAcquiredType;
    }

    /**
     * Set subscriptionStatus
     *
     * @param int|SubscriptionStatus $subscriptionStatus
     *
     * @return Subscription
     */
    public function setSubscriptionStatus($subscriptionStatus)
    {
        $this->subscriptionStatus = SubscriptionStatus::convert($subscriptionStatus);

        return $this;
    }

    /**
     * Get subscriptionStatus
     *
     * @return SubscriptionStatus
     */
    public function getSubscriptionStatus()
    {
        return $this->subscriptionStatus;
    }

    /**
     * @return bool
     */
    public function isRecurrent()
    {
        return $this->getExpirationType() === Package::EXPIRATION_TYPE_RECURRENT;
    }

    /**
    * Get remote_status
    *
    * @return string
    */
    public function getRemoteStatus()
    {
        return $this->remoteStatus;
    }

     /**
     * Set remote_status
     *
     * @param string $remoteStatus
     *
     * @return Subscription
     */
    public function setRemoteStatus($remoteStatus)
    {
        $this->remoteStatus = $remoteStatus;

        return $this;
    }

    /**
     * @param \DateTime $remoteStatusUpdatedAt
     *
     * @return $this
     */
    public function setRemoteStatusUpdatedAt(\DateTime $remoteStatusUpdatedAt)
    {
        $this->remoteStatusUpdatedAt = $remoteStatusUpdatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRemoteStatusUpdatedAt()
    {
        return $this->remoteStatusUpdatedAt;
    }

    /**
     * @return Transaction
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param ArrayCollection|Transaction[] $transactions
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Return end and renewal date after counting them.
     *
     * @param \DateTime|null $date
     *
     * @return array
     * @throws \Exception
     */
    public function countSubscriptionRenewalAndEndDate(\DateTime $date = null)
    {
        $date = $date ?: new \DateTime();
        $endDate = new \DateTime('0000-00-00 00:00:00');
        $renewalDate = new \DateTime('0000-00-00 00:00:00');
        $expirationType = $this->getExpirationType();

        switch ($expirationType) {
            case Package::EXPIRATION_TYPE_DATE:
                $endDate = $this->getPackage()->getExpirationDate();
                break;
            case Package::EXPIRATION_TYPE_NO_EXPIRY:
                // If No Expiry (End = NOW + 20 Years)
                $endDate = Carbon::now()->addYear(20);
                break;
            case Package::EXPIRATION_TYPE_PERIOD:
                $endDate = $this->addExpirationTimeToDate($date);
                break;
            case Package::EXPIRATION_TYPE_RECURRENT:
                $renewalDate = $this->addExpirationTimeToDate($date);
                break;
            default:
                throw new \Exception(sprintf('Unknown expiration type: %s', $expirationType));
                break;
        }

        return array($renewalDate, $endDate);
    }

    /**
     * @param \DateTime $date
     *
     * @return \DateTime|static
     * @throws \Exception
     */
    public function addExpirationTimeToDate(\DateTime $date)
    {
        $date = Carbon::instance($date);
        $expirationPeriodType = $this->getExpirationPeriodType();
        $expirationPeriodValue = $this->getExpirationPeriodValue();

        switch ($expirationPeriodType) {
            case Package::EXPIRATION_PERIOD_TYPE_DAY:
                $date->addDays($expirationPeriodValue);
                break;
            case Package::EXPIRATION_PERIOD_TYPE_WEEK:
                $date->addWeeks($expirationPeriodValue);
                break;
            case Package::EXPIRATION_PERIOD_TYPE_MONTH:
                $date->addMonths($expirationPeriodValue);
                break;
            case Package::EXPIRATION_PERIOD_TYPE_YEAR:
                $date->addYears($expirationPeriodValue);
                break;
            default:
                throw new \Exception(sprintf('Unknown expiration period type: %s', $expirationPeriodType));
                break;
        }

        return $date;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->getSubscriptionStatus()->is(SubscriptionStatus::ACTIVE);
    }

    /**
     * @return bool
     */
    public function hasCredits()
    {
        return $this->getIsScholarshipsUnlimited() || $this->getCredit() > 0;
    }

    /**
     * @return bool
     */
    public function isFreemium()
    {
        return $this->isFreemium;
    }

    /**
     * @param bool $isFreemium
     *
     * @return $this
     */
    public function setFreemium($isFreemium)
    {
        $this->isFreemium = $isFreemium;
        return $this;
    }

    /**
     * @return string
     */
    public function getFreemiumRecurrencePeriod(): string
    {
        return $this->freemiumRecurrencePeriod;
    }

    /**
     * @param string $freemiumRecurrencePeriod
     *
     * @return $this
     */
    public function setFreemiumRecurrencePeriod(string $freemiumRecurrencePeriod
    ) {
        $this->freemiumRecurrencePeriod = $freemiumRecurrencePeriod;
        return $this;
    }

    /**
     * @return int
     */
    public function getFreemiumRecurrenceValue(): int
    {
        return $this->freemiumRecurrenceValue;
    }

    /**
     * @param int $freemiumRecurrenceValue
     *
     * @return $this
     */
    public function setFreemiumRecurrenceValue(int $freemiumRecurrenceValue)
    {
        $this->freemiumRecurrenceValue = $freemiumRecurrenceValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getFreemiumCredits(): int
    {
        return $this->freemiumCredits;
    }

    /**
     * @param int $freemiumCredits
     *
     * @return $this
     */
    public function setFreemiumCredits(int $freemiumCredits)
    {
        $this->freemiumCredits = $freemiumCredits;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFreemiumCreditsUpdatedDate(): \DateTime
    {
        return $this->freemiumCreditsUpdatedDate;
    }

    /**
     * @param \DateTime $freemiumCreditsUpdatedDate
     *
     * @return $this
     */
    public function setFreemiumCreditsUpdatedDate($freemiumCreditsUpdatedDate
    ) {
        $this->freemiumCreditsUpdatedDate = $freemiumCreditsUpdatedDate;
        return $this;
    }

    /**
     * @param Package $package
     */
    protected function setPackagesInitialProps(Package $package)
    {
        if ($package->isFreeTrial()) {
            $freeTrialEndDate = Carbon::instance($this->getStartDate())
                ->add($package->getFreeTrialInterval());

            $this->setFreeTrial(true);
            $this->setFreeTrialEndDate($freeTrialEndDate);
            $this->setRenewalDate($freeTrialEndDate);
        }

        if($package->isFreemium()){
            $this->setFreemium(true);
            $this->setFreemiumCredits($package->getFreemiumCredits());
            $this->setFreemiumRecurrencePeriod($package->getFreemiumRecurrencePeriod());
            $this->setFreemiumRecurrenceValue($package->getFreemiumRecurrenceValue());
            $this->setFreemiumCreditsUpdatedDate(Carbon::now());
            if($package->getFreemiumCredits() > 0){
                $this->setCredit($package->getFreemiumCredits());
            }
        }
    }

    /**
     * Check if subscription is paid
     *
     * @return bool
     */
    public function isPaid()
    {
        return !$this->isFreemium() && $this->price != '0.00';
    }

    /**
     * @param Subscription|null $subscription
     *
     * @return string
     */
    public function isReallyPaid()
    {
        return $this->getTransactions()->count() > 0 && ($this->isActive() || $this->getActiveUntil() > new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PostRemove()
     */
    public function flushAccountSubscriptionCache()
    {
        $cacheKey = sprintf(self::CACHE_KEY_ACCOUNT_SUBSCRIPTION, $this->getAccount()->getAccountId());
        \Cache::forget($cacheKey);
    }
}
