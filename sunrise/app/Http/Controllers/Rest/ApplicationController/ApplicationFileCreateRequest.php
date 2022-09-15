<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Entities\Application;
use App\Http\Requests\RestRequest;

class ApplicationFileCreateRequest extends RestRequest
{
    public function entityRules()
    {
        return [
            'file' => 'required|file',

            /**
             * Scholarship relation.
             */
            'data.relationships.application.data.type' => [
                'required', 'in:'.Application::getResourceKey(),
            ],
            'data.relationships.application.data.id' => [
                'required', 'string', 'exists:'.Application::class.',id',
            ],
        ];
    }
}
