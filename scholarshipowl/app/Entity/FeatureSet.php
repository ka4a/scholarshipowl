<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * FeatureSet
 *
 * @ORM\Table(name="feature_set", uniqueConstraints={@ORM\UniqueConstraint(name="feature_set_name_unique", columns={"name"})}, indexes={@ORM\Index(name="feature_set_feature_payment_set_id_foreign", columns={"feature_payment_set_id"})})
 * @ORM\Entity
 */
class FeatureSet
{
    use Dictionary;
    use Timestamps;

    const DEFAULT_SET = 1;

    const FREEMIUM_MVP_NAME = "FreemiumMVP";
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
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @var FeaturePaymentSet
     *
     * @ORM\ManyToOne(targetEntity="FeaturePaymentSet", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="desktop_payment_set", referencedColumnName="id")
     * })
     */
    private $desktopPaymentSet;

    /**
     * @var FeaturePaymentSet
     *
     * @ORM\ManyToOne(targetEntity="FeaturePaymentSet", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mobile_payment_set", referencedColumnName="id")
     * })
     */
    private $mobilePaymentSet;

    /**
     * @var FeatureContentSet
     *
     * @ORM\ManyToOne(targetEntity="FeatureContentSet", fetch="EAGER")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="content_set", referencedColumnName="id")
     * })
     */
    private $contentSet;

    /**
     * @var FeatureSet
     */
    protected static $current;

    /**
     * @return FeatureSet
     */
    public static function config()
    {
        if (static::$current === null) {
            static::$current = static::find(static::DEFAULT_SET);
        }

        return static::$current;
    }

    /**
     * @return FeatureContentSet
     */
    public static function content()
    {
        return static::config()->getContentSet();
    }

    /**
     * @param int|FeatureSet $current
     */
    public static function set($current)
    {
        static::$current = $current === null ? null : static::convert($current);
    }

    /**
     * FeatureSet constructor.
     *
     * @param string                $name
     * @param int|FeaturePaymentSet $desktopPaymentSet
     * @param int|FeaturePaymentSet $mobilePaymentSet
     * @param int|FeatureContentSet $homepageBlock
     */
    public function __construct(string $name, $desktopPaymentSet, $mobilePaymentSet, $homepageBlock)
    {
        $this->setName($name);
        $this->setDesktopPaymentSet($desktopPaymentSet);
        $this->setMobilePaymentSet($mobilePaymentSet);
        $this->setContentSet($homepageBlock);
    }

    /**
     * @param int|FeaturePaymentSet $desktopPaymentSet
     *
     * @return $this
     */
    public function setDesktopPaymentSet($desktopPaymentSet)
    {
        $this->desktopPaymentSet = FeaturePaymentSet::convert($desktopPaymentSet);
        return $this;
    }

    /**
     * @return FeaturePaymentSet
     */
    public function getDesktopPaymentSet()
    {
        return $this->desktopPaymentSet;
    }

    /**
     * @param int|FeaturePaymentSet $mobilePaymentSet
     *
     * @return $this
     */
    public function setMobilePaymentSet($mobilePaymentSet)
    {
        $this->mobilePaymentSet = FeaturePaymentSet::convert($mobilePaymentSet);
        return $this;
    }

    /**
     * @return FeaturePaymentSet
     */
    public function getMobilePaymentSet()
    {
        return $this->mobilePaymentSet;
    }

    /**
     * @param int|FeatureContentSet $contentSet
     *
     * @return $this
     */
    public function setContentSet($contentSet)
    {
        $this->contentSet = FeatureContentSet::convert($contentSet);
        return $this;
    }

    /**
     * @return FeatureContentSet
     */
    public function getContentSet()
    {
        return $this->contentSet;
    }

    /**
     * @param bool $deleted
     *
     * @return $this
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }
}

