<?php

namespace App\Http\Middleware;

use App\Services\Account\AccountLoginTokenService;
use Closure;
use Session;
use Event;

class TokenLoginMiddleware
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
        /** @var AccountLoginTokenService $service */
        $service = app(AccountLoginTokenService::class);

        if($request->get("auth_token") && $accountLoginToken = $service->verifyLoginToken($request->get("auth_token"))){
            \Auth::loginUsingId($accountLoginToken->getAccount()->getAccountId());
            $service->expireLoginToken($accountLoginToken);
        }

        return $next($request);
    }
}
