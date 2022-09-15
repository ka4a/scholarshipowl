<?php namespace App\Entities;

use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @Gedmo\Loggable(logEntryClass="ScholarshipTemplateLog");
 * @ORM\Table(
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="ix_template_type", columns={"template_id","type"}),
 *     }
 * )
 */
class ScholarshipTemplateContent implements JsonApiResource
{
    use Timestamps;

    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_template_content';
    }

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    protected $id;

    /**
     * @var ScholarshipTemplate
     *
     * @ORM\ManyToOne(targetEntity="ScholarshipTemplate", inversedBy="content")
     */
    protected $template;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Gedmo\Versioned()
     */
    protected $content;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function setTemplate(ScholarshipTemplate $template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}
