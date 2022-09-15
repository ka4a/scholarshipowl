<?php

namespace App\Http\Middleware;

use App\Services\Account\AccountLoginTokenService;
use Auth;
use Gate;
use Illuminate\Http\JsonResponse;
use Route;
use Closure;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Traits\RestHelperTrait;

class Authenticate
{
    const RE_AUTH_TOKEN = 'reg_auth';

    use RestHelperTrait;
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $guard = Auth::getDefaultDriver();
        $guest = true;

        if (func_num_args() > 2) {
            // Get guards list from route middleware and default driver
            $checkGuards = array_unique(array_slice(func_get_args(), 2));

            foreach ($checkGuards as $checkGuard) {
                if (!Auth::guard($checkGuard)->guest()) {
                    Auth::setDefaultDriver($guard = $checkGuard);
                    $guest = false;
                    break;
                }
            }
        } else {
            $guest = Auth::guard($guard)->guest();
        }

        if ($guest) {
            $referer = $request->server('HTTP_REFERER');
            $isRegistrationStep = strpos($referer, '/register') !== false;
            $reAuthToken = $request->cookie(self::RE_AUTH_TOKEN);

            $reAuthorized = false;
            if ($isRegistrationStep && $reAuthToken) {
                 $ip = $request->ip();
                \Log::info("Registration re-auth token [ $reAuthToken ] has been used. IP [ $ip ] refer: [ $referer ]");

                /** @var AccountLoginTokenService $tokenService */
                $tokenService = app(AccountLoginTokenService::class);
                $accountLoginToken = $tokenService->verifyLoginToken($reAuthToken);
                if ($accountLoginToken) {
                    \Auth::loginUsingId($accountLoginToken->getAccount()->getAccountId());
                    $tokenService->expireLoginToken($accountLoginToken);
                    $reAuthorized = true;
                }
            }

            if (!$reAuthorized) {
                if ($this->isRestCall($request)) {
                    return response('Unauthorized.', 401);
                }

                if ($guard === 'admin' || (isset($checkGuards) && in_array('admin', $checkGuards))) {
                    return Redirect::guest('/admin/login');
                }

                if ($request->ajax()) {
                    return response('Unauthorized.', 401);
                } else {
                    return Redirect::guest('/')->withCookie(cookie('showLoginPopup', 1, 1, null, null, false, false));
                }
            }
        }

        /**
         * Admin authentication role check
         */
        abort_if($guard === 'admin' && Gate::denies('access', Route::getCurrentRoute()), 403, 'Access denied!');

        return $next($request);
    }
}
