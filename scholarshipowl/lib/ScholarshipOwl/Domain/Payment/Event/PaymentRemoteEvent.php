<?php namespace ScholarshipOwl\Domain\Payment\Event;

use Illuminate\Queue\Jobs\Job;
use ScholarshipOwl\Domain\Payment\IMessage;

interface PaymentRemoteEvent
{

    public function fireEventFromMessage(IMessage $message, Job $job = null);

}
