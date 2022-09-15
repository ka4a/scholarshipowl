<?php namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * HTTP Auth for one request.
 *
 * Usage: middleware => 'auth.basic.once:user,password'
 *
 * @package App\Http\Middleware
 */
class BasicHttpAuthOnce
{
    /**
     * @param          $request
     * @param \Closure $next
     * @param null     $user
     * @param null     $password
     *
     * @return Response
     */
    public function handle($request, \Closure $next, $user = null, $password = null)
    {
        if ($request instanceof Request) {
            if (!$user || !$password || $request->getUser() !== $user || $request->getPassword() !== $password) {
                return new Response('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
            }
        }

        return $next($request);
    }
}
