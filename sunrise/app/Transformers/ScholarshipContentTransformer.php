<?php namespace App\Transformers;

use App\Entities\ScholarshipContent;
use League\Fractal\TransformerAbstract;

class ScholarshipContentTransformer extends TransformerAbstract
{
    /**
     * @param ScholarshipContent $content
     * @return array
     */
    public function transform(ScholarshipContent $content)
    {
        return [
            'id' => $content->getId(),
            'privacyPolicy' => $content->getPrivacyPolicy(),
            'termsOfUse' => $content->getTermsOfUse(),
            'createdAt' => $content->getCreatedAt()->format('c'),
            'updatedAt' => $content->getUpdatedAt()->format('c'),

            /**
             * TODO: Remove after Barn fixes it is on their side.
             */
            'rules' => '',
        ];
    }
}
