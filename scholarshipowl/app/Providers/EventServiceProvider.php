<?php namespace App\Providers;

use App\Events\Account\CreateAccountEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Listeners\AccountPubSubPublisher;
use App\Listeners\ApplyForDYIScholarshipListener;
use App\Listeners\CacheSubscriber;
use App\Listeners\CappexDataDealListener;
use App\Listeners\CompleteMissionsOnLogin;
use App\Listeners\EligibilityCacheCleaner;
use App\Listeners\FireBaseListener;
use App\Listeners\FreemiumSubscriber;
use App\Listeners\HasOffersSubscriber;
use App\Listeners\LoginHistorySubscriber;
use App\Listeners\ProfileSubscribe;
use App\Listeners\PubSubTransactionalEmailsSubscriber;
use App\Listeners\PaymentsSubscribe;
use App\Listeners\RegisterAccount;
use App\Listeners\EligibilityCacheMaintainer;
use App\Listeners\ScholarshipWinner;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Auth\Events\Logout as LogoutEvent;
use App\Listeners\ZendSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\FacebookPixels;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        LoginEvent::class => [
            CompleteMissionsOnLogin::class,
        ],
        CreateAccountEvent::class => [
            RegisterAccount::class,
        ]
    ];

    protected $subscribe = [
        LoginHistorySubscriber::class,
        CacheSubscriber::class,
        HasOffersSubscriber::class,
        ZendSubscriber::class,
        PubSubTransactionalEmailsSubscriber::class,
        FacebookPixels::class,
        PaymentsSubscribe::class,
        ScholarshipWinner::class,
        ProfileSubscribe::class,
        FireBaseListener::class,
        EligibilityCacheCleaner::class,
        EligibilityCacheMaintainer::class,
        ApplyForDYIScholarshipListener::class,
        AccountPubSubPublisher::class,
        FreemiumSubscriber::class,

        CappexDataDealListener::class,
    ];
}
