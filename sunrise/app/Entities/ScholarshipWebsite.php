<?php namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Http\UploadedFile;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\ScholarshipWebsiteRepository")
 * @ORM\Table(name="scholarship_website")
 * @ORM\HasLifecycleCallbacks()
 */
class ScholarshipWebsite implements JsonApiResource
{
    use Timestamps;

    const LOGO_WIDTH = 200;
    const LOGO_HEIGHT = 50;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_website';
    }

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $domain;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $domainHosted = true;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $layout;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $variant;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $companyName;

    /**
     * @var ScholarshipWebsiteFile
     * @ORM\OneToOne(targetEntity="ScholarshipWebsiteFile", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $logo;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $intro;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gtm;

    /**
     * @var ScholarshipTemplate
     * @ORM\OneToOne(targetEntity="ScholarshipTemplate", mappedBy="website")
     */
    protected $template;

    /**
     * Upload file to cloud before save.
     *
     * @ORM\PrePersist()
     * @ORM\PreFlush()
     */
    public function uploadImageFile()
    {
        if ($this->logo instanceof UploadedFile) {
            /**
             * Resize logo
             */
            if ($this->logo->extension() !== 'svg') {
                \Image::make($this->logo)->fit(self::LOGO_WIDTH, self::LOGO_HEIGHT)->save();
            }

            $this->logo = ScholarshipWebsiteFile::uploaded($this->logo)->setWebsite($this);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return bool
     */
    public function isDomainHosted()
    {
        return $this->domainHosted;
    }

    /**
     * @param $domainHosted
     * @return $this
     */
    public function setDomainHosted($domainHosted)
    {
        $this->domainHosted = $domainHosted;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomainFull()
    {
        return $this->isDomainHosted() ?
            sprintf('%s.%s', $this->getDomain(), config('services.barn.hosted_domain')) : $this->getDomain();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->isDomainHosted() ?
            sprintf('%s://%s', config('services.barn.hosted_scheme'), $this->getDomainFull()) :
            sprintf('https://%s', $this->getDomain());
    }

    /**
     * @return string
     */
    public function getPrivacyPolicyUrl()
    {
        return sprintf('%s/privacy-policy', $this->getUrl());
    }

    /**
     * @return string
     */
    public function getTermsOfUseUrl()
    {
        return sprintf('%s/terms-of-use', $this->getUrl());
    }

    /**
     * @param string $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Variant type of a layout. Dark, light for example.
     *
     * @param string $variant
     * @return $this
     */
    public function setVariant($variant)
    {
        $this->variant = $variant;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * @param string $companyName
     * @return $this
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $intro
     * @return $this
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
        return $this;
    }

    /**
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param string $gtm
     * @return $this
     */
    public function setGtm($gtm)
    {
        $this->gtm = $gtm;
        return $this;
    }

    /**
     * @return string
     */
    public function getGtm()
    {
        return $this->gtm;
    }

    /**
     * @return ScholarshipTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param ScholarshipTemplate $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
}
