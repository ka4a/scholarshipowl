<?php namespace App\Entity;

use App\Entity\Traits\Hydratable;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * @ORM\Entity()
 * @ORM\Table(name="special_offer_page")
 */
class SpecialOfferPage
{
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
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_title1", type="string", length=255, nullable=false)
     */
    private $iconTitle1;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_title2", type="string", length=255, nullable=false)
     */
    private $iconTitle2;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_title3", type="string", length=255, nullable=false)
     */
    private $iconTitle3;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="scroll_to_text", type="string", length=255, nullable=true)
     */
    private $scrollToText;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_author", type="string", length=255, nullable=true)
     */
    private $metaAuthor;

    /**
     * @var Package
     *
     * @ORM\ManyToOne(targetEntity="Package")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="package_id", referencedColumnName="package_id")
     * })
     */
    private $package;

    /**
     * SpecialOfferPage constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return SpecialOfferPage
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return SpecialOfferPage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set iconTitle1
     *
     * @param string $iconTitle1
     *
     * @return SpecialOfferPage
     */
    public function setIconTitle1($iconTitle1)
    {
        $this->iconTitle1 = $iconTitle1;

        return $this;
    }

    /**
     * Get iconTitle1
     *
     * @return string
     */
    public function getIconTitle1()
    {
        return $this->iconTitle1;
    }

    /**
     * Set iconTitle2
     *
     * @param string $iconTitle2
     *
     * @return SpecialOfferPage
     */
    public function setIconTitle2($iconTitle2)
    {
        $this->iconTitle2 = $iconTitle2;

        return $this;
    }

    /**
     * Get iconTitle2
     *
     * @return string
     */
    public function getIconTitle2()
    {
        return $this->iconTitle2;
    }

    /**
     * Set iconTitle3
     *
     * @param string $iconTitle3
     *
     * @return SpecialOfferPage
     */
    public function setIconTitle3($iconTitle3)
    {
        $this->iconTitle3 = $iconTitle3;

        return $this;
    }

    /**
     * Get iconTitle3
     *
     * @return string
     */
    public function getIconTitle3()
    {
        return $this->iconTitle3;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return SpecialOfferPage
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
     * @param $scrollToText
     *
     * @return $this
     */
    public function setScrollToText($scrollToText)
    {
        $this->scrollToText = $scrollToText;

        return $this;
    }

    /**
     * @return string
     */
    public function getScrollToText()
    {
        return $this->scrollToText;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return SpecialOfferPage
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return SpecialOfferPage
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     *
     * @return SpecialOfferPage
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaAuthor
     *
     * @param string $metaAuthor
     *
     * @return SpecialOfferPage
     */
    public function setMetaAuthor($metaAuthor)
    {
        $this->metaAuthor = $metaAuthor;

        return $this;
    }

    /**
     * Get metaAuthor
     *
     * @return string
     */
    public function getMetaAuthor()
    {
        return $this->metaAuthor;
    }

    /**
     * Set package
     *
     * @param \App\Entity\Package $package
     *
     * @return SpecialOfferPage
     */
    public function setPackage(Package $package = null)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return \App\Entity\Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return string
     */
    public function getFullUrl()
    {
        return \URL::route('special-offer-page', $this->getUrl());
    }
}

