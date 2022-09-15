<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\Field;
use App\Http\Requests\RestRequest;
use App\Rules\Data;

class RelatedFieldsUpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.*.attributes.eligibilityType' => 'string|nullable',
            'data.*.attributes.eligibilityValue' => 'string|nullable',
            'data.*.relationships.field' => new Data(Field::class),
        ];
    }

    /**
     * @return array
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function getData()
    {
        return \Pz\LaravelDoctrine\Rest\RestRequest::getData();
    }
}
