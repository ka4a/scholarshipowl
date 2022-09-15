<?php namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxWords implements Rule
{
    /**
     * @var int
     */
    protected $max;

    public function __construct($max)
    {
        $this->max = $max;
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
        return str_word_count($value) <= $this->max;
    }

    /**
     * @return string
     */
    public function message()
    {
        return trans('validation.max_words', ['value' => $this->max]);
    }
}
