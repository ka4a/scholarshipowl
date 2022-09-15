<?php namespace App\Entity\Resource\ApplyMe;
# CrEaTeD bY FaI8T IlYa      
# 2017  

use App\Entity\ApplyMe\ApplymeSettings;
use ScholarshipOwl\Data\AbstractResource;

class SettingsResource extends AbstractResource
{
    /** @var ApplymeSettings $entity */
    protected $entity;

	/**
     * @return array
     */
    public function toArray(): array
    {
        return [
			'name'	=> $this->replaceUnderscores($this->entity->getName()),
			'value'	=> $this->entity->getValue()
        ];
    }

	/**
	 * @param string $name
	 * @return string
	 */
	public function replaceUnderscores(string $name) : string
	{
		if (strpos($name, '_') !== -1) {
			return lcfirst(str_replace(" ", "", ucwords(str_replace("_", " ", $name)), $name));
		}
	}

}