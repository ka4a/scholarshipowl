<?php namespace App\Rules;

class EligibilityGt extends AbstractEligibilityRule
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
        return $value > $this->eligibilityValue;
    }
}
