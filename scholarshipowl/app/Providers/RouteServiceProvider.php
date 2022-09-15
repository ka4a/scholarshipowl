<?php namespace App\Providers;

use \Route;
use App\Entity\Page;
use App\Entity\Scholarship;
use App\Entity\Subscription;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * @inheritdoc
     */
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWeb();
        $this->mapApi();
        $this->mapRest();
        $this->mapRestMobile();
        $this->mapRestExternal();
        $this->mapApplyMe();
        $this->mapAdmin();
    }

    protected function mapWeb()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace
        ], function() {
            require base_path('routes/web.php');
        });
    }

    protected function mapApi()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace
        ], function() {
            require base_path('routes/api.php');
        });
    }

    protected function mapRest()
    {
        Route::group([
            'middleware' => 'rest',
            'namespace' => $this->namespace
        ], function() {
            require base_path('routes/rest.php');
        });
    }

    protected function mapRestMobile()
    {
        Route::group([
            'middleware' => 'rest',
            'namespace' => $this->namespace
        ], function() {
            require base_path('routes/rest-mobile.php');
        });
    }

    protected function mapRestExternal()
    {
        Route::group([
            'middleware' => 'rest-external',
            'namespace' => $this->namespace
        ], function() {
            require base_path('routes/rest-external.php');
        });
    }

    protected function mapApplyMe()
    {
        Route::group([
            'middleware' => 'apply-me',
            'namespace' => $this->namespace
        ], function() {
            require base_path('routes/apply-me.php');
        });
    }

    protected function mapAdmin()
    {
        Route::group([
            'middleware' => 'admin',
            'namespace' => $this->namespace
        ], function() {
            require base_path('routes/admin.php');
        });
    }
}
