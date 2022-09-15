<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Filesystem\Filesystem;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * Banner
 *
 * @ORM\Table(name="banner")
 * @ORM\Entity
 */
class Banner
{
    use Timestamps;

    const TYPE_IMAGE = 1;
    const TYPE_TEXT = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="url_display", type="string", nullable=true)
     */
    private $urlDisplay;

    /**
     * @var string
     *
     * @ORM\Column(name="header_content", type="string", length=255, nullable=true)
     */
    private $headerContent;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255, nullable=true)
     */
    private $text;

    /**
     * @var BannerImage
     *
     * @ORM\OneToOne(targetEntity="BannerImage", fetch="EAGER", mappedBy="banner", cascade={"remove"}, orphanRemoval=true)
     */
    private $image;

    /**
     * @return array|string[]
     */
    public static function types()
    {
        return [
            static::TYPE_IMAGE => 'Image',
            static::TYPE_TEXT => 'Text',
        ];
    }

    /**
     * @param $type
     *
     * @return null|string
     */
    public static function type($type)
    {
        return static::types()[$type] ?? null;
    }

    /**
     * @return string
     */
    public function getVisibility()
    {
        return Filesystem::VISIBILITY_PUBLIC;
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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Banner
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null|string
     */
    public function getTypeName()
    {
        return static::type($this->getType());
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Banner
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
     * @param $urlDisplay
     *
     * @return $this
     */
    public function setUrlDisplay($urlDisplay)
    {
        $this->urlDisplay = $urlDisplay;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlDisplay()
    {
        return $this->urlDisplay;
    }

    /**
     * Set image
     *
     * @param BannerImage $image
     *
     * @return Banner
     */
    public function setImage(BannerImage $image)
    {
        $this->image = $image->setBanner($this);

        return $this;
    }

    /**
     * Get image
     *
     * @return BannerImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set headerContent
     *
     * @param string $headerContent
     *
     * @return Banner
     */
    public function setHeaderContent($headerContent)
    {
        $this->headerContent = $headerContent;

        return $this;
    }

    /**
     * Get headerContent
     *
     * @return string
     */
    public function getHeaderContent()
    {
        return $this->headerContent;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}

