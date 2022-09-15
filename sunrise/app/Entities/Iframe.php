<?php

/**
 * Auto-generated entity class
 */

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Entity represent IFrame integration configuration.
 * Each row it is integration with config and "code" that should be used for iframe access authentication.
 *
 * @ORM\Entity(repositoryClass="App\Repositories\IframeRepository")
 * @Gedmo\SoftDeleteable()
 */
class Iframe implements JsonApiResource
{
	use Timestamps;
	use SoftDeleteableEntity;

	const CODE_PREFIX = 'SA-';

    /**
	 * @ORM\Id()
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="string", length=16)
	 */
	protected $id;

    /**
     * @var ScholarshipTemplate
     * @ORM\ManyToOne(targetEntity="ScholarshipTemplate")
     */
	protected $template;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
	protected $width = '100%';

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
	protected $height;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
	protected $source = 'iframe';

    /**
	 * @return string
	 */
	public static function getResourceKey()
	{
		return "iframe";
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

    /**
     * @param string $id
     * @return Iframe
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param ScholarshipTemplate $template
     * @return Iframe
     */
    public function setTemplate(ScholarshipTemplate $template): self
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return ScholarshipTemplate
     */
    public function getTemplate(): ScholarshipTemplate
    {
        return $this->template;
    }

    /**
     * @param string $width
     * @return Iframe
     */
    public function setWidth(?string $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return string
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * @param string $height
     * @return Iframe
     */
    public function setHeight(?string $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @param string $source
     * @return Iframe
     */
    public function setSource(?string $source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): ?string
    {
        return $this->source;
    }
}
