<?php namespace App\Http\Controllers\Rest\ApplicationBatchController;

use App\Http\Requests\RestRequest;

/**
 * Create new application batch request.
 */
class CreateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes' => 'array',
        ];
    }
}
