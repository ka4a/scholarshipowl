<?php namespace App\Events\Scholarship;

use App\Events\ReasonInterface;

class ScholarshipAccountEligibilityUpdate extends ScholarshipEvent
{
    /**
     * @var ReasonInterface|null
     */
    public $reason;

    /**
     * @var bool
     */
    public $disable;

    /**
     * @var array|null
     */
    public $accounts;

    /**
     * ScholarshipAccountEligibilityUpdate constructor.
     *
     * @param \App\Entity\Scholarship|int $scholarship
     * @param ReasonInterface|null        $reason
     * @param bool                        $disable
     * @param null|array                  $accounts
     */
    public function __construct($scholarship, array $accounts, ReasonInterface $reason = null, $disable = false)
    {
        parent::__construct($scholarship);
        $this->reason = $reason;
        $this->disable = $disable;
        $this->accounts = $accounts;
    }
}
