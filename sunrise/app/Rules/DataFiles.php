<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class DataFiles implements Rule
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var null|mixed
     */
    protected $rules;
    /**
     * @var string
     */
    protected $message = 'The :attribute field is invalid.';

    /**
     * Create a new rule instance.
     *
     * @param string $type
     * @param string $rules
     * @param string|null $message
     */
    public function __construct($type, $rules = null, $message = null)
    {
        $this->type = $type;
        $this->rules = $rules;
        $this->message = $message ?: $this->message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_array($value) && isset($value['data']) && is_array($value['data'])) {
            foreach ($value['data'] as $r) {
                if ($r instanceof UploadedFile) {
                    if (is_null($this->rules) || !Validator::make(['file' => $r], ['file' => $this->rules])->fails()) {
                        continue;
                    }
                }
                if (is_array($r) && isset($r['id']) && isset($r['type']) && $this->type === $r['type']) {
                    continue;
                }
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
