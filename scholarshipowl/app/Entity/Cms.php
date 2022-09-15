<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cms
 *
 * @ORM\Table(name="cms", indexes={@ORM\Index(name="ix_cms_url", columns={"url"})})
 * @ORM\Entity
 */
class Cms
{
    /**
     * @var integer
     *
     * @ORM\Column(name="cms_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cmsId;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=127, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="page", type="string", length=127, nullable=false)
     */
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=127, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=2045, nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2045, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=2045, nullable=false)
     */
    private $author;


    /**
     * Cms constructor.
     *
     * @param string $url
     * @param string $page
     * @param string $author
     * @param string $title
     * @param string $description
     * @param string $keywords
     */
    public function __construct(
        string $url,
        string $page,
        string $author = '',
        string $title = '',
        string $description = '',
        string $keywords = ''
    )
    {
        $this->setUrl($url);
        $this->setPage($page);
        $this->setAuthor($author);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setKeywords($keywords);
    }

    /**
     * Get cmsId
     *
     * @return integer
     */
    public function getCmsId()
    {
        return $this->cmsId;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Cms
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
     * Set page
     *
     * @param string $page
     *
     * @return Cms
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Cms
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
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Cms
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Cms
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
     * Set author
     *
     * @param string $author
     *
     * @return Cms
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }
}

