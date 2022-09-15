<?php namespace App\Entities;

use App\Events\ScholarshipWinnerPublished;
use Illuminate\Http\UploadedFile;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class ScholarshipWinner implements JsonApiResource
{
    use Timestamps;

    const PHOTO_SIZE = 300;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_winner';
    }

    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    protected $testimonial;

    /**
     * @var ScholarshipFile
     * @ORM\OneToOne(targetEntity="ScholarshipFile", cascade={"persist"})
     */
    protected $image;

    /**
     * @var Scholarship
     * @ORM\ManyToOne(targetEntity="Scholarship", inversedBy="winners")
     */
    protected $scholarship;

    /**
     * @var ApplicationWinner
     * @ORM\OneToOne(targetEntity="ApplicationWinner", inversedBy="scholarshipWinner")
     * @ORM\JoinColumn(nullable=false, unique=true)
     */
    protected $applicationWinner;

    /**
     * Upload file to cloud before save.
     *
     * @ORM\PrePersist()
     * @ORM\PreFlush()
     */
    public function uploadImageFile()
    {
        if ($this->image instanceof UploadedFile) {
            \Image::make($this->image)->fit(self::PHOTO_SIZE)->save();
            $this->image = ScholarshipFile::uploaded($this->image)
                ->setScholarship($this->getScholarship());
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
     * @param Scholarship $scholarship
     * @return $this
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;
        return $this;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @param string $testimonial
     * @return $this
     */
    public function setTestimonial($testimonial)
    {
        $this->testimonial = $testimonial;
        return $this;
    }

    /**
     * @return string
     */
    public function getTestimonial()
    {
        return $this->testimonial;
    }

    /**
     * @param UploadedFile|ScholarshipFile $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return ScholarshipFile
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param ApplicationWinner $applicationWinner
     * @return $this
     */
    public function setApplicationWinner(ApplicationWinner $applicationWinner)
    {
        $this->applicationWinner = $applicationWinner;
        $this->setScholarship($applicationWinner->getApplication()->getScholarship());
        return $this;
    }

    /**
     * @return ApplicationWinner
     */
    public function getApplicationWinner()
    {
        return $this->applicationWinner;
    }
}
