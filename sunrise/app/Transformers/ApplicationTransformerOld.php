<?php namespace App\Transformers;

use App\Entities\Application;
use App\Entities\ApplicationStatus;
use App\Entities\Scholarship;
use App\Entities\State;
use League\Fractal\TransformerAbstract;

class ApplicationTransformerOld extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'state',
        'status',
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'scholarship',
        'winner',
    ];

    /**
     * @param Application $application
     * @return array
     */
    public function transform(Application $application)
    {
        return [
            'id' => $application->getId(),
            'name' => $application->getName(),
            'email' => $application->getEmail(),
            'phone' => $application->getPhone(),
            'source' => $application->getSource(),
            'createdAt' => $application->getCreatedAt()->format('c'),
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
            ApplicationStatus::getResourceKey()
        );
    }

    /**
     * @param Application $application
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeState(Application $application)
    {
        if (is_null($application->getState())) {
            return $this->null();
        }
        return $this->item($application->getState(), new StateTransformer(), State::getResourceKey());
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
            Scholarship::getResourceKey()
        );
    }
}
