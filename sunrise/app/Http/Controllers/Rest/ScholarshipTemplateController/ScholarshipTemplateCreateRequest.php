<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\Organisation;
use App\Http\Requests\RestRequest;
use App\Rules\Data;

class ScholarshipTemplateCreateRequest extends RestRequest
{
    public function entityRules()
    {
        return [
            'data.attributes.title'          => 'required|min:3|max:255',
            'data.attributes.description'    => 'required|max:1024',
            'data.attributes.amount'         => 'required|numeric',
            'data.attributes.awards'         => 'required|numeric',

            'data.relationships.organisation' => ['required', new Data(Organisation::class)],
        ];
    }
}
