<?php namespace App\Rules;

class EligibilityIn extends AbstractEligibilityRule
{
    /**
     * EligibilityIn constructor.
     * @param mixed $eligibilityValue
     * @param array $options
     */
    public function __construct($eligibilityValue, array $options = [])
    {
        parent::__construct(explode(',', $eligibilityValue), $options);
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
        return in_array($value, $this->eligibilityValue);
    }
}
