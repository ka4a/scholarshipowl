<?php namespace App\Http\Controllers\Rest\ScholarshipWebsiteController;

use App\Http\Requests\RestRequest;

class RelatedWinnersCreateRequest extends RestRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        return $this->all()['data'];
    }

    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.*.attributes.name'          => 'required|max:255',
            'data.*.attributes.testimonial'   => 'required|max:1024',
            'data.*.attributes.image'         => 'required|image',
        ];
    }
}
