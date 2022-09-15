<?php namespace App\Http\Controllers\Rest\ScholarshipController;

use App\Http\Requests\RestRequest;

class ScholarshipApplyRequest extends RestRequest
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

    /**
     * @return array
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function getApplicationFields()
    {
        return $this->getData()['attributes'];
    }
}
