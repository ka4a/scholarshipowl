<?php namespace App\Events\Account;

use App\Entity\Account;

class MissionCompletedEvent extends AccountEvent
{
	/**
	 * @var array $missions
	 */
    public $missions;

    /**
     * MissionCompletedEvent constructor.
     *
     * @param Account|int $account
     * @param array       $missions
     */
    public function __construct($account, array $missions)
    {
        parent::__construct($account);
        $this->missions = $missions;
    }
}
