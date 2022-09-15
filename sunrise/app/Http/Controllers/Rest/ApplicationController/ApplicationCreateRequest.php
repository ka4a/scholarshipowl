<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Entities\State;
use App\Entities\Scholarship;
use App\Http\Requests\RestRequest;

/**
 * Request for scholarship application.
 */
class ApplicationCreateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes' => 'array',

            /**
             * Scholarship relation.
             */
            'data.relationships.scholarship.data.type' => [
                'required', 'in:'.Scholarship::getResourceKey(),
            ],
            'data.relationships.scholarship.data.id' => [
                'required', 'string', 'exists:'.Scholarship::class.',id',
            ],

            'data.relationships.state.data.id'        => ['numeric'],
        ];
    }
}
