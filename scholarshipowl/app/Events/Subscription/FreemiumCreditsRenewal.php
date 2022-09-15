<?php

namespace App\Events\Subscription;

use App\Events\Event;

class FreemiumCreditsRenewal extends Event
{

    /**
     * @var \DateTime
     */
    protected $renewalDate;

    public function __construct($renewalDate)
    {
        $this->renewalDate = $renewalDate;
    }

    /**
     * @return \DateTime
     */
    public function getRenewalDate()
    {
        return $this->renewalDate;
    }
}
