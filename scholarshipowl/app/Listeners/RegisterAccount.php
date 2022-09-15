<?php namespace App\Listeners;

use App\Jobs\CreateLoginToken;
use App\Jobs\ReferralRewardJob;
use App\Events\Account\CreateAccountEvent;
use App\Services\Account\AccountLoginTokenService;

class RegisterAccount
{
    /**
     * @param CreateAccountEvent $event
     */
    public function handle(CreateAccountEvent $event)
    {
        if ($referralCode = request('referral')) {
            ReferralRewardJob::dispatch($event->getAccountId(), $referralCode, request('ch'));
        }
    }
}
