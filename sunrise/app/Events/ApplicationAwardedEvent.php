<?php namespace App\Events;

use App\Entities\ApplicationWinner;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicationAwardedEvent
{
    use Dispatchable;

    /**
     * @var string
     */
    protected $applicationId;

    /**
     * @var int
     */
    protected $scholarshipWinnerId;

    /**
     * Create a new event instance.
     *
     * @param ApplicationWinner|int $winner
     */
    public function __construct(ApplicationWinner $winner)
    {
        $this->scholarshipWinnerId = $winner->getId();
        $this->applicationId = $winner->getApplication()->getId();
    }

    /**
     * @return int
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @return ApplicationWinner|int
     */
    public function getScholarshipWinnerId()
    {
        return $this->scholarshipWinnerId;
    }
}
