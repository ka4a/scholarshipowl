<?php namespace App\Entity;

use App\Entity\Traits\CloudFile;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Filesystem\Filesystem;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * BannerImage
 *
 * @ORM\Table(name="banner_image", indexes={@ORM\Index(name="banner_image_banner_id_foreign", columns={"banner_id"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class BannerImage
{
    use Timestamps;
    use CloudFile;

    const CLOUD_PATH = '/b/i/%s/%s';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Banner
     *
     * @ORM\OneToOne(targetEntity="Banner", mappedBy="image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="banner_id", referencedColumnName="id")
     * })
     */
    private $banner;

    /**
     * Update path after path element changes.
     */
    protected function updatePath()
    {
        if (($banner = $this->getBanner()) && ($file = $this->getFile())) {
            $this->setPath(sprintf(static::CLOUD_PATH, $banner->getId(), $this->getFileName($file)));
        }
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return Filesystem::VISIBILITY_PUBLIC;
    }

    /**
     * BannerImage constructor.
     *
     * @param Banner $banner
     * @param File   $image
     */
    public function __construct(Banner $banner, File $image)
    {
        $this->setImage($image);
        $this->setBanner($banner);
    }

    /**
     * @param File $image
     *
     * @return $this
     */
    public function setImage(File $image)
    {
        $this->setFile($image);
        $this->updatePath();

        return $this;
    }

    /**
     * @return File
     */
    public function getImage()
    {
        return $this->getFile();
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
     * Set banner
     *
     * @param \App\Entity\Banner $banner
     *
     * @return BannerImage
     */
    public function setBanner(Banner $banner = null)
    {
        $this->banner = $banner;
        $this->updatePath();

        return $this;
    }

    /**
     * Get banner
     *
     * @return \App\Entity\Banner
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @param File $file
     *
     * @return string
     */
    protected function getFileName(File $file)
    {
        $fileName = $file instanceof UploadedFile ? $file->getClientOriginalExtension() : $file->getFilename();
        $fileExtension = $file instanceof UploadedFile ? $file->getClientOriginalExtension() : $file->getExtension();

        return sprintf('%s.%s', substr(md5($fileName), 0, 16), $fileExtension);
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->getPublicUrl();
    }
}

