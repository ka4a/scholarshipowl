<?php namespace App\Http\Traits;

use App\Contracts\GenericResponseContract;
use App\Http\Exceptions\JsonErrorException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait JsonResponses
{
    use JsonValidationResponse;

    /**
     * @param null|array|GenericResponseContract $data
     * @param null|array $meta
     * @param array $headers
     * @return JsonResponse
     */
    protected function jsonSuccessResponse($data = null, $meta = null, $headers = [])
    {
        $error = null;

        if ($data instanceof GenericResponseContract) {
            $meta = $data->getMeta();
            $error = $data->getError();
            $data = $data->getData();
        }

        return $this->buildResponse(JsonResponse::HTTP_OK, $data, $meta, $error, $headers);
    }

    /**
     * @param array $data
     * @param null|array $meta
     * @param array $headers
     *
     * @return JsonResponse
     */
    protected function jsonDataResponse(array $data, $meta = null, $headers = [])
    {
        return $this->jsonSuccessResponse($data, $meta, $headers);
    }

    /**
     * @param     $error
     * @param int $status
     *
     * @return JsonResponse
     */
    protected function jsonErrorResponse($error, int $status = JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->buildResponse($status, null, null, $error);
    }

    /**
     * Front-end should redirect page to URL provided in meta.
     *
     * @param string $url
     *
     * @return JsonResponse
     */
    protected function jsonRedirectResponse(string $url)
    {
        return $this->buildResponse(JsonResponse::HTTP_OK, null, ['redirect' => $url]);
    }

    /**
     * @param \Exception $e
     *
     * @return HttpResponseException|JsonResponse
     */
    protected function jsonExceptionHandle(\Exception $e)
    {
        if ($e instanceof HttpException) {
            // 0 code throws InvalidArgumentException
            if($e->getStatusCode() == 0) {
                return response($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                return response($e->getMessage(), $e->getStatusCode());
            }
        } elseif ($e instanceof AuthenticationException) {
            return response('Unauthorized.', JsonResponse::HTTP_UNAUTHORIZED);
        } elseif ($e instanceof AuthorizationException) {
            return response($e->getMessage(), JsonResponse::HTTP_FORBIDDEN);
        } elseif ($e instanceof ValidationException) {
            return $this->jsonErrorResponse($e->validator->getMessageBag(), JsonResponse::HTTP_BAD_REQUEST);
        } elseif ($e instanceof JsonErrorException) {
             // 0 code throws InvalidArgumentException
            if($e->getCode() == 0) {
                return $this->jsonErrorResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return $this->jsonErrorResponse($e->getMessage(), $e->getCode());
            }
        } else if($e instanceof \LogicException) {
            return $this->jsonErrorResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return response($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param int $status
     * @param null|array $data
     * @param null|array $meta
     * @param null|string $error
     * @param array $headers
     * @return JsonResponse
     */
    protected function buildResponse(
        int     $status,
                $data = null,
                $meta = null,
                $error = null,
                $headers = []
    ): JsonResponse
    {
        $response = ['status' => $status];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        if ($error !== null) {
            $response['error'] = $error;
        }

        return \Response::json($response, $status, $headers);
    }
}
