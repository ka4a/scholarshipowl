<?php

/**
 * Package
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	25. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use App\Entity\Account;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use Carbon\Carbon;
use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\StylableEntity;
use ScholarshipOwl\Data\Entity\StyleEntity;
use ScholarshipOwl\Domain\Repository\Association\StylesAssociation;


class Package extends AbstractEntity {

    use StylableEntity;

	const EXPIRATION_TYPE_NO_EXPIRY = "no_expiry";
	const EXPIRATION_TYPE_DATE = "date";
	const EXPIRATION_TYPE_PERIOD = "period";
	const EXPIRATION_TYPE_RECURRENT = "recurrent";

	const EXPIRATION_PERIOD_TYPE_DAY = "day";
	const EXPIRATION_PERIOD_TYPE_WEEK = "week";
	const EXPIRATION_PERIOD_TYPE_MONTH = "month";
	const EXPIRATION_PERIOD_TYPE_YEAR = "year";

	const EXPIRATION_PERIOD_TYPE_NEVER = "never";

    const RECURRENCE_PERIOD_UNLIMITED = 9999;

	private $packageId;
	private $name;
	private $alias;
    private $braintree_plan;
    private $recurly_plan;
    private $stripe_plan;
    private $stripeDiscountId;
	private $price;
	private $discountPrice;
	private $description;
	private $scholarshipsCount;
	private $scholarshipsUnlimited;
	private $expirationType;
	private $expirationDate;
    private $freeTrial = false;
    private $freeTrialPeriodType;
    private $freeTrialPeriodValue;
	private $expirationPeriodType;
	private $expirationPeriodValue;
	private $active;
	private $marked;
	private $issuedAutomatically;
	private $mobileActive;
	private $mobileMarked;
	private $priority;
	private $message;
	private $successMessage;
	private $successTitle;
    private $g2sProductId;
    private $g2sTemplateId;

    private $isFreemium = false;
    private $freemiumRecurrencePeriod = 'day';
    private $freemiumRecurrenceValue;
    private $freemiumCredits;

    private $summaryDescription;

    private $isContactUs = false;
    private $contactUsLink = '';
    private $popupCtaButton = '';

    public function __construct() {
		$this->packageId = null;
		$this->name = "";
		$this->price = 0;
		$this->discountPrice = 0;
		$this->description = "";
		$this->scholarshipsCount = 0;
		$this->scholarshipsUnlimited = false;
		$this->expirationType = null;
		$this->expirationDate = "0000-00-00 00:00:00";
		$this->expirationPeriodType = null;
		$this->expirationPeriodValue = null;
		$this->active = 0;
		$this->marked = 0;
		$this->issuedAutomatically = 0;
		$this->mobileActive = 0;
		$this->mobileMarked = 0;
		$this->priority = 1;
        $this->message = "";
        $this->successMessage = "";
        $this->successTitle = "";
        $this->isFreemium = false;
        $this->summaryDescription = "";
        $this->popupCtaButton = "";
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
	public function getPackageId(){
		return $this->packageId;
	}

	public function setPackageId($packageId){
		$this->packageId = $packageId;
	}

    public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

    public function getBraintreePlan()
    {
        return $this->braintree_plan;
    }

    public function setBraintreePlan($plan)
    {
        $this->braintree_plan = $plan;
    }

    public function getRecurlyPlan()
    {
        return $this->recurly_plan;
    }

    public function setRecurlyPlan($recurly_plan)
    {
        $this->recurly_plan = $recurly_plan;
    }

    public function getStripePlan()
    {
        return $this->stripe_plan;
    }

    public function setStripePlan($stripe_plan)
    {
        $this->stripe_plan = $stripe_plan;
    }

    public function getStripeDiscountId()
    {
        return $this->stripeDiscountId;
    }

    public function setStripeDiscountId($stripeDiscountId)
    {
        $this->stripeDiscountId = $stripeDiscountId;

        return $this;
    }

    public function getPrice(){
		return $this->price;
	}

	public function setPrice($price){
		$this->price = $price;
	}

    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice($discountPrice)
    {
        $this->discountPrice = $discountPrice;
    }

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($description){
		$this->description = $description;
	}

	public function getScholarshipsCount(){
		return $this->scholarshipsCount;
	}

	public function setScholarshipsCount($scholarshipsCount){
		$this->scholarshipsCount = $scholarshipsCount;
	}

	public function isScholarshipsUnlimited(){
		return $this->scholarshipsUnlimited;
	}

	public function setScholarshipsUnlimited($scholarshipsUnlimited){
		$this->scholarshipsUnlimited = $scholarshipsUnlimited;
	}

	public function getExpirationType(){
		return $this->expirationType;
	}

	public function setExpirationType($expirationType){
		$this->expirationType = $expirationType;
	}

	public function getExpirationDate(){
		return $this->expirationDate;
	}

    public function isFreeTrial()
    {
        return $this->freeTrial;
    }

    public function setFreeTrial($freeTrial)
    {
        $this->freeTrial = $freeTrial;
    }

    public function getFreeTrialPeriodType()
    {
        return $this->freeTrialPeriodType;
    }

    public function setFreeTrialPeriodType($freeTrialPeriodType)
    {
        $this->freeTrialPeriodType = $freeTrialPeriodType;
    }

    public function getFreeTrialPeriodValue()
    {
        return $this->freeTrialPeriodValue;
    }

    public function setFreeTrialPeriodValue($freeTrialPeriodValue)
    {
        $this->freeTrialPeriodValue = $freeTrialPeriodValue;
    }

    public function setExpirationDate($expirationDate){
		$this->expirationDate = $expirationDate;
	}

	public function getExpirationPeriodType(){
		return $this->expirationPeriodType;
	}

	public function setExpirationPeriodType($expirationPeriodType){
		$this->expirationPeriodType = $expirationPeriodType;
	}

	public function getExpirationPeriodValue(){
		return $this->expirationPeriodValue;
	}

	public function setExpirationPeriodValue($expirationPeriodValue){
		$this->expirationPeriodValue = $expirationPeriodValue;
	}

	public function isActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}

	public function isMarked(){
		return $this->marked;
	}

	public function setMarked($marked){
		$this->marked = $marked;
	}

	public function isMobileActive(){
		return $this->mobileActive;
	}

	public function setMobileActive($mobileActive){
		$this->mobileActive = $mobileActive;
	}

	public function isMobileMarked(){
		return $this->mobileMarked;
	}

	public function setMobileMarked($mobileMarked){
		$this->mobileMarked = $mobileMarked;
	}

    public function getPriority(){
        return $this->priority;
    }

    public function setPriority($priority){
        $this->priority = $priority;
    }

    public function isAutomatic(){
        return $this->issuedAutomatically;
    }

    public function setAutomatic($automatic) {
        $this->issuedAutomatically = $automatic;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getDisplayDescription() {
        return $this->prepareDisplayText($this->getDescription());
    }

    public function getDisplayMessage() {
        return $this->prepareDisplayText($this->getMessage());
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getSuccessMessage() {
        return $this->successMessage;
    }

    public function getDisplaySuccessMessage() {
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
            $this->getExpirationDate(),
            $eligibleScholarshipsCount,
        ],
            $text !== null ? $text : ''
        );
    }

    public function setSuccessMessage($successMessage) {
        $this->successMessage = $successMessage;
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

    public function getG2SProductId()
    {
        return $this->g2sProductId;
    }

    public function setG2SProductId($productId)
    {
        $this->g2sProductId = $productId;
    }

    public function getG2STemplateId()
    {
        return $this->g2sTemplateId;
    }

    public function setG2STemplateId($templateId)
    {
        $this->g2sTemplateId = $templateId;
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

    public function getStyles()
    {
        if ($this->styles === null) {
            $stylesAssociation = new StylesAssociation();
            $stylesAssociation->addStylesOnPackage($this);
        }

        return $this->styles;
    }

	public static function getExpirationTypes() {
		return array(
			self::EXPIRATION_TYPE_NO_EXPIRY => "No Expiry",
			self::EXPIRATION_TYPE_DATE => "Date",
			self::EXPIRATION_TYPE_PERIOD => "Period",
			self::EXPIRATION_TYPE_RECURRENT => "Recurrence",
		);
	}

	public static function getExpirationPeriodTypes() {
		return array(
			self::EXPIRATION_PERIOD_TYPE_DAY => "Day",
			self::EXPIRATION_PERIOD_TYPE_WEEK => "Week",
			self::EXPIRATION_PERIOD_TYPE_MONTH => "Month",
			self::EXPIRATION_PERIOD_TYPE_YEAR => "Year",
			self::EXPIRATION_PERIOD_TYPE_NEVER => "Never"
		);
	}

    public static function getExpirationPeriodValues() {
        $unlimited = array(self::RECURRENCE_PERIOD_UNLIMITED => "Unlimited");
        return $unlimited + array_combine(range(1, 5000), range(1, 5000));
    }

    public static function getPriorityOptions() {
        return array_combine(range(1, 100), range(1, 100));
    }

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "package_id") {
				$this->setPackageId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
            else if($key == "braintree_plan") {
                $this->setBraintreePlan($value);
            }
            else if($key == "recurly_plan") {
                $this->setRecurlyPlan($value);
            }
            else if($key == "stripe_plan") {
                $this->setStripePlan($value);
            }
            else if($key == "stripe_discount_id") {
                $this->setStripeDiscountId($value);
            }
			else if($key == "price") {
				$this->setPrice($value);
			}
			else if($key == "discount_price") {
				$this->setDiscountPrice($value);
			}
			else if($key == "description") {
				$this->setDescription($value);
			}
			else if($key == "scholarships_count") {
				$this->setScholarshipsCount($value);
			}
			else if($key == "is_scholarships_unlimited") {
				$this->setScholarshipsUnlimited($value);
			}
			else if($key == "expiration_type") {
				$this->setExpirationType($value);
			}
			else if($key == "expiration_date") {
				$this->setExpirationDate($value);
			}
            else if($key == 'free_trial') {
                $this->setFreeTrial($value);
            }
            else if($key == 'free_trial_period_type') {
                $this->setFreeTrialPeriodType($value);
            }
            else if($key == 'free_trial_period_value') {
                $this->setFreeTrialPeriodValue($value);
            }
			else if($key == "expiration_period_type") {
				$this->setExpirationPeriodType($value);
			}
			else if($key == "expiration_period_value") {
				$this->setExpirationPeriodValue($value);
			}
			else if($key == "is_active") {
				$this->setActive($value);
			}
			else if($key == "is_marked") {
				$this->setMarked($value);
			}
			else if($key == "is_mobile_active") {
				$this->setMobileActive($value);
			}
			else if($key == "is_mobile_marked") {
				$this->setMobileMarked($value);
			}
            else if($key == "is_automatic") {
                $this->setAutomatic($value);
            }
            else if($key == "priority") {
                $this->setPriority($value);
            }
            else if($key == "message") {
                $this->setMessage($value);
            }
            else if($key == "success_message") {
                $this->setSuccessMessage($value);
            }
            else if($key == "success_title") {
                $this->setSuccessTitle($value);
            }
            else if($key == "button_css") {
                if ($buttonStyle = $this->getStyle('button')) {
                    $buttonStyle->setCSS($value);
                } else {
                    $buttonStyle = new StyleEntity($this, 'button', $value, '');
                    $this->addStyle($buttonStyle);
                }
            }
            else if($key == "button_content") {
                if ($buttonStyle = $this->getStyle('button')) {
                    $buttonStyle->setContent($value);
                } else {
                    $this->addStyle(new StyleEntity($this, 'button', '', $value));
                }
            }
            else if($key == "g2s_product_id") {
                $this->setG2SProductId($value);
            }
            else if($key == "g2s_template_id") {
                $this->setG2STemplateId($value);
            }else if($key == "is_freemium") {
                $this->setIsFreemium($value);
            }else if($key == "freemium_recurrence_period") {
                $this->setFreemiumRecurrencePeriod($value);
            }else if($key == "freemium_recurrence_value") {
                $this->setFreemiumRecurrenceValue($value);
            }else if($key == "freemium_credits") {
                $this->setFreemiumCredits($value);
            }else if($key == "is_contact_us") {
                $this->setIsContactUs($value);
            }else if($key == "contact_us_link") {
                $this->setContactUsLink($value);
            }else if($key == "alias") {
                $this->setAlias($value);
            }else if($key == "summary_description") {
                $this->setSummaryDescription($value);
            }else if($key == "popup_cta_button") {
                $this->setPopupCtaButton($value);
            }
		}
	}

	public function toArray() {
		return array(
			"package_id" => $this->getPackageId(),
			"name" => $this->getName(),
			"alias" => $this->getAlias(),
            "braintree_plan" => $this->getBraintreePlan(),
            "recurly_plan" => $this->getRecurlyPlan(),
            "stripe_plan" => $this->getStripePlan(),
			"stripe_discount_id" => $this->getStripeDiscountId(),
			"price" => $this->getPrice(),
			"discount_price" => $this->getDiscountPrice(),
			"description" => $this->getDescription(),
			"scholarships_count" => $this->getScholarshipsCount(),
			"is_scholarships_unlimited" => $this->isScholarshipsUnlimited(),
			"expiration_type" => $this->getExpirationType(),
			"expiration_date" => $this->getExpirationDate(),
            'free_trial' => $this->isFreeTrial(),
            'free_trial_period_type' => $this->getFreeTrialPeriodType(),
            'free_trial_period_value' => $this->getFreeTrialPeriodValue(),
			"expiration_period_type" => $this->getExpirationPeriodType(),
			"expiration_period_value" => $this->getExpirationPeriodValue(),
			"is_active" => $this->isActive(),
			"is_marked" => $this->isMarked(),
			"is_automatic" => $this->isAutomatic(),
			"is_mobile_active" => $this->isMobileActive(),
			"is_mobile_marked" => $this->isMobileMarked(),
			"priority" => $this->getPriority(),
			"message" => $this->getMessage(),
			"success_message" => $this->getSuccessMessage(),
			"success_title" => $this->getSuccessTitle(),
            "g2s_product_id" => $this->getG2SProductId(),
            "g2s_template_id" => $this->getG2STemplateId(),

            'is_freemium' => $this->isFreemium(),
            'freemium_recurrence_period ' => $this->getFreemiumRecurrencePeriod(),
            'freemium_recurrence_value' => $this->getFreemiumRecurrenceValue(),
            'freemium_credits' => $this->getFreemiumCredits(),

            'is_contact_us' => $this->isContactUs(),
            'contact_us_link' => $this->getContactUsLink(),

            'summary_description' => $this->getSummaryDescription(),
            'popup_cta_button' => $this->getPopupCtaButton(),
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

    public function getRecurrentTypeMessage() {
    	$result = "";

    	if ($this->isExpirationTypeRecurrent()) {
    		if ($this->getExpirationPeriodType() == self::EXPIRATION_PERIOD_TYPE_DAY) {
    			$result = "Daily";
    		}
    		else if ($this->getExpirationPeriodType() == self::EXPIRATION_PERIOD_TYPE_WEEK) {
    			$result = "Weekly";
    		}
    		else if ($this->getExpirationPeriodType() == self::EXPIRATION_PERIOD_TYPE_MONTH) {
    			$result = "Monthly";
    		}
    		else if ($this->getExpirationPeriodType() == self::EXPIRATION_PERIOD_TYPE_YEAR) {
    			$result = "Annual";
    		}
    	}

    	return $result;
    }

    /**
     * @return bool|int
     */
    public function getPricePerMonth()
    {
        $pricePerMonth = false;

        $price = $this->getPrice();
        $expirationPeriodValue = $this->getExpirationPeriodValue();

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

    public function getRecurrentTypeMessageFull()
    {
    	$message = "";

    	if ($this->isExpirationTypeRecurrent()) {

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
     * @return string
     */
    public function getBillingAgreement()
    {
        $billingAgreement = '';
        if ($this->isExpirationTypeRecurrent() && (int)$this->getDiscountPrice() !== 0) {
            $billingAgreement =  sprintf('%s for $%s for the first billing, then $%s %s', $this->getName(), $this->getDiscountPrice(), $this->getPricePerMonth(), strtolower($this->getRecurrentTypeMessageFull()));
        } else {
            $billingAgreement = $this->isExpirationTypeRecurrent() ?
                sprintf('%s for $%s/mo, %s', $this->getName(), $this->getPricePerMonth(), $this->getRecurrentTypeMessageFull()) :
                sprintf('%s for $%s, one time payment', $this->getName(), $this->getPrice() );
        }
        return $billingAgreement;
    }
    /**
     * @return string
     */
    public function getFreeTrialPeriodText()
    {
        return $this->getFreeTrialPeriodValue() .' '. $this->getFreeTrialPeriodType()
            .(($this->getFreeTrialPeriodValue() !== '1') ? 's' : '');
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
        $this->contactUsLink = $contactUsLink;

        return $this;
    }

    public function setSummaryDescription($summaryDescription)
    {
        $this->summaryDescription = $summaryDescription;
    }

    public function getSummaryDescription()
    {
        return $this->summaryDescription;
    }

    public function getSummaryDescriptionText()
    {
        $startDate = new \DateTime();
        $description = $this->getSummaryDescription();
        $firstBillingDate = new \DateTime();

        if($this->isFreeTrial()) {
            $firstBillingDate = Carbon::instance($startDate)
                ->add(\DateInterval::createFromDateString(
                    sprintf('%s %s', $this->getFreeTrialPeriodValue(), $this->getFreeTrialPeriodType())
                ));
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
}
