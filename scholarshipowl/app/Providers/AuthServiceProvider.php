<?php namespace App\Providers;

use App\Entity\Account;
use App\Entity\ApplicationInput;
use App\Entity\ApplicationSurvey;
use App\Entity\ApplicationSpecialEligibility;
use App\Services\Mailbox\Email;
use App\Entity\Application as ApplicationEntity;
use App\Contracts\HasPermission;
use App\Entity\AccountFile;
use App\Entity\Admin\Admin;
use App\Entity\Admin\AdminRole;
use App\Entity\ApplicationEssay;
use App\Entity\ApplicationFile;
use App\Entity\ApplicationImage;
use App\Entity\ApplicationText;
use App\Entity\EssayFiles;
use App\Entity\AccountOnBoardingCall;
use App\Entity\OnesignalAccount;
use App\Entity\Profile;
use App\Entity\Scholarship;
use App\Entity\ApplyMe\ApplyMeLanguageForm;
use App\Entity\Subscription;
use App\Policies\AccountFilePolicy;
use App\Policies\AccountOnBoardingCallPolicy;
use App\Policies\ApplicationEssayPolicy;
use App\Policies\ApplicationFilePolicy;
use App\Policies\ApplicationImagePolicy;
use App\Policies\ApplicationInputPolicy;
use App\Policies\ApplicationPolicy;
use App\Policies\ApplicationSurveyPolicy;
use App\Policies\ApplicationSpecialEligibilityPolicy;
use App\Policies\ApplicationTextPolicy;
use App\Policies\EssayFilesPolicy;
use App\Policies\OnesignalAccountPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\RoutePolicy;
use App\Policies\ScholarshipPolicy;
use App\Policies\AccountPolicy;
use App\Policies\MailboxPolicy;
use App\Policies\ApplyMe\LanguageFormPolicy;

use App\Policies\SubscriptionPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Routing\Route;
use Illuminate\Foundation\Application;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Route::class                 => RoutePolicy::class,
        Account::class               => AccountPolicy::class,
        Profile::class               => ProfilePolicy::class,
        AccountFile::class           => AccountFilePolicy::class,
        AccountOnBoardingCall::class => AccountOnBoardingCallPolicy::class,
        ApplicationEntity::class     => ApplicationPolicy::class,
        ApplicationEssay::class      => ApplicationEssayPolicy::class,
        ApplicationText::class       => ApplicationTextPolicy::class,
        ApplicationFile::class       => ApplicationFilePolicy::class,
        ApplicationImage::class      => ApplicationImagePolicy::class,
        ApplicationInput::class      => ApplicationInputPolicy::class,
        ApplicationSurvey::class     => ApplicationSurveyPolicy::class,
        ApplicationSpecialEligibility::class => ApplicationSpecialEligibilityPolicy::class,
        Scholarship::class           => ScholarshipPolicy::class,
        EssayFiles::class            => EssayFilesPolicy::class,
        Email::class                 => MailboxPolicy::class,
        ApplyMeLanguageForm::class   => LanguageFormPolicy::class,
        Subscription::class          => SubscriptionPolicy::class,
        OnesignalAccount::class      => OnesignalAccountPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissions();

        $this->app->bind(Account::class, function(Application $app) {
            if (null === ($user = $app->make('auth')->guard()->user())) {
                abort(403, 'Access denied!');
            }

            return $user;
        });
    }

    public function registerPermissions()
    {
        \Gate::before(function($admin) {
            if (($admin instanceof Admin) && $admin->getAdminRole()->getAdminRoleId() === AdminRole::ROOT) {
                return true;
            }

            return null;
        });

        \Gate::define('access-route', function(HasPermission $user, string $route) {
            return $user->hasPermissionTo(RoutePolicy::addPrefix($route));
        });
    }
}
