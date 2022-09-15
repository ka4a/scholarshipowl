<?php namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Controllers\AuthorizationController as BaseController;
use Laravel\Passport\TokenRepository;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationController extends BaseController
{

    /**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $psrRequest
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @return \Illuminate\Http\Response
     */
    public function authorize(
        ServerRequestInterface $psrRequest, Request $request, ClientRepository $clients, TokenRepository $tokens
    ) {
        return $this->withErrorHandling(function () use ($psrRequest, $request, $clients, $tokens) {
            $authRequest = $this->server->validateAuthorizationRequest($psrRequest);

            $scopes = $this->parseScopes($authRequest);
            $client = $clients->find($authRequest->getClient()->getIdentifier());

            if ($user = $request->user()) {
                $token = $tokens->findValidToken($user, $client);

                if ($token && $token->scopes === collect($scopes)->pluck('id')->all()) {
                    return $this->approveRequest($authRequest, $user);
                }
            }

            $request->session()->put('authRequest', $authRequest);

            return $this->response->view('layout.admin');
        });
    }
}