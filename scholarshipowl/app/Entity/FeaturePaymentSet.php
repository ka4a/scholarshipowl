<?php namespace App\Entity;

use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Traits\Dictionary;
use App\Facades\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use ScholarshipOwl\Data\Entity\Payment\Package as LibPackage;
use ScholarshipOwl\Data\Service\Payment\PackageService;

/**
 * FeaturePaymentSet
 *
 * @ORM\Table(name="feature_payment_set", uniqueConstraints={@ORM\UniqueConstraint(name="feature_payment_set_name_unique", columns={"name"})})
 * @ORM\Entity
 */
class FeaturePaymentSet
{
    use Dictionary;
    use Timestamps;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_title", type="string", length=255, nullable=false)
     */
    private $popupTitle;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_names", type="boolean", nullable=false)
     */
    private $showNames = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="mobile_special_offer_only", type="boolean", nullable=false)
     */
    private $mobileSpecialOfferOnly;

    /**
     * @var array
     *
     * @ORM\Column(name="packages", type="json_array", length=255, nullable=false)
     */
    private $packages;

    /**
     * @var PaymentMethod
     *
     * @ORM\OneToOne(fetch="EAGER", targetEntity="PaymentMethod" )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method", referencedColumnName="payment_method_id")
     * })
     */
    private $paymentMethod;

    /**
     * @var array
     *
     * @ORM\Column(name="common_option", type="json_array", length=255, nullable=false)
     */
    protected $commonOption;

    /**
     * @return FeaturePaymentSet
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    static public function config()
    {
        return is_mobile() ?
            FeatureSet::config()->getMobilePaymentSet() :
            FeatureSet::config()->getDesktopPaymentSet();
    }

    /**
     * @return string
     */
    static public function popupTitleDisplay()
    {
        $title = static::config()->getPopupTitle();

        if (($account = \Auth::user()) && ($account instanceof Account)) {
            $title = $account->mapTags($title);
        }

        return $title;
    }

    /**
     * @return array|LibPackage[]
     */
    static public function packages()
    {
        $result = [];
        $config = static::config()->getPackages() ?? [];
        $service = new PackageService();
        $ids = array_map(
            function($package) {
                return $package['id'];
            },
            $config
        );

        /** @var Package[] $packageList */
        $packages = [];
        $packageList = EntityManager::getRepository(Package::class)->findBy(['packageId' => $ids]);

        foreach ($packageList as $p) {
            $packages[$p->getPackageId()] = $p;
        }



        foreach ($config as $package) {
            if ($package['flag'] ?? false) {
                $packages[$package['id']]->setIsMobileMarked(true);
                $packages[$package['id']]->setIsMarked(true);
            }
        }

        foreach ($ids as $id) {
            $result[$id] = $packages[$id];
        }

        return $result;
    }


    /**
     * @return array|LibPackage[]
     */
    static public function newPackages()
    {
        $result = [];
        $config = static::config()->getPackages() ?? [];
        $ids = array_map(
            function($package) {
                return $package['id'];
            },
            $config
        );
        $orderedConfig = [];
        foreach ($config as $item) {
            $orderedConfig[$item['id']] = $item;
        }
        $packageRepo = EntityManager::getRepository(Package::class);

        $packageList = $packageRepo->findBy(['packageId' => $ids]);
        $orderedPackage = [];
        foreach ($packageList as $value) {
            $orderedPackage[$value->getPackageId()] = $value;
        }

        foreach ($config as $c) {
            $package = $orderedPackage[$c['id']];

            if (isset($c['flag']) && $c['flag'] == 1) {
                $package->setIsMobileMarked(true);
                $package->setIsMarked(true);
            }
            $result[] = $package;
        }

        return $result;
    }

    /**
     * FeaturePaymentSet constructor.
     *
     * @param int|PaymentMethod $paymentMethod
     * @param string            $name
     * @param string            $popupTitle
     * @param array             $packages
     */
    public function __construct($paymentMethod, string $name, string $popupTitle, array $packages, $showNames = true)
    {
        $this->setPaymentMethod($paymentMethod);
        $this->setName($name);
        $this->setPopupTitle($popupTitle);
        $this->setPackages($packages);
        $this->setShowNames($showNames);
        $this->setMobileSpecialOfferOnly(true);
        $this->setCommonOption([]);
    }

    /**
     * @param $popupTitle
     *
     * @return $this
     */
    public function setPopupTitle($popupTitle)
    {
        $this->popupTitle = $popupTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPopupTitle()
    {
        return $this->popupTitle;
    }

    /**
     * @param $showNames
     *
     * @return $this
     */
    public function setShowNames($showNames)
    {
        $this->showNames = (bool) $showNames;
        return $this;
    }

    /**
     * @return bool
     */
    public function getShowNames()
    {
        return $this->showNames;
    }

    /**
     * @param $showNames
     *
     * @return $this
     */
    public function setMobileSpecialOfferOnly($val)
    {
        $this->mobileSpecialOfferOnly = (bool)$val;

        return $this;
    }

    /**
     * @return bool
     */
    public function getMobileSpecialOfferOnly()
    {
        return $this->mobileSpecialOfferOnly;
    }

    /**
     * @param array $packages
     *
     * @return $this
     */
    public function setPackages(array $packages)
    {
        $this->packages = $packages;
        return $this;
    }

    /**
     * @return array
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @return array
     */
    public function getCommonOption()
    {
        return $this->commonOption;
    }

    /**
     * @param array $commonOption
     */
    public function setCommonOption(array $commonOption)
    {
        $this->commonOption = $commonOption;
    }

    /**
     * @param int|PaymentMethod $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = PaymentMethod::convert($paymentMethod);
        return $this;
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
}

