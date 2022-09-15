<?php namespace App\Transformers;

use App\Entities\ApplicationStatus;
use League\Fractal\TransformerAbstract;

class ApplicationStatusTransformer extends TransformerAbstract
{
    /**
     * @param ApplicationStatus $status
     * @return array
     */
    public function transform(ApplicationStatus $status)
    {
        return [
            'id' => $status->getId(),
            'name' => $status->getName(),
        ];
    }
}
