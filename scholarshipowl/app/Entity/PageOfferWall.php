<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * PageOfferWall
 *
 * @ORM\Table(name="page_offer_wall")
 * @ORM\Entity
 */
class PageOfferWall
{
    use Timestamps;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var Banner
     *
     * @ORM\ManyToOne(targetEntity="Banner", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="banner1", referencedColumnName="id")
     * })
     */
    private $banner1;

    /**
     * @var Banner
     *
     * @ORM\ManyToOne(targetEntity="Banner", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="banner2", referencedColumnName="id")
     * })
     */
    private $banner2;

    /**
     * @var Banner
     *
     * @ORM\ManyToOne(targetEntity="Banner", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="banner3", referencedColumnName="id")
     * })
     */
    private $banner3;

    /**
     * @var Banner
     *
     * @ORM\ManyToOne(targetEntity="Banner", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="banner4", referencedColumnName="id")
     * })
     */
    private $banner4;

    /**
     * @var Banner
     *
     * @ORM\ManyToOne(targetEntity="Banner", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="banner5", referencedColumnName="id")
     * })
     */
    private $banner5;

    /**
     * @var Banner
     *
     * @ORM\ManyToOne(targetEntity="Banner", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="banner6", referencedColumnName="id")
     * })
     */
    private $banner6;

    /**
     * @var Page
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Page")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;

    /**
     * PageOfferWall constructor.
     *
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->setPage($page);
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PageOfferWall
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
     * Set description
     *
     * @param string $description
     *
     * @return PageOfferWall
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
     * Set banner1
     *
     * @param Banner $banner1
     *
     * @return PageOfferWall
     */
    public function setBanner1(Banner $banner1 = null)
    {
        $this->banner1 = $banner1;

        return $this;
    }

    /**
     * Get banner1
     *
     * @return Banner
     */
    public function getBanner1()
    {
        return $this->banner1;
    }

    /**
     * Set banner2
     *
     * @param Banner $banner2
     *
     * @return PageOfferWall
     */
    public function setBanner2(Banner $banner2 = null)
    {
        $this->banner2 = $banner2;

        return $this;
    }

    /**
     * Get banner2
     *
     * @return Banner
     */
    public function getBanner2()
    {
        return $this->banner2;
    }

    /**
     * Set banner3
     *
     * @param Banner $banner3
     *
     * @return PageOfferWall
     */
    public function setBanner3(Banner $banner3 = null)
    {
        $this->banner3 = $banner3;

        return $this;
    }

    /**
     * Get banner3
     *
     * @return Banner
     */
    public function getBanner3()
    {
        return $this->banner3;
    }

    /**
     * Set banner4
     *
     * @param Banner $banner4
     *
     * @return PageOfferWall
     */
    public function setBanner4(Banner $banner4 = null)
    {
        $this->banner4 = $banner4;

        return $this;
    }

    /**
     * Get banner4
     *
     * @return Banner
     */
    public function getBanner4()
    {
        return $this->banner4;
    }

    /**
     * Set banner5
     *
     * @param Banner $banner5
     *
     * @return PageOfferWall
     */
    public function setBanner5(Banner $banner5 = null)
    {
        $this->banner5 = $banner5;

        return $this;
    }

    /**
     * Get banner5
     *
     * @return Banner
     */
    public function getBanner5()
    {
        return $this->banner5;
    }

    /**
     * Set banner6
     *
     * @param Banner $banner6
     *
     * @return PageOfferWall
     */
    public function setBanner6(Banner $banner6 = null)
    {
        $this->banner6 = $banner6;

        return $this;
    }

    /**
     * Get banner6
     *
     * @return Banner
     */
    public function getBanner6()
    {
        return $this->banner6;
    }

    /**
     * @return array|Banner[]
     */
    public function getBanners()
    {
        $banners = [];

        if ($this->getBanner1()) {
            $banners[] = $this->getBanner1();
        }

        if ($this->getBanner2()) {
            $banners[] = $this->getBanner2();
        }

        if ($this->getBanner3()) {
            $banners[] = $this->getBanner3();
        }

        if ($this->getBanner4()) {
            $banners[] = $this->getBanner4();
        }

        if ($this->getBanner5()) {
            $banners[] = $this->getBanner5();
        }

        if ($this->getBanner6()) {
            $banners[] = $this->getBanner6();
        }

        return $banners;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return PageOfferWall
     */
    public function setPage(Page $page)
    {
        $this->page = $page->setOfferWall($this);

        return $this;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }
}

