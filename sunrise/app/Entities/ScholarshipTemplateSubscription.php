<?php namespace App\Entities;

use App\Entities\Traits\BelongsToScholarshipTemplate;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Class ScholarshipTemplateSubscription
 * @ORM\Entity(repositoryClass="App\Repositories\ScholarshipTemplateSubscriptionRepository")
 */
class ScholarshipTemplateSubscription implements JsonApiResource
{
    use Timestamps;
    use BelongsToScholarshipTemplate;

    const STATUS_WAITING = 'waiting';
    const STATUS_NOTIFIED = 'notified';

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_template_subscription';
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
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    protected $status = self::STATUS_WAITING;

    /**
     * @var ScholarshipTemplate
     * @ORM\ManyToOne(targetEntity="ScholarshipTemplate")
     */
    protected $template;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ScholarshipTemplate $template
     * @return $this
     */
    public function setTemplate(ScholarshipTemplate $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return ScholarshipTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
