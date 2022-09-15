<?php namespace App\Traits;

trait OptionalRequirement
{
    /**
     * @var bool
     *
     * @ORM\Column(name="is_optional", type="boolean", nullable=false)
     */
    private $isOptional = '0';

    /**
     * @return bool
     */
    public function isOptional()
    {
        return $this->isOptional;
    }

    /**
     * @param bool $isOptional
     */
    public function setIsOptional(bool $isOptional)
    {
        $this->isOptional = $isOptional;
        return $this;
    }
}
