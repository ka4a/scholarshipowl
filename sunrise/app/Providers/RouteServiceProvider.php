<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('id', '([0-9]+|[-0-9a-f]+|SA\-[A-Z0-9]{13})');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Passport::routes(function(RouteRegistrar $registrar) {
            $registrar->forAccessTokens();
            $registrar->forTransientTokens();
            $registrar->forPersonalAccessTokens();
            Route::group(['middleware' => ['web']], function ($router) {
                $router->get('/authorize', [
                    'uses' => '\App\Http\Controllers\Front\AuthorizationController@authorize',
                    'as' => 'passport.authorizations.authorize',
                ]);

                $router->post('/authorize', [
                    'uses' => 'ApproveAuthorizationController@approve',
                    'as' => 'passport.authorizations.approve',
                ]);

                $router->delete('/authorize', [
                    'uses' => 'DenyAuthorizationController@deny',
                    'as' => 'passport.authorizations.deny',
                ]);
            });
        });

        $this->mapApiRoutes();
        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
             ->prefix('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
