<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

/**
 * Set SRV cookie based on APP_SRV environment variable.
 * Needed to persist server for further requests for a particular user.
 * So, load balancer is using this cookie to direct a user to the same server between different requests.
 *
 * Class SRVCookiePersister
 * @package App\Http\Middleware
 */
class SRVCookiePersister
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $serverName = env('APP_SRV');

        $currentCookie = $request->cookie('SRV');
        $isForceCookieAction = (bool)preg_match("~/srv(/canary|/prod)?/?$~", \Request::url());

        $response = $next($request);

        if (!$currentCookie || ($serverName !== $currentCookie && !$isForceCookieAction)) {
            if (method_exists($response, 'withCookie')) {
                $cookie = cookie('SRV', $serverName);

                return $response->withCookie($cookie);
            }
        }

        return $response;
    }
}
