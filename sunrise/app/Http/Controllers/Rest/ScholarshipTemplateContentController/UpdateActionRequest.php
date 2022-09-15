<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateContentController;

use App\Http\Requests\RestRequest;

class UpdateActionRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.content' => 'required|string',
        ];
    }
}
