<?php namespace App\Entity;

use App\Contracts\Recurrable;
use App\Entity\Repository\ScholarshipRepository;
use Carbon\Carbon;
use App\Facades\EntityManager;
use Doctrine\ORM\Mapping AS ORM;
use ScholarshipOwl\Data\DateHelper;

/**
 * Class Package
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PackageRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="package")
 */
class Package
{

    const FREEMIUM_MVP_ALIAS = "freemium-mvp";

    const EXPIRATION_TYPE_NO_EXPIRY = "no_expiry";
    const EXPIRATION_TYPE_DATE = "date";
    const EXPIRATION_TYPE_PERIOD = "period";
    const EXPIRATION_TYPE_RECURRENT = "recurrent";

    const EXPIRATION_PERIOD_TYPE_DAY = "day";
    const EXPIRATION_PERIOD_TYPE_WEEK = "week";
    const EXPIRATION_PERIOD_TYPE_MONTH = "month";
    const EXPIRATION_PERIOD_TYPE_YEAR = "year";
    const EXPIRATION_PERIOD_TYPE_NEVER = "never";

    const CACHE_TAG = 'package_cache';

    /**
     * @var integer
     *
     * @ORM\Column(name="package_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $packageId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="braintree_plan", type="string", length=255, nullable=true)
     */
    private $braintreePlan;

    /**
     * @var string
     *
     * @ORM\Column(name="recurly_plan", type="string", nullable=true)
     */
    private $recurlyPlan;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_plan", type="string", nullable=true)
     */
    private $stripePlan;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_discount_id", type="string", nullable=true)
     */
    private $stripeDiscountId = '';

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="summary_description", type="string", length=4095, nullable=true)
     */
    private $summaryDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=4095, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="scholarships_count", type="smallint", nullable=false)
     */
    private $scholarshipsCount = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_scholarships_unlimited", type="boolean", nullable=false)
     */
    private $isScholarshipsUnlimited = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="expiration_type", type="string", nullable=false)
     */
    private $expirationType = 'date';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime", nullable=false)
     */
    private $expirationDate = '0000-00-00 00:00:00';

    /**
     * @var bool
     *
     * @ORM\Column(name="free_trial", type="boolean", nullable=false)
     */
    private $freeTrial = false;

    /**
     * @var string
     *
     * @ORM\Column(name="free_trial_period_type", type="string", nullable=true)
     */
    private $freeTrialPeriod;

    /**
     * @var integer
     *
     * @ORM\Column(name="free_trial_period_value", type="integer", nullable=true)
     */
    private $freeTrialPeriodValue;

    /**
     * @var string
     *
     * @ORM\Column(name="expiration_period_type", type="string", nullable=true)
     */
    private $expirationPeriodType = 'month';

    /**
     * @var integer
     *
     * @ORM\Column(name="expiration_period_value", type="smallint", nullable=true)
     */
    private $expirationPeriodValue = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_marked", type="boolean", nullable=true)
     */
    private $isMarked = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_automatic", type="boolean", nullable=true)
     */
    private $isAutomatic = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_mobile_active", type="boolean", nullable=true)
     */
    private $isMobileActive = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_mobile_marked", type="boolean", nullable=true)
     */
    private $isMobileMarked = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=4095, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="success_message", type="string", length=4095, nullable=true)
     */
    private $successMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="success_title", type="string", length=255, nullable=true)
     */
    private $successTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="g2s_product_id", type="string", length=255, nullable=true)
     */
    private $g2sProductId;

    /**
     * @var string
     *
     * @ORM\Column(name="g2s_template_id", type="string", length=255, nullable=true)
     */
    private $g2sTemplateId;

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
     * @var bool
     *
     * @ORM\Column(name="is_contact_us", type="boolean", nullable=false)
     */
    private $isContactUs = false;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_us_link", type="boolean", nullable=false)
     */
    private $contactUsLink = false;

    /**
     * @var string
     *
     * @ORM\Column(name="discount_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $discountPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_cta_button", type="string", length=255, nullable=true)
     */
    private $popupCtaButton;

    protected static $options;

    /**
     * @return array
     */
    public static function options()
    {
        if (static::$options === null) {
            $options = [0 => 'Not selected'];
            $query = \EntityManager::getRepository(static::class)
                ->createQueryBuilder('p')
                ->select(['p.packageId', 'p.name'])
                ->getQuery();

            foreach ($query->getScalarResult() as $item) {
                $options[$item['packageId']] = sprintf('%s (%s)', $item['name'], $item['packageId']);
            }

            static::$options = $options;
        }

        return static::$options;
    }

    /**
     * Package constructor.
     *
     * @param string $name
     * @param        $price
     * @param string $expirationType
     * @param int    $priority
     */
    public function __construct(string $name, $price, string $expirationType, int $priority = 0)
    {
        $this->setName($name);
        $this->setPrice($price);
        $this->setExpirationType($expirationType);
        $this->setExpirationDate(new \DateTime('0000-00-00 00:00:00'));
        $this->setPriority($priority);
    }

    /**
     * Get packageId
     *
     * @return integer
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Package
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
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Set braintreePlan
     *
     * @param string $braintreePlan
     *
     * @return Package
     */
    public function setBraintreePlan($braintreePlan)
    {
        $this->braintreePlan = $braintreePlan;

        return $this;
    }

    /**
     * Get braintreePlan
     *
     * @return string
     */
    public function getBraintreePlan()
    {
        return $this->braintreePlan;
    }

    /**
     * @param string $recurlyPlan
     *
     * @return $this
     */
    public function setRecurlyPlan($recurlyPlan)
    {
        $this->recurlyPlan = $recurlyPlan;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecurlyPlan()
    {
        return $this->recurlyPlan;
    }

    /**
     * @param string $stripePlan
     *
     * @return $this
     */
    public function setStripePlan($stripePlan)
    {
        $this->stripePlan = $stripePlan;

        return $this;
    }

    /**
     * @return string
     */
    public function getStripePlan()
    {
        return $this->stripePlan;
    }

    /**
     * @return string
     */
    public function getStripeDiscountId()
    {
        return $this->stripeDiscountId;
    }

    /**
     * @param string $stripeDiscountId
     *
     * @return Package
     */
    public function setStripeDiscountId($stripeDiscountId)
    {
        $this->stripeDiscountId = $stripeDiscountId;

        return $this;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Package
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
     * @return int
     */
    public function getPriceInCents()
    {
        return (int) ($this->getPrice() * 100);
    }

    /**
     * @return string
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * @param string $discountPrice
     */
    public function setDiscountPrice(string $discountPrice)
    {
        $this->discountPrice = $discountPrice;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Package
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set scholarshipsCount
     *
     * @param integer $scholarshipsCount
     *
     * @return Package
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
     * @return Package
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
    public function isScholarshipsUnlimited()
    {
        return $this->isScholarshipsUnlimited;
    }

    /**
     * Set expirationType
     *
     * @param string $expirationType
     *
     * @return Package
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
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return Package
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
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
     * @return string
     */
    public function getFreeTrialPeriod()
    {
        return $this->freeTrialPeriod;
    }

    /**
     * @param string $freeTrialPeriod
     *
     * @return $this
     */
    public function setFreeTrialPeriod(string $freeTrialPeriod)
    {
        $this->freeTrialPeriod = $freeTrialPeriod;

        return $this;
    }

    /**
     * @return int
     */
    public function getFreeTrialPeriodValue()
    {
        return $this->freeTrialPeriodValue;
    }

    /**
     * @param int $freeTrialPeriodValue
     *
     * @return $this
     */
    public function setFreeTrialPeriodValue(int $freeTrialPeriodValue)
    {
        $this->freeTrialPeriodValue = $freeTrialPeriodValue;

        return $this;
    }

    /**
     * @return \DateInterval
     */
    public function getFreeTrialInterval()
    {
        return \DateInterval::createFromDateString(
            sprintf('%s %s', $this->getFreeTrialPeriodValue(), $this->getFreeTrialPeriod())
        );
    }

    /**
     * @return string
     */
    public function getFreeTrialPeriodText()
    {
        return $this->getFreeTrialPeriodValue() .' '. $this->getFreeTrialPeriod()
            .(($this->getFreeTrialPeriodValue() !== 1) ? 's' : '');
    }

    /**
     * Set expirationPeriodType
     *
     * @param string $expirationPeriodType
     *
     * @return Package
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
     * Set expirationPeriodValue
     *
     * @param integer $expirationPeriodValue
     *
     * @return Package
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Package
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isMarked
     *
     * @param boolean $isMarked
     *
     * @return Package
     */
    public function setIsMarked($isMarked)
    {
        $this->isMarked = $isMarked;

        return $this;
    }

    /**
     * Get isMarked
     *
     * @return boolean
     */
    public function getIsMarked()
    {
        return $this->isMarked;
    }

    /**
     * Set isAutomatic
     *
     * @param boolean $isAutomatic
     *
     * @return Package
     */
    public function setIsAutomatic($isAutomatic)
    {
        $this->isAutomatic = $isAutomatic;

        return $this;
    }

    /**
     * Get isAutomatic
     *
     * @return boolean
     */
    public function getIsAutomatic()
    {
        return $this->isAutomatic;
    }

    /**
     * Set isMobileActive
     *
     * @param boolean $isMobileActive
     *
     * @return Package
     */
    public function setIsMobileActive($isMobileActive)
    {
        $this->isMobileActive = $isMobileActive;

        return $this;
    }

    /**
     * Get isMobileActive
     *
     * @return boolean
     */
    public function getIsMobileActive()
    {
        return $this->isMobileActive;
    }

    /**
     * Set isMobileMarked
     *
     * @param boolean $isMobileMarked
     *
     * @return Package
     */
    public function setIsMobileMarked($isMobileMarked)
    {
        $this->isMobileMarked = $isMobileMarked;

        return $this;
    }

    /**
     * Get isMobileMarked
     *
     * @return boolean
     */
    public function getIsMobileMarked()
    {
        return $this->isMobileMarked;
    }

    /**
     * Set priority
     *
     * @param int $priority
     *
     * @return Package
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Package
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set successMessage
     *
     * @param string $successMessage
     *
     * @return Package
     */
    public function setSuccessMessage($successMessage)
    {
        $this->successMessage = $successMessage;

        return $this;
    }

    /**
     * Get successMessage
     *
     * @return string
     */
    public function getSuccessMessage()
    {
        return $this->successMessage;
    }

    /**
     * @return string
     */
    public function getSuccessTitle()
    {
        return $this->successTitle;
    }

    /**
     * @param string $successTitle
     *
     * @return Package
     */
    public function setSuccessTitle($successTitle)
    {
        $this->successTitle = $successTitle;

        return $this;
    }

    /**
     * Set g2sProductId
     *
     * @param string $g2sProductId
     *
     * @return Package
     */
    public function setG2sProductId($g2sProductId)
    {
        $this->g2sProductId = $g2sProductId;

        return $this;
    }

    /**
     * Get g2sProductId
     *
     * @return string
     */
    public function getG2sProductId()
    {
        return $this->g2sProductId;
    }

    /**
     * Set g2sTemplateId
     *
     * @param string $g2sTemplateId
     *
     * @return Package
     */
    public function setG2sTemplateId($g2sTemplateId)
    {
        $this->g2sTemplateId = $g2sTemplateId;

        return $this;
    }

    /**
     * Get g2sTemplateId
     *
     * @return string
     */
    public function getG2sTemplateId()
    {
        return $this->g2sTemplateId;
    }

    /**
     * @return bool
     */
    public function isRecurrent()
    {
        return $this->getExpirationType() === static::EXPIRATION_TYPE_RECURRENT;
    }

    /**
     * @return string
     */
    public function getDisplayDescription()
    {
        return $this->prepareDisplayText($this->getDescription());
    }

    /**
     * @return string
     */
    public function getDisplayMessage(): string
    {
        return $this->prepareDisplayText($this->getMessage());
    }

    /**
     * @return string
     */
    public function getDisplaySuccessMessage(): string
    {
        return $this->prepareDisplayText($this->getSuccessMessage());
    }

    /**
     * Insert tags values in messages.
     *
     * @param string $text
     *
     * @return string
     */
    protected function prepareDisplayText(string $text = null): string
    {
        $eligibleScholarshipsCount = 0;

        /** @var Account $account */
        if (($account = \Auth::user()) instanceof Account) {
            /** @var ScholarshipRepository $repository */
            $repository = \EntityManager::getRepository(Scholarship::class);
            $eligibleScholarshipsCount = $repository->countEligibleScholarships($account);
        }

        return str_replace(
            [
                "[[name]]",
                "[[price]]",
                "[[scholarships]]",
                "[[unlimited]]",
                "[[period]]",
                "[[period_value]]",
                "[[expiration_date]]",
                "[[eligible_scholarships_count]]",
            ],[
                $this->getName(),
                $this->getPrice(),
                $this->getScholarshipsCount(),
                $this->isScholarshipsUnlimited() ? "Unlimited" : "",
                $this->getExpirationPeriodType(),
                $this->getExpirationPeriodValue(),
                $this->getExpirationDate()->format(DateHelper::DEFAULT_FORMAT),
                $eligibleScholarshipsCount,
            ],
            $text !== null ? $text : ''
        );
    }

    /**
     * Get expiration period in paypal format.
     *
     * @return bool|string
     */
    public function getPaypalExpirationPeriodType()
    {
        switch ($this->getExpirationPeriodType()) {
            case self::EXPIRATION_PERIOD_TYPE_DAY:
                $paypalExpiration = 'D';
                break;

            case self::EXPIRATION_PERIOD_TYPE_WEEK:
                $paypalExpiration = 'W';
                break;

            case self::EXPIRATION_PERIOD_TYPE_MONTH:
                $paypalExpiration = 'M';
                break;

            case self::EXPIRATION_PERIOD_TYPE_YEAR:
                $paypalExpiration = 'Y';
                break;

            default:
                $paypalExpiration = false;
                break;
        }

        return $paypalExpiration;
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
     */
    public function setIsFreemium($isFreemium)
    {
        $this->isFreemium = $isFreemium;
        return $this;
    }

    /**
     * @return string
     */
    public function getFreemiumRecurrencePeriod()
    {
        return $this->freemiumRecurrencePeriod;
    }

    /**
     * @param string $freemiumRecurrencePeriod
     *
     * @return $this
     */
    public function setFreemiumRecurrencePeriod($freemiumRecurrencePeriod
    ) {
        $this->freemiumRecurrencePeriod = $freemiumRecurrencePeriod;
        return $this;
    }

    /**
     * @return int
     */
    public function getFreemiumRecurrenceValue()
    {
        return $this->freemiumRecurrenceValue;
    }

    /**
     * @param int $freemiumRecurrenceValue
     *
     * @return $this
     */
    public function setFreemiumRecurrenceValue($freemiumRecurrenceValue)
    {
        $this->freemiumRecurrenceValue = $freemiumRecurrenceValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getFreemiumCredits()
    {
        return $this->freemiumCredits;
    }

    /**
     * @param int $freemiumCredits
     *
     * @return $this
     */
    public function setFreemiumCredits($freemiumCredits)
    {
        $this->freemiumCredits = $freemiumCredits;
        return $this;
    }

    /**
     * @return bool
     */
    public function isContactUs()
    {
        return $this->isContactUs;
    }

    /**
     * @param bool $isContactUs
     *
     * @return Package
     */
    public function setIsContactUs($isContactUs)
    {
        $this->isContactUs = $isContactUs;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactUsLink()
    {
        return $this->contactUsLink;
    }

    /**
     * @param string $contactUsLink
     *
     * @return Package
     */
    public function setContactUsLink($contactUsLink)
    {
        $this->isContactUs = $contactUsLink;

        return $this;
    }

    /**
     * Get package monthly price
     *
     * @return bool|int
     */
    public function getPricePerMonth()
    {
        $pricePerMonth = false;

        $price = $this->getPrice();
        $expirationPeriodValue = $this->getExpirationPeriodValue();
        $expirationPeriodValue =  $expirationPeriodValue == 0 ? 1 : $expirationPeriodValue;
        switch ($this->getExpirationPeriodType()) {
            case static::EXPIRATION_PERIOD_TYPE_DAY:
                $pricePerMonth = intval($price * ( 30 / $expirationPeriodValue));
                break;

            case static::EXPIRATION_PERIOD_TYPE_WEEK:
                $pricePerMonth = intval($price * ( 4 / $expirationPeriodValue ));
                break;

            case static::EXPIRATION_PERIOD_TYPE_MONTH:
                $pricePerMonth = intval($price / $expirationPeriodValue);
                break;

            case static::EXPIRATION_PERIOD_TYPE_YEAR:
                $pricePerMonth = intval($price / ( 12 / $expirationPeriodValue ));
                break;

            default:
                break;
        }

        return $pricePerMonth;
    }

    /*
     * Get full message for recurrent package
     *
     * @return string
     */
    public function getRecurrentTypeMessageFull()
    {
        $message = "";

        if ($this->isRecurrent()) {

            $expirationValue = intval($this->getExpirationPeriodValue());
            switch ($this->getExpirationPeriodType()) {
                case static::EXPIRATION_PERIOD_TYPE_DAY:
                    $message = 'Billed ';
                    $message .= ($expirationValue === 1 ? 'daily' : "every $expirationValue days");
                    break;
                case static::EXPIRATION_PERIOD_TYPE_WEEK:
                    $message = 'Billed ';
                    $message .= ($expirationValue === 1 ? 'weekly' : "every $expirationValue weeks");
                    break;
                case static::EXPIRATION_PERIOD_TYPE_MONTH:
                    $message = 'Billed ';
                    $message .= ($expirationValue === 1 ? 'monthly' : "every $expirationValue months");
                    break;
                case static::EXPIRATION_PERIOD_TYPE_YEAR:
                    $message = 'Billed ';
                    $message .= ($expirationValue === 1 ? 'annually' : "every $expirationValue years");
                    break;
                default:
                    break;
            }
        }

        return $message;
    }

    /**
     * Get billing agreement message
     *
     * @return string
     */
    public function getBillingAgreement()
    {
        $billingAgreement = '';
        if($this->isRecurrent() && (int)$this->getDiscountPrice() !== 0) {
            $billingAgreement =  sprintf('%s for $%s for the first billing, then $%s %s', $this->getName(), $this->getDiscountPrice(), $this->getPricePerMonth(), strtolower($this->getRecurrentTypeMessageFull()));
        }else{
            $billingAgreement = $this->isRecurrent() ?
                sprintf('%s for $%s/mo, %s', $this->getName(), $this->getPricePerMonth(), $this->getRecurrentTypeMessageFull()) :
                sprintf('%s for $%s, one time payment', $this->getName(), $this->getPrice() );
        }
        return $billingAgreement;
    }

    /**
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getButtonTextForPackage()
    {
        $packageId = $this->getPackageId();

        $cacheKey = sprintf('package_button_text_%d', $packageId);
        if (!$buttonText = \Cache::tags([Package::CACHE_TAG])->get($cacheKey)) {

            $content = EntityManager::getConnection()->executeQuery(
                'select content from package_style where package_id = :packageId and element = "button";',
                ['packageId' => $packageId]
            )->fetch();
            if(is_array($content) && isset($content['content'])) {
                $buttonText = $content['content'];
                \Cache::tags([Package::CACHE_TAG])->put($cacheKey, $buttonText, 60 * 24 * 7);
            }
        }

        return $buttonText;
    }

    /**
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getButtonCssForPackage()
    {
        $buttonCss = "";
        $packageId = $this->getPackageId();

        $cacheKey = sprintf('package_button_css_%d', $packageId);
        if (!$buttonCss = \Cache::tags([Package::CACHE_TAG])->get($cacheKey)) {

            $content = EntityManager::getConnection()->executeQuery(
                'select css from package_style where package_id = :packageId and element = "button";',
                ['packageId' => $packageId]
            )->fetch();
            if(is_array($content) && isset($content['css'])) {
                $buttonCss = $content['css'];
                \Cache::tags([Package::CACHE_TAG])->put($cacheKey, $buttonCss, 60 * 24 * 7);
            }
        }

        return $buttonCss;
    }
    public function isExpirationTypeNoExpiry() {
        return $this->expirationType == self::EXPIRATION_TYPE_NO_EXPIRY;
    }

    public function isExpirationTypeDate() {
        return $this->expirationType == self::EXPIRATION_TYPE_DATE;
    }

    public function isExpirationTypePeriod() {
        return $this->expirationType == self::EXPIRATION_TYPE_PERIOD;
    }

    public function isExpirationTypeRecurrent() {
        return $this->expirationType == self::EXPIRATION_TYPE_RECURRENT;
    }


    /**
     * @return string
     */
    public function getSummaryDescription()
    {
        return $this->summaryDescription;
    }


    public function getSummaryDescriptionText()
    {
        $startDate = new \DateTime();
        $description = $this->getSummaryDescription();
        $firstBillingDate = new \DateTime();

        if($this->getFreeTrial()) {
            $firstBillingDate = Carbon::instance($startDate)
                ->add($this->getFreeTrialInterval());
        }

        $firstBillingDate = $firstBillingDate->format("jS F, Y");

        return str_replace(
            [
                "[[first_billing_date]]",
                "[[billing_amount]]",
            ],[
            $firstBillingDate,
            $this->getPrice(),
        ],
            $description !== null ? $description : ''
        );
    }
    /**
     * @param string $summaryDescription
     */
    public function setSummaryDescription($summaryDescription)
    {
        $this->summaryDescription = $summaryDescription;
    }

    /**
     * @return string
     */
    public function getPopupCtaButton()
    {
        return $this->popupCtaButton;
    }

    /**
     * @param string $popupCtaButton
     */
    public function setPopupCtaButton($popupCtaButton)
    {
        $this->popupCtaButton = $popupCtaButton;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PostRemove()
     */
    public function flushCacheTag()
    {
        \Cache::tags([self::CACHE_TAG])->flush();
    }
}
