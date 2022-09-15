<?php namespace App\Listeners;

use App\Events\Account\AccountEvent;
use App\Events\Account\ApplicationsAddEvent;
use App\Events\Account\ChangeEmailEvent;
use App\Events\Account\UpdateAccountEvent;

class CacheSubscriber
{

    /**
     * @param UpdateAccountEvent $event
     */
    public function clearAccountCacheOnProfileUpdate(AccountEvent $event)
    {
        \Cache::tags([$event->getAccount()->cacheTag()])->flush();
    }

    /**
    * @param  \Illuminate\Events\Dispatcher $dispatcher
    */
    public function subscribe($dispatcher)
    {
        $dispatcher->listen(UpdateAccountEvent::class, static::class . '@clearAccountCacheOnProfileUpdate');
        $dispatcher->listen(ChangeEmailEvent::class, static::class . '@clearAccountCacheOnProfileUpdate');
        $dispatcher->listen(ApplicationsAddEvent::class, static::class . '@clearAccountCacheOnProfileUpdate');
    }
}
