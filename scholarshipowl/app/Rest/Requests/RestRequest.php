<?php namespace App\Rest\Requests;

use App\Http\Requests\BaseRequest;
use App\Http\Traits\JsonResponses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

abstract class RestRequest extends BaseRequest
{
    use JsonResponses;

    const PARAM_FIELDS = 'fields';

    /**
     * @var array
     */
    protected $fields;

    /**
     * @return bool
     */
    abstract public function authorize();

    /**
     * @return array
     */
    abstract public function rules();

    /**
     * @return array
     */
    public function fields()
    {
        if ($this->fields === null) {
            $this->fields = [];

            if ($this->has(static::PARAM_FIELDS)) {
                foreach (explode(',', $this->input(static::PARAM_FIELDS)) as $field) {
                    Arr::set($this->fields, $field, true);
                }
            }
        }

        return $this->fields;
    }

    /**
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $errors)
    {
        return $this->jsonErrorResponse($errors, JsonResponse::HTTP_BAD_REQUEST);
    }
}
