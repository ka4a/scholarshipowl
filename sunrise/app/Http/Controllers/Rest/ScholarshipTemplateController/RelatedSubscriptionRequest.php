<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\ScholarshipTemplateSubscription;
use App\Http\Requests\RestRequest;

class RelatedSubscriptionRequest extends RestRequest
{
    /**
     * @return array
     */
    public function entityRules()
    {
        return [
            'data.attributes.email' => [
                'required', 'email', 'max:255',
                // 'unique:'.ScholarshipTemplateSubscription::class.',email,NULL,id,template,'.$this->getId()
            ]
        ];
    }
}
