<?php namespace App\Http\Middleware;

class TransformRequest extends TransformsRequest
{
    /**
     * The attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        $value = is_string($value) ? trim($value) : $value;

        if ($value === 'true' || $value === 'TRUE') {
            $value = true;
        }

        if ($value === 'false' || $value === 'FALSE') {
            $value = false;
        }

        return $value;
    }
}
