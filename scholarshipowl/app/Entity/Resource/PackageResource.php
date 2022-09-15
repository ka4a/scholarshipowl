<?php namespace App\Entity\Resource;


use App\Entity\Package;
use App\Entity\Repository\PackageRepository;
use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Data\AbstractResource;
use App\Entity\Account;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Scholarship;
use App\Entity\SocialAccount;
use App\Entity\Subscription;
use \Doctrine\Common\Persistence\Proxy;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

class PackageResource extends AbstractResource
{
    /** @var Package */
    protected $entity;

    /**
     * PackageResource constructor.
     *
     * @param Package $package
     */
    public function __construct(Package $package = null)
    {
        $this->entity = $package;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        /**
         * @var PackageRepository $packageRepo
         */
        $packageRepo = \EntityManager::getRepository(Package::class);
        $packageIcon = [];

        $packagesWithIcon = [
            89 => 'cake.svg',
            72 => 'lollipop.svg',
            70 => 'candy.svg',
            71 => 'cap-cake.svg',
        ];

        if (in_array($this->entity->getPackageId(), array_keys($packagesWithIcon)) && isset($packagesWithIcon[$this->entity->getPackageId()])) {
            try {
                $packageIcon['icon'] = \URL::asset('img/packages/'.$packagesWithIcon[$this->entity->getPackageId()]);
            } catch (\Exception $e){
                \Log::error($e);
            }
        }

        return array_merge([
            "package_id" => $this->entity->getPackageId(),
            "name" => $this->entity->getName(),
            "alias" => $this->entity->getAlias(),
            "braintree_plan" => $this->entity->getBraintreePlan(),
            "recurly_plan" => $this->entity->getRecurlyPlan(),
            "stripe_plan" => $this->entity->getStripePlan(),
            "stripe_discount_id" => $this->entity->getStripeDiscountId(),
            "price" => $this->entity->getPrice(),
            "price_cents" => $this->entity->getPriceInCents(),
            "price_per_month" => $this->entity->getPricePerMonth(),
            "discount_price" => $this->entity->getDiscountPrice(),
            "description" => $this->entity->getDescription(),
            "scholarships_count" => $this->entity->getScholarshipsCount(),
            "is_scholarships_unlimited" => $this->entity->isScholarshipsUnlimited(),
            "expiration_type" => $this->entity->getExpirationType(),
            "expiration_date" => $this->entity->getExpirationDate(),
            "free_trial" => $this->entity->getFreeTrial(),
            "free_trial_period_type" => $this->entity->getFreeTrialPeriod(),
            "free_trial_period_value" => $this->entity->getFreeTrialPeriodValue(),
            "expiration_period_type" => $this->entity->getExpirationPeriodType(),
            "expiration_period_value" => $this->entity->getExpirationPeriodValue(),
            "is_active" => $this->entity->getIsActive(),
            "is_marked" => $this->entity->getIsMarked(),
            "is_marked_mobile" => $this->entity->getIsMobileMarked(),
            "is_automatic" => $this->entity->getIsAutomatic(),
            "priority" => $this->entity->getPriority(),
            "success_message" => $this->entity->getSuccessMessage(),
            "success_title" => $this->entity->getSuccessTitle(),
            "button_text" => $packageRepo->getButtonTextForPackage($this->entity->getPackageId()),
            "summary_description" => $this->entity->getSummaryDescription(),
            "summary_description_full_text" => $this->entity->getSummaryDescriptionText(),
            "billing_agreement" => $this->entity->getBillingAgreement(),
            "popup_cta_text" => $this->entity->getPopupCtaButton()
        ],
        $packageIcon);
    }
}
