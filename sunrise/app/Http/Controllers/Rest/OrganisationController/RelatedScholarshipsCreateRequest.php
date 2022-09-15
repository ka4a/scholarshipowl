<?php namespace App\Http\Controllers\Rest\OrganisationController;

use App\Http\Requests\RestRequest;

class RelatedScholarshipsCreateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.title'         => 'required|string|max:255',
            'data.attributes.description'   => 'required|string|max:2047',
            'data.attributes.amount'        => 'required|numeric',
            'data.attributes.start'         => 'required|date',
            'data.attributes.deadline'      => 'required|date',
        ];
    }
}
