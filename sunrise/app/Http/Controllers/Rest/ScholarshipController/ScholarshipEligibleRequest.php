<?php namespace App\Http\Controllers\Rest\ScholarshipController;

use App\Http\Requests\RestRequest;

class ScholarshipEligibleRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes' => 'required|array',
        ];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array_merge_recursive($this->customData, $this->input('data.attributes'));
    }
}
