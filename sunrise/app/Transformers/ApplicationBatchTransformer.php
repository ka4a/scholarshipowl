<?php namespace App\Transformers;

use App\Entities\Application;
use App\Entities\ApplicationBatch;
use League\Fractal\TransformerAbstract;

class ApplicationBatchTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'applications',
    ];

    /**
     * @param ApplicationBatch $batch
     * @return array
     */
    public function transform(ApplicationBatch $batch)
    {
        return [
            'id' => $batch->getId(),
            'source' => $batch->getSource(),
            'eligible' => $batch->getEligible(),
            'applied' => $batch->getApplied(),
            'errors' => $batch->getErrors(),
            'status' => $batch->getStatus(),
            'data' => $batch->getData(),
        ];
    }

    /**
     * @param ApplicationBatch $batch
     * @return \League\Fractal\Resource\Collection
     */
    public function includeApplications(ApplicationBatch $batch)
    {
        return $this->collection(
            $batch->getApplications(),
            new ApplicationTransformer(),
            Application::getResourceKey()
        );
    }
}
