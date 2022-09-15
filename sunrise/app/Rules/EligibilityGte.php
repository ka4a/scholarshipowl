<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EligibilityGte extends AbstractEligibilityRule
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
        return $value >= $this->eligibilityValue;
    }
}
