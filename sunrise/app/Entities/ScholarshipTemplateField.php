<?php namespace App\Entities;

use App\Entities\Super\AbstractScholarshipField;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ScholarshipTemplateField extends AbstractScholarshipField implements JsonApiResource
{
    /**
     * @return string
     */
    static public function getResourceKey()
    {
        return 'scholarship_template_field';
    }

    /**
     * @var ScholarshipTemplate
     * @ORM\ManyToOne(targetEntity="ScholarshipTemplate", inversedBy="fields")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $template;

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
}
