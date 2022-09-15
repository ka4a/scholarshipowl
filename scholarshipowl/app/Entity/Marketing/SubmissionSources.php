<?php

namespace App\Entity\Marketing;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * SubmissionSources
 *
 * @ORM\Table(name="submission_sources")
 * @ORM\Entity
 */
class SubmissionSources
{
    use Dictionary;
    const DESKTOP = 1;
    const MOBILE = 2;
    const SYSTEM = 3;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=50, nullable=true)
     */
    private $source;

    /**
     * @return null|string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param null|string $source
     *
     * @return SubmissionSources
     */
    public function setSource(?string $source)
    {
        $this->source = $source;

        return $this;
    }
}

