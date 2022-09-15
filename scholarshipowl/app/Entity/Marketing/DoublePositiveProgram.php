<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 13/12/2016
 */

namespace App\Entity\Marketing;

use App\Entity\DegreeType;
use Doctrine\ORM\Mapping as ORM;

/**
 * DoublePositiveProgram
 *
 * @ORM\Table(name="double_positive_program")
 * @ORM\Entity
 */
class DoublePositiveProgram
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var DegreeType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\DegreeType", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_type_id", referencedColumnName="degree_type_id")
     * })
     */
    private $degreeType;

    /**
     * @var string
     *
     * @ORM\Column(name="program", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $program;

    /**
     * @var string
     *
     * @ORM\Column(name="states", type="string", length=2045, precision=0, scale=0, nullable=false, unique=false)
     */
    private $states;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_hs_grad_year", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $minHsGradYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_hs_grad_year", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $maxHsGradYear;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isActive;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DegreeType
     */
    public function getDegreeType()
    {
        return $this->degreeType;
    }

    /**
     * @param DegreeType $degreeType
     */
    public function setDegreeType($degreeType)
    {
        $this->degreeType = $degreeType;
    }

    /**
     * @return string
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @param string $program
     */
    public function setProgram($program)
    {
        $this->program = $program;
    }

    /**
     * @return int
     */
    public function getMinHsGradYear()
    {
        return $this->minHsGradYear;
    }

    /**
     * @param int $minHsGradYear
     */
    public function setMinHsGradYear($minHsGradYear)
    {
        $this->minHsGradYear = $minHsGradYear;
    }

    /**
     * @return int
     */
    public function getMaxHsGradYear()
    {
        return $this->maxHsGradYear;
    }

    /**
     * @param int $maxHsGradYear
     */
    public function setMaxHsGradYear($maxHsGradYear)
    {
        $this->maxHsGradYear = $maxHsGradYear;
    }

    /**
     * @return boolean
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
}