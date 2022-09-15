<?php namespace App\Entity\Resource;

use App\Entity\ApplyMe\ApplyMeLanguageForm;
use ScholarshipOwl\Data\AbstractResource;

class LanguageFormResource extends AbstractResource
{
    /**
     * @var ApplyMeLanguageForm
     */
    protected $entity;

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'name'  => $this->entity->getName(),
            'value' => $this->entity->getValue()
        ];
    }
}
