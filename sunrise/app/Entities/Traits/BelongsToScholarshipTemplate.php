<?php namespace App\Entities\Traits;

use App\Entities\ScholarshipTemplate;
use Doctrine\ORM\Mapping as ORM;

trait BelongsToScholarshipTemplate
{
    /**
     * @var ScholarshipTemplate
     * @ORM\OneToOne(targetEntity="ScholarshipTemplate")
     * @ORM\JoinColumn(name="scholarship_template_id")
     */
    protected $template;

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
}
