<?php

namespace App\Exceptions;

use App\Entity\Account;
use App\Http\Exceptions\JsonErrorException;
use App\Http\Traits\JsonResponses;
use App\Http\Traits\RestHelperTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Sentry\State\HubInterface;
use Sentry\State\Scope;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use JsonResponses;
    use RestHelperTrait;

    /**
     * @var int
     */
    protected $sentryId;

    /**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ValidationException::class,
        JsonErrorException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
        parent::report($e);

        if (config('services.sentry.phpReport') && $this->shouldReport($e)) {
            $this->reportSentry($e);
        }
	}

    /**
     * @param Exception $e
     */
    protected function reportSentry(Exception $e)
    {
        if (!config('services.sentry.phpReport')) {
            return;
        }

        /** @var HubInterface $sentry */
        $sentry = app('sentry');

        if ($account = auth()->user()) {
            if ($account instanceof Account) {
               $sentry->configureScope(function (Scope $scope) use($account): void {
                  $scope->setUser([
                    'id'    => $account->getAccountId(),
                    'email' => $account->getEmail(),
                  ]);
                });
            }

            $sentry->configureScope(function (Scope $scope): void {
                $scope->setExtra('server', config('services.sentry.server'));
            });
        }

        $this->sentryId = $sentry->captureException($e);
    }

    /**
	 * Render an exception into an HTTP response.
	 *
	 * @param  Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
    public function render($request, Exception $e)
	{
        if ($this->isRestCall($request)) {
            return $this->jsonExceptionHandle($e);
//        } elseif ($e instanceof NotFoundHttpException) {
//            return \Response::view('error.404');
        } elseif ($e instanceof \Error) {
            return response($this->renderError($e), 500);
        } elseif (config('services.sentry.crashReport') && $this->sentryId) {
            return \Response::view('common.errors.500', [
                'sentryID' => $this->sentryId,
            ], 500);
        }

        return parent::render($request, $e);
	}

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    /**
     * @param \Error $error
     */
    public function renderError(\Error $error)
    {
        return sprintf('Error %s', $error->getMessage());
    }

    protected function whoopsHandler()
    {
        try {
            return app(\Whoops\Handler\HandlerInterface::class);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            return (new \Illuminate\Foundation\Exceptions\WhoopsHandler)->forDebug();
        }
    }
}
