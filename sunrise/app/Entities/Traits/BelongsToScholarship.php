<?php namespace App\Entities\Traits;

use App\Entities\Scholarship;
use Doctrine\ORM\Mapping as ORM;

trait BelongsToScholarship
{
    /**
     * @var Scholarship
     * @ORM\OneToOne(targetEntity="Scholarship")
     * @ORM\JoinColumn(name="scholarship_id")
     */
    protected $scholarship;

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
}
