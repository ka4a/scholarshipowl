<?php

namespace App\Http\Middleware;

use App\Entities\Passport\OauthClient;
use App\Providers\AuthServiceProvider;
use Closure;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class Authenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * The Resource Server instance.
     *
     * @var \League\OAuth2\Server\ResourceServer
     */
    protected $server;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Authenticate constructor.
     * @param Auth $auth
     * @param ResourceServer $server
     * @param EntityManager $em
     */
    public function __construct(Auth $auth, ResourceServer $server, EntityManager $em)
    {
        $this->auth = $auth;
        $this->server = $server;
        $this->em = $em;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        try {
            $psr = (new DiactorosFactory())->createRequest($request);
            $psr = $this->server->validateAuthenticatedRequest($psr);

            /** @var OauthClient $client */
            $client = $this->em->find(OauthClient::class, $psr->getAttribute('oauth_client_id'));

            if ($client && !$client->isPasswordClient() && !$client->isPersonalAccessClient()) {
                AuthServiceProvider::$clientAuth = true;
                return;
            }

        } catch (\Exception $e) {}

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        //
    }
}
