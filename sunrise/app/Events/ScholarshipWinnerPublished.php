<?php namespace App\Events;

use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipWinner;
use Illuminate\Foundation\Events\Dispatchable;

class ScholarshipWinnerPublished
{
    use Dispatchable;

    /**
     * @var int
     */
    private $scholarshipWinnerId;

    /**
     * ScholarshipWinnerFormFilled constructor.
     * @param ScholarshipWinner $winner
     */
    public function __construct(ScholarshipWinner $winner)
    {
        $this->scholarshipWinnerId = $winner->getId();
    }

    /**
     * @return int
     */
    public function getScholarshipWinnerId()
    {
        return $this->scholarshipWinnerId;
    }
}
