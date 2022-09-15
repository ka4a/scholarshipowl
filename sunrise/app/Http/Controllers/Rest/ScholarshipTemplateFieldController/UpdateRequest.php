<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateFieldController;

use App\Http\Requests\RestRequest;

class UpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.eligibilityType' => ['nullable', 'string', 'max:255'],
            'data.attributes.eligibilityValue' => ['nullable', 'string', 'max:255'],
        ];
    }
}
