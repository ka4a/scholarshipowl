<?php namespace ScholarshipOwl\Events;

use App\Events\Account\Register3VerifyAccountEvent;
use Illuminate\Events\Dispatcher;

class DaneMediaEventHandler
{
    /**
     * @var string
     */
	private $pluginName = "danemedia";

    /**
     * @param Register3VerifyAccountEvent $event
     *
     * @return array
     */
	public function onRegister3Verification(Register3VerifyAccountEvent $event)
    {
        return array("redirect" => "dane");
	}

    /**
     * @param Dispatcher $events
     */
	public function subscribe(Dispatcher $events)
    {
		$events->listen(Register3VerifyAccountEvent::class, static::class . '@onRegister3Verification');
	}
}
