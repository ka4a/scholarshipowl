<?php namespace App\Events\Account;

use App\Events\Event;
use App\Events\ReasonInterface;

/**
 * Happens on updating `account_eligibility` table and updating `account`.`eligibility` field.
 */
class AccountEligibilityUpdateEvent extends Event
{
    /**
     * @var int
     */
    public $account;

    /**
     * @var string
     */
    public $new;

    /**
     * @var null|string
     */
    public $old;

    /**
     * @var int
     */
    public $reason;

    /**
     * AccountEligibilityUpdate constructor.
     *
     * @param int                       $account  Account Id
     * @param string                    $new      New `account_eligibility`.`id`
     * @param string|null               $old      Old `account_eligibility`.`id`
     * @param ReasonInterface|null      $reason   Reason of updating the table.
     */
    public function __construct(int $account, string $new, string $old = null, ReasonInterface $reason = null)
    {
        $this->account = $account;
        $this->reason = $reason;
        $this->new = $new;
        $this->old = $old;
    }
}
