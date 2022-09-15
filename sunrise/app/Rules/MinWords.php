<?php namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinWords implements Rule
{
    /**
     * @var int
     */
    protected $min;

    public function __construct($min)
    {
        $this->min = $min;
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
        return str_word_count($value) >= $this->min;
    }

    /**
     * @return string
     */
    public function message()
    {
        return trans('validation.min_words', ['value' => $this->min]);
    }
}
