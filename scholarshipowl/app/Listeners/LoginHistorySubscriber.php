<?php namespace App\Listeners;

use App\Entity\Account;
use App\Entity\FeatureSet;
use App\Entity\Log\LoginHistory;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class LoginHistorySubscriber
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Login::class,  static::class . '@onLogin');
        $events->listen(Logout::class, static::class . '@onLogout');
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function onLogin(Login $event)
    {
        if ($event->user instanceof Account) {
            $event->user->setLastActionAt(new \DateTime());
            $loginHistory = $this->createLoginHistory($event->user, LoginHistory::ACTION_LOGIN);

            \EntityManager::persist($loginHistory);
            \EntityManager::flush($loginHistory);
        }
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function onLogout(Logout $event)
    {
        if ($event->user instanceof Account) {
            $event->user->setLastActionAt(new \DateTime());
            $loginHistory = $this->createLoginHistory($event->user, LoginHistory::ACTION_LOGOUT);

            \EntityManager::persist($loginHistory);
            \EntityManager::flush($loginHistory);
        }
    }

    /**
     * @param Account $account
     * @param string  $action
     *
     * @return LoginHistory
     */
    protected function createLoginHistory(Account $account, string $action) : LoginHistory
    {
         return new LoginHistory(
            $account,
            $action,
            FeatureSet::config()->getName(),
            env('APP_SRV', 'unknown'),
            \Request::getClientIp(),
            \Request::header('User-Agent', 'unknown')
        );
    }
}
