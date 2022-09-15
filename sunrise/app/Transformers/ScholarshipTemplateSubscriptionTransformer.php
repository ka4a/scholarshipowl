<?php namespace App\Transformers;

use App\Entities\ScholarshipTemplateSubscription;
use League\Fractal\TransformerAbstract;

class ScholarshipTemplateSubscriptionTransformer extends TransformerAbstract
{
    /**
     * @param ScholarshipTemplateSubscription $subscription
     * @return array
     */
    public function transform(ScholarshipTemplateSubscription $subscription)
    {
        return [
            'id' => $subscription->getId(),
            'email' => $subscription->getEmail(),
            'status' => $subscription->getStatus(),
            'createdAt' => $subscription->getCreatedAt()->format('c'),
            'updatedAt' => $subscription->getUpdatedAt()->format('c'),
        ];
    }
}
