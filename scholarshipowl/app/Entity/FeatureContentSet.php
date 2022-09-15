<?php namespace App\Entity;

use App\Contracts\MappingTags;
use App\Entity\Traits\Dictionary;
use App\Entity\Traits\Hydratable;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * FeatureBlock
 *
 * @ORM\Table(name="feature_content_set", uniqueConstraints={@ORM\UniqueConstraint(name="feature_block_name_unique", columns={"name"})})
 * @ORM\Entity
 */
class FeatureContentSet
{
    use Dictionary;
    use Timestamps;
    use Hydratable;

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
     * @ORM\Column(name="homepage_header", type="text", length=65535, nullable=false)
     */
    private $homepageHeader;

    /**
     * @var string
     *
     * @ORM\Column(name="register_header", type="text", nullable=false)
     */
    private $registerHeader;

    /**
     * @var string
     *
     * @ORM\Column(name="register_heading_text", type="text", nullable=false)
     */
    private $registerHeadingText;

    /**
     * @var string
     *
     * @ORM\Column(name="register_subheading_text", type="text", nullable=false)
     */
    private $registerSubheadingText;

    /**
     * @var boolean
     *
     * @ORM\Column(name="register_hide_footer", type="boolean", nullable=false)
     */
    private $registerHideFooter = false;

    /**
     * @var string
     *
     * @ORM\Column(name="register_cta_text", type="text", nullable=false)
     */
    private $registerCtaText;

    /**
     * @var string
     *
     * @ORM\Column(name="register_illustration",  type="string", length=255, nullable=true)
     */
    private $registerIllustration;

    /**
     * @var string
     *
     * @ORM\Column(name="select_apply_now", type="string", nullable=false)
     */
    private $selectApplyNow = 'apply now';

    /**
     * @var bool
     *
     * @ORM\Column(name="select_hide_checkboxes", type="boolean", nullable=false)
     */
    private $selectHideCheckboxes = false;

    /**
     * @var string
     *
     * @ORM\Column(name="application_sent_title", type="string", length=255, nullable=true)
     */
    private $applicationSentTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="application_sent_description", type="string", length=255, nullable=true)
     */
    private $applicationSentDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="application_sent_content", type="string", length=255, nullable=true)
     */
    private $applicationSentContent;

    /**
     * @var string
     *
     * @ORM\Column(name="no_credits_title", type="string", length=255, nullable=true)
     */
    private $noCreditsTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="no_credits_description", type="string", length=255, nullable=true)
     */
    private $noCreditsDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="no_credits_content", type="string", length=255, nullable=true)
     */
    private $noCreditsContent;

    /**
     * @var string
     *
     * @ORM\Column(name="upgrade_block_text", type="string", length=255, nullable=true)
     */
    private $upgradeBlockText;

    /**
     * @var string
     *
     * @ORM\Column(name="upgrade_block_link_upgrade", type="string", length=255, nullable=true)
     */
    private $upgradeBlockLinkUpgrade;

    /**
     * @var string
     *
     * @ORM\Column(name="upgrade_block_link_vip", type="string", length=255, nullable=true)
     */
    private $upgradeBlockLinkVip;


    /**
     * @var bool
     *
     * @ORM\Column(name="hp_double_promotion_flag", type="boolean", nullable=false)
     */
    private $hpDoublePromotionFlag = false;

    /**
     * @var string
     *
     * @ORM\Column(name="hp_ydi_flag", type="boolean", nullable=false)
     */
    private $hpYdiFlag = false;

    /**
     * @var string
     *
     * @ORM\Column(name="hp_cta_text", type="string", length=255, nullable=true)
     */
    private $hpCtaText;

    /**
     * @var string
     *
     * @ORM\Column(name="register2_heading_text", type="text", nullable=false)
     */
    private $register2HeadingText;

    /**
     * @var string
     *
     * @ORM\Column(name="register2_subheading_text", type="text", nullable=false)
     */
    private $register2SubheadingText;

    /**
     * @var string
     *
     * @ORM\Column(name="register2_cta_text", type="text", nullable=false)
     */
    private $register2CtaText;

    /**
     * @var string
     *
     * @ORM\Column(name="register2_illustration",  type="string", length=255, nullable=true)
     */
    private $register2Illustration;

    /**
     * @var string
     *
     * @ORM\Column(name="register3_heading_text", type="text", nullable=false)
     */
    private $register3HeadingText;

    /**
     * @var string
     *
     * @ORM\Column(name="register3_subheading_text", type="text", nullable=false)
     */
    private $register3SubheadingText;

    /**
     * @var string
     *
     * @ORM\Column(name="register3_cta_text", type="text", nullable=false)
     */
    private $register3CtaText;

    /**
     * @var string
     *
     * @ORM\Column(name="register3_illustration",  type="string", length=255, nullable=true)
     */
    private $register3Illustration;

    /**
     * @var string
     *
     * @ORM\Column(name="pp_header_text", type="text", nullable=false)
     */
    private $ppHeaderText;

    /**
     * @var string
     *
     * @ORM\Column(name="pp_header_text_2", type="text", nullable=false)
     */
    private $ppHeaderText2;

    /**
     *
     * @ORM\Column(name="pp_carousel_items_cnt", type="integer", nullable=true)
     */
    private $ppCarouselItemsCnt;

    /**
     * FeatureContentSet constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * @param $homepageHeader
     *
     * @return $this
     */
    public function setHomepageHeader($homepageHeader)
    {
        $this->homepageHeader = $homepageHeader;
        return $this;
    }

    /**
     * @return string
     */
    public function getHomepageHeader()
    {
        return $this->homepageHeader;
    }

    /**
     * @param array $providers
     *
     * @return string
     */
    public function mapHomepageHeader(array $providers = [])
    {
        return $this->getMappedText($this->getHomepageHeader(), $providers);
    }

    /**
     * @param $registerHeader
     *
     * @return $this
     */
    public function setRegisterHeader($registerHeader)
    {
        $this->registerHeader = $registerHeader;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterHeader()
    {
        return $this->registerHeader;
    }

    /**
     * @param $registerHeadingText
     *
     * @return $this
     */
    public function setRegisterHeadingText($registerHeadingText)
    {
        $this->registerHeadingText = $registerHeadingText;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterHeadingText()
    {
        return $this->registerHeadingText;
    }

    /**
     * @param $registerSubheadingText
     *
     * @return $this
     */
    public function setRegisterSubheadingText($registerSubheadingText)
    {
        $this->registerSubheadingText = $registerSubheadingText;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterSubheadingText()
    {
        return $this->registerSubheadingText;
    }

    /**
     * @param $hide
     *
     * @return $this
     */
    public function setRegisterHideFooter($hide)
    {
        $this->registerHideFooter = (bool) $hide;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRegisterHideFooter()
    {
        return $this->registerHideFooter;
    }

    /**
     * @param $registerCtaText
     * @return $this
     */
    public function setRegisterCtaText($registerCtaText)
    {
        $this->registerCtaText = $registerCtaText;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterCtaText()
    {
        return $this->registerCtaText;
    }

    /**
     * @param $selectApplyNow
     *
     * @return $this
     */
    public function setSelectApplyNow($selectApplyNow)
    {
        $this->selectApplyNow = $selectApplyNow;
        return $this;
    }

    /**
     * @return string
     */
    public function getSelectApplyNow()
    {
        return $this->selectApplyNow;
    }


    /**
     * @param $selectHideCheckboxes
     *
     * @return $this
     */
    public function setSelectHideCheckboxes($selectHideCheckboxes)
    {
        $this->selectHideCheckboxes = (bool) $selectHideCheckboxes;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSelectHideCheckboxes()
    {
        return $this->selectHideCheckboxes;
    }

    /**
     * @return bool
     */
    public function hideFooter()
    {
        $routes = ['register', 'register2', 'register3'];
        return is_mobile() && in_array(\Request::route()->getName(), $routes) && $this->getRegisterHideFooter();
    }

    /**
     * @param array $providers
     *
     * @return string
     */
    public function mapRegisterHeader(array $providers = [])
    {
        return $this->getMappedText($this->getRegisterHeader(), $providers);
    }

    /**
     * @param string    $text
     * @param array     $providers
     *
     * @return string
     */
    protected function getMappedText(string $text, array $providers = [])
    {
        if (\Auth::user() instanceof MappingTags) {
            array_unshift($providers, \Auth::user());
        }

        return map_tags_provider($text, $providers);
    }

    /**
     * @return string
     */
    public function getApplicationSentTitle()
    {
        return $this->applicationSentTitle;
    }

    /**
     * @param string $applicationSentTitle
     *
     * @return $this
     */
    public function setApplicationSentTitle($applicationSentTitle)
    {
        $this->applicationSentTitle = $applicationSentTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationSentDescription()
    {
        return $this->applicationSentDescription;
    }

    /**
     * @param string $applicationSentDescription
     *
     * @return $this
     */
    public function setApplicationSentDescription( $applicationSentDescription) {
        $this->applicationSentDescription = $applicationSentDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationSentContent()
    {
        return $this->applicationSentContent;
    }

    /**
     * @param string $applicationSentContent
     *
     * @return $this
     */
    public function setApplicationSentContent($applicationSentContent)
    {
        $this->applicationSentContent = $applicationSentContent;
        return $this;
    }

    /**
     * @return string
     */
    public function getNoCreditsTitle()
    {
        return $this->noCreditsTitle;
    }

    /**
     * @param string $noCreditsTitle
     *
     * @return $this
     */
    public function setNoCreditsTitle($noCreditsTitle)
    {
        $this->noCreditsTitle = $noCreditsTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getNoCreditsDescription()
    {
        return $this->noCreditsDescription;
    }

    /**
     * @param string $noCreditsDescription
     *
     * @return $this
     */
    public function setNoCreditsDescription($noCreditsDescription)
    {
        $this->noCreditsDescription = $noCreditsDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getNoCreditsContent()
    {
        return $this->noCreditsContent;
    }

    /**
     * @param string $noCreditsContent
     *
     * @return $this
     */
    public function setNoCreditsContent($noCreditsContent)
    {
        $this->noCreditsContent = $noCreditsContent;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpgradeBlockText()
    {
        return $this->upgradeBlockText;
    }

    /**
     * @param string $upgradeBlockText
     *
     * @return FeatureContentSet
     */
    public function setUpgradeBlockText($upgradeBlockText)
    {
        $this->upgradeBlockText = $upgradeBlockText;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpgradeBlockLinkUpgrade()
    {
        return $this->upgradeBlockLinkUpgrade;
    }

    /**
     * @param string $upgradeBlockLinkUpgrade
     *
     * @return FeatureContentSet
     */
    public function setUpgradeBlockLinkUpgrade($upgradeBlockLinkUpgrade)
    {
        $this->upgradeBlockLinkUpgrade = $upgradeBlockLinkUpgrade;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpgradeBlockLinkVip()
    {
        return $this->upgradeBlockLinkVip;
    }

    /**
     * @param string $upgradeBlockLinkVip
     *
     * @return FeatureContentSet
     */
    public function setUpgradeBlockLinkVip($upgradeBlockLinkVip)
    {
        $this->upgradeBlockLinkVip = $upgradeBlockLinkVip;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHpDoublePromotionFlag()
    {
        return $this->hpDoublePromotionFlag;
    }

    /**
     * @param bool $hpDoublePromotionFlag
     */
    public function setHpDoublePromotionFlag($hpDoublePromotionFlag)
    {
        $this->hpDoublePromotionFlag = $hpDoublePromotionFlag;
    }

    /**
     * @return string
     */
    public function isHpYdiFlag()
    {
        return $this->hpYdiFlag;
    }

    /**
     * @param string $hpYdiFlag
     */
    public function setHpYdiFlag($hpYdiFlag)
    {
        $this->hpYdiFlag = $hpYdiFlag;
    }

    /**
     * @return string
     */
    public function getHpCtaText()
    {
        return $this->hpCtaText;
    }

    /**
     * @param string $hpCtaText
     */
    public function setHpCtaText($hpCtaText)
    {
        $this->hpCtaText = $hpCtaText;
    }

    /**
     * @return string
     */
    public function getRegister2HeadingText()
    {
        return $this->register2HeadingText;
    }

    /**
     * @param string $register2HeadingText
     */
    public function setRegister2HeadingText(string $register2HeadingText)
    {
        $this->register2HeadingText = $register2HeadingText;
    }

    /**
     * @return string
     */
    public function getRegister2SubheadingText()
    {
        return $this->register2SubheadingText;
    }

    /**
     * @param string $register2SubheadingText
     */
    public function setRegister2SubheadingText(string $register2SubheadingText)
    {
        $this->register2SubheadingText = $register2SubheadingText;
    }

    /**
     * @return string
     */
    public function getRegister2CtaText()
    {
        return $this->register2CtaText;
    }

    /**
     * @param string $register2CtaText
     */
    public function setRegister2CtaText(string $register2CtaText)
    {
        $this->register2CtaText = $register2CtaText;
    }

    /**
     * @return string
     */
    public function getRegister3HeadingText()
    {
        return $this->register3HeadingText;
    }

    /**
     * @param string $register3HeadingText
     */
    public function setRegister3HeadingText(string $register3HeadingText)
    {
        $this->register3HeadingText = $register3HeadingText;
    }

    /**
     * @return string
     */
    public function getRegister3SubheadingText()
    {
        return $this->register3SubheadingText;
    }

    /**
     * @param string $register3SubheadingText
     */
    public function setRegister3SubheadingText(string $register3SubheadingText)
    {
        $this->register3SubheadingText = $register3SubheadingText;
    }

    /**
     * @return string
     */
    public function getRegister3CtaText()
    {
        return $this->register3CtaText;
    }

    /**
     * @param string $register3CtaText
     */
    public function setRegister3CtaText(string $register3CtaText)
    {
        $this->register3CtaText = $register3CtaText;
    }

    /**
     * @return string
     */
    public function getRegisterIllustration()
    {
        return $this->registerIllustration;
    }

    /**
     * @param string $registerIllustration
     */
    public function setRegisterIllustration(string $registerIllustration)
    {
        $this->registerIllustration = $registerIllustration;
    }

    /**
     * @return string
     */
    public function getRegister2Illustration()
    {
        return $this->register2Illustration;
    }

    /**
     * @param string $register2Illustration
     */
    public function setRegister2Illustration(string $register2Illustration)
    {
        $this->register2Illustration = $register2Illustration;
    }

    /**
     * @return string
     */
    public function getRegister3Illustration()
    {
        return $this->register3Illustration;
    }

    /**
     * @param string $register3Illustration
     */
    public function setRegister3Illustration(string $register3Illustration)
    {
        $this->register3Illustration = $register3Illustration;
    }
    /**
     * @return string
     */
    public function getPpHeaderText()
    {
        return $this->ppHeaderText;
    }

    /**
     * @param string $ppHeaderText
     */
    public function setPpHeaderText(string $ppHeaderText)
    {
        $this->ppHeaderText = $ppHeaderText;
    }

    /**
     * @return string
     */
    public function getPpHeaderText2()
    {
        return $this->ppHeaderText2;
    }

    /**
     * @param string $ppHeaderText2
     */
    public function setPpHeaderText2(string $ppHeaderText2)
    {
        $this->ppHeaderText2 = $ppHeaderText2;
    }

    /**
     * @return int
     */
    public function getPpCarouselItemsCnt()
    {
        return $this->ppCarouselItemsCnt;
    }

    /**
     * @param int $ppCarouselItemsCnt
     */
    public function setPpCarouselItemsCnt($ppCarouselItemsCnt)
    {
        $this->ppCarouselItemsCnt = $ppCarouselItemsCnt;
    }

}

