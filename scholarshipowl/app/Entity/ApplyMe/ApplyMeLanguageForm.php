<?php

namespace App\Entity\ApplyMe;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Dictionary;

/**
 * ApplyMeLanguageForm
 *
 * @ORM\Table(name="apply_me_language_form", indexes={@ORM\Index(name="apply_me_language_form_title_index", columns={"name"})})
 * @ORM\Entity
 */
class ApplyMeLanguageForm
{
    use Dictionary;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    protected $value;

    /**
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value) {
        $this->value = $value;
    }
}

