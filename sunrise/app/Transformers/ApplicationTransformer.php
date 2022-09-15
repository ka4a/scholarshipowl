<?php namespace App\Transformers;

use App\Entities\Application;
use App\Entities\ApplicationRequirement;
use League\Fractal\TransformerAbstract;

class ApplicationTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'status',
        'scholarship',
        'requirements',
    ];

    /**
     * @param Application $application
     * @return array
     */
    public function transform(Application $application)
    {
        return [
            'id' => $application->getId(),
            'email' => $application->getEmail(),
            'name' => $application->getName(),
            'source' => $application->getSource(),
            'data' => $application->getData(),
            'createdAt' => $application->getCreatedAt()->format('c'),
            'meta' => [
                'scholarship' => $application->getScholarship()->getId(),
            ],
        ];
    }

    /**
     * @param Application $application
     * @return \League\Fractal\Resource\Item
     */
    public function includeStatus(Application $application)
    {
        return $this->item(
            $application->getStatus(),
            new ApplicationStatusTransformer(),
            $application->getStatus()->getResourceKey()
        );
    }

    /**
     * @param Application $application
     * @return \League\Fractal\Resource\Item
     */
    public function includeScholarship(Application $application)
    {
        return $this->item(
            $application->getScholarship(),
            new ScholarshipTransformer(),
            $application->getScholarship()->getResourceKey()
        );
    }

    /**
     * @param Application $application
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRequirements(Application $application)
    {
        return $this->collection(
            $application->getRequirements(),
            new ApplicationRequirementTransformer(),
            ApplicationRequirement::getResourceKey()
        );
    }
}
