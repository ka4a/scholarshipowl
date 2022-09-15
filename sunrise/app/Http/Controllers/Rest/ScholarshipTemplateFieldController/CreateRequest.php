<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateFieldController;

use App\Entities\Field;
use App\Entities\ScholarshipTemplate;
use App\Http\Requests\RestRequest;
use App\Rules\Data;

class CreateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.eligibilityType' => ['string', 'max:8'],
            'data.attributes.eligibilityValue' => ['string', 'max:1024'],
            'data.relationships.field' => ['required', new Data(Field::getResourceKey())],
            'data.relationships.template' => ['required', new Data(ScholarshipTemplate::getResourceKey())],
        ];
    }
}
