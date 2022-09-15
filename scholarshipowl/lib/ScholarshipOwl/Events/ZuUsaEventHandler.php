<?php namespace ScholarshipOwl\Events;

use App\Events\Account\Register3VerifyAccountEvent;
use Illuminate\Events\Dispatcher;

class ZuUsaEventHandler
{
    /**
     * @var string
     */
	private $pluginName = "zuusa";

	public function onRegister3Verification()
    {
        return array("redirect" => "zuusa");
	}

    /**
     * @param Dispatcher $events
     */
	public function subscribe(Dispatcher $events)
    {
		$events->listen(Register3VerifyAccountEvent::class, static::class . '@onRegister3Verification');
	}
}
