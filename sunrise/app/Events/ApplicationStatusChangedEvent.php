<?php namespace App\Events;

use App\Entities\Application;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicationStatusChangedEvent
{
    use Dispatchable;

    /**
     * @var string
     */
    protected $applicationId;

    /**
     * Create a new event instance.
     *
     * @param Application|string $application
     */
    public function __construct($application)
    {
        $this->applicationId = ($application instanceof Application) ? $application->getId() : $application;
    }

    /**
     * @return Application|string
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }
}
