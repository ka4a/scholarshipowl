<?php namespace App\Rules;

class EligibilityNot extends AbstractEligibilityRule
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
        return $this->eligibilityValue != $value;
    }
}
