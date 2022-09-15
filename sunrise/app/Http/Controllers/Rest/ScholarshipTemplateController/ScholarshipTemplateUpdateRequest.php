<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Http\Requests\RestRequest;

class ScholarshipTemplateUpdateRequest extends RestRequest
{
    public function entityRules()
    {
        return [
            'data.attributes.title'             => 'sometimes|required|min:3|max:255',
            'data.attributes.description'       => 'sometimes|required|max:1024',
            'data.attributes.timezone'          => 'sometimes|required',
            'data.attributes.start'             => 'sometimes|required|date|before:data.attributes.deadline',
            'data.attributes.deadline'          => 'sometimes|required|date|after:now|after:data.attributes.start',
            'data.attributes.amount'            => 'sometimes|required|numeric',
            'data.attributes.awards'            => 'sometimes|required|numeric',
            'data.attributes.recurrenceConfig'  => 'sometimes|required|array',
            'data.attributes.scholarshipUrl'    => 'sometimes|nullable|url|max:255',
            'data.attributes.scholarshipPPUrl'  => 'sometimes|nullable|url|max:255',
            'data.attributes.scholarshipTOSUrl' => 'sometimes|nullable|url|max:255',
        ];
    }
}
