<?php namespace App\Rules;

class EligibilityNotIn extends EligibilityIn
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !parent::passes($attribute, $value);
    }
}
