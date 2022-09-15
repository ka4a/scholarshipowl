<?php namespace App\Events;

use App\Entities\ApplicationWinner;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicationWinnerFormFilledEvent
{
    use Dispatchable;

    /**
     * @var int
     */
    private $applicationWinnerId;

    /**
     * @var string
     */
    private $applicationId;

    /**
     * ScholarshipWinnerFormFilled constructor.
     * @param ApplicationWinner $winner
     */
    public function __construct(ApplicationWinner $winner)
    {
        $this->applicationWinnerId = $winner->getId();
        $this->applicationId = $winner->getApplication()->getId();
    }

    /**
     * @return int
     */
    public function getApplicationWinnerId()
    {
        return $this->applicationWinnerId;
    }

    /**
     * @return string
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }
}
