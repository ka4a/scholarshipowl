<?php namespace App\Http\Controllers\Rest\OrganisationController;

use App\Entities\Country;
use App\Entities\State;
use App\Http\Requests\RestRequest;
use App\Rules\Data;

class OrganisationUpdateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.name' => 'sometimes|max:255',
            'data.attributes.businessName' => 'sometimes|max:255',

            'data.attributes.city' => 'sometimes|max:255',
            'data.attributes.address' => 'sometimes|max:255',
            'data.attributes.address2' => 'sometimes|max:255',
            'data.attributes.zip' => 'sometimes|max:255',

            'data.attributes.email' => 'sometimes|email|max:255',
            'data.attributes.phone' => 'sometimes|max:255',
            'data.attributes.website' => 'sometimes|max:255',

            'data.relationships.state.data' => ['nullable', new Data(State::class)],
            'data.relationships.country.data' => ['required', new Data(Country::class)],
        ];
    }
}
