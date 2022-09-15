<?php namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\RestResponse;
use Google\Cloud\ErrorReporting\Bootstrap;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (isset($_SERVER['GAE_SERVICE'])) {
            Bootstrap::exceptionHandler($exception);
        } else {
            parent::report($exception);
        }
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return RestResponse::exception(RestException::create(401, $exception->getMessage(), $exception));
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($exception instanceof RestException) {
            return RestResponse::exception($exception);
        }

        if ($exception instanceof AuthorizationException) {
            return RestResponse::exception(RestException::createForbidden($exception->getMessage()));
        }

        if ($exception instanceof ValidationException) {
            $restException = RestException::createUnprocessable($exception->getMessage());
            foreach ($exception->validator->errors()->keys() as $field) {
                $restException->errorValidation($field, $exception->validator->errors()->get($field));
            }

            return new JsonResponse(['errors' => $restException->errors()], $restException->getCode());
        }

        return parent::render($request, $exception);
    }
}
