<?php namespace App\Http;

use App\Http\Middleware\EligibilityCacheMiddleware;
use App\Http\Middleware\RegistrationDataMiddleware;
use App\Http\Middleware\SRVCookiePersister;
use App\Http\Middleware\TransformRequest;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
		Middleware\EncryptCookies::class,
		\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
		\Illuminate\Session\Middleware\StartSession::class,
		\Illuminate\View\Middleware\ShareErrorsFromSession::class,
		SRVCookiePersister::class
	];

    protected $middlewareGroups = [
        'web' => [
            Middleware\FeatureAbTestsMiddleware::class,
            Middleware\TrackingParamsMiddleware::class,
            Middleware\TransformRequest::class,
            Middleware\TokenLoginMiddleware::class,
            Middleware\DomainHeader::class,
        ],
        'api' => [
            Middleware\FeatureAbTestsMiddleware::class,
            Middleware\TransformRequest::class,
        ],
        'rest' => [
            Middleware\FeatureAbTestsMiddleware::class,
            TransformRequest::class,
        ],
        'apply-me' => [],
        'rest-external' => [],
        'admin' => [],
    ];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth'              => Middleware\Authenticate::class,
        'auth.basic.once'   => Middleware\BasicHttpAuthOnce::class,
		'guest'             => Middleware\RedirectIfAuthenticated::class,
        'csrf'              => Middleware\VerifyCsrfToken::class,
        'whitelist'         => Middleware\WhiteList::class,
        'throttle'          => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'cors.allow.origin' => Middleware\CrossOriginSharing::class,
        'jwt.auth'          => Authenticate::class,
        'admin.activity'    => Middleware\Admin\ActivityLogMiddleware::class,
        'domain'            => Middleware\DomainRoute::class,
        'can'               => \Illuminate\Auth\Middleware\Authorize::class,
        'registration.data' => RegistrationDataMiddleware::class,
        'fset'              => Middleware\FeatureAbTestsMiddleware::class
    ];
}
