<?php namespace App\Events;

use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipWinner;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicationWinnerDisqualified
{
    use Dispatchable;

    /**
     * @var int
     */
    private $applicationId;

    /**
     * @var int
     */
    private $applicationWinnerId;

    /**
     * ApplicationWinnerDisqualified constructor.
     * @param ApplicationWinner $winner
     */
    public function __construct(ApplicationWinner $winner)
    {
        $this->applicationId = $winner->getApplication()->getId();
        $this->applicationWinnerId = $winner->getId();
    }

    /**
     * @return int
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @return int
     */
    public function getApplicationWinnerId()
    {
        return $this->applicationWinnerId;
    }
}
