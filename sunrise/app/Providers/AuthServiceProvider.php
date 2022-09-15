<?php

namespace App\Providers;

use App\Auth\ApiTokenGuard;
use App\Entities\Application;
use App\Entities\ApplicationFile;
use App\Entities\Country;
use App\Entities\Field;
use App\Entities\Iframe;
use App\Entities\Organisation;
use App\Entities\Requirement;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipTemplateContent;
use App\Entities\ScholarshipTemplateField;
use App\Entities\ScholarshipWebsite;
use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipWinner;
use App\Entities\Settings;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\UserTutorial;
use App\Policies\ApplicationFilePolicy;
use App\Policies\ApplicationPolicy;
use App\Policies\ApplicationWinnerPolicy;
use App\Policies\FieldPolicy;
use App\Policies\OrganisationPolicy;
use App\Policies\RequirementPolicy;
use App\Policies\ScholarshipPolicy;
use App\Policies\ScholarshipTemplateContentPolicy;
use App\Policies\ScholarshipTemplateFieldPolicy;
use App\Policies\ScholarshipTemplatePolicy;
use App\Policies\ScholarshipWebsitePolicy;
use App\Policies\ScholarshipWinnerPolicy;
use App\Policies\SettingsPolicy;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserTutorialPolicy;
use App\Policies\CountryPolicy;
use App\Policies\IframePolicy;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Scope allows access to scholarships data.
     */
    const SCOPE_SCHOLARSHIPS = 'scholarships';

    /**
     * Determine if Oauth client signed in.
     *
     * @var bool
     */
    public static $clientAuth;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

        Country::class                  => CountryPolicy::class,

        User::class                     => UserPolicy::class,
        UserTutorial::class             => UserTutorialPolicy::class,

        Settings::class                 => SettingsPolicy::class,

        Role::class                     => RolePolicy::class,
        Organisation::class             => OrganisationPolicy::class,

        Field::class                    => FieldPolicy::class,
        Requirement::class              => RequirementPolicy::class,

        Scholarship::class              => ScholarshipPolicy::class,
        ScholarshipTemplate::class      => ScholarshipTemplatePolicy::class,
        ScholarshipTemplateField::class => ScholarshipTemplateFieldPolicy::class,
        ScholarshipTemplateContent::class => ScholarshipTemplateContentPolicy::class,
        ScholarshipWebsite::class       => ScholarshipWebsitePolicy::class,
        ScholarshipWinner::class        => ScholarshipWinnerPolicy::class,
        Iframe::class                   => IframePolicy::class,

        Application::class              => ApplicationPolicy::class,
        ApplicationFile::class          => ApplicationFilePolicy::class,
        ApplicationWinner::class        => ApplicationWinnerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        static::$clientAuth = false;

        $this->registerPolicies();

        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();

        Passport::$scopes = [
            static::SCOPE_SCHOLARSHIPS => 'Scholarships access.',
        ];


        Auth::extend('api-token', function (Container $app) {
            return new ApiTokenGuard($app['em']);
        });
    }

    public function register()
    {
        $this->allowRootAccess();
    }

    public function allowRootAccess()
    {
        /** @var Gate $gate */
        $gate = $this->app->make(Gate::class);

        $gate->before(function($user = null) {
            if (is_null($user) && static::$clientAuth) {
                return true;
            }

            if ($user instanceof User && $user->hasRole(Role::root())) {
                return true;
            }

            return null;
        });
    }
}
