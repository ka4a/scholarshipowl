<?php namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use ScholarshipOwl\Data\Service\Mission\MissionAccountService;

class CompleteMissionsOnLogin
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $service = new MissionAccountService();
        $service->completeMissions($event->user->getAuthIdentifier());
    }
}
