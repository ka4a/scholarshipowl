<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\ScholarshipWebsite;

class ScholarshipTemplateRelatedWebsiteCreateRequest extends ScholarshipTemplateRelatedWebsiteUpdateRequest
{
    /**
     * @return array
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function entityRules()
    {
        $rules = parent::entityRules();
        $rules['data.attributes.domain'] = 'required|max:255|unique:'.ScholarshipWebsite::class.',domain';
        return $rules;
    }
}
