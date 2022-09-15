<?php namespace App\Transformers;

use App\Entities\UserTutorial;
use League\Fractal\TransformerAbstract;

class UserTutorialTransformer extends TransformerAbstract
{
    /**
     * @param UserTutorial $tutorial
     * @return array
     */
    public function transform(UserTutorial $tutorial)
    {
        return [
            'id' => $tutorial->getUser()->getId(),
            'newScholarship' => $tutorial->isNewScholarship(),
        ];
    }
}
