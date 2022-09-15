<?php namespace App\Rules;

class EligibilityBetween extends AbstractEligibilityRule
{
    /**
     * EligibilityBetween constructor.
     * @param mixed $eligibilityValue
     * @param array $options
     */
    public function __construct($eligibilityValue, array $options = [])
    {
        if (count(explode(',', $eligibilityValue)) !== 2) {
            throw new \LogicException('Between value should be comma separated 2 values.');
        }

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
        return $value >= $this->eligibilityValue[0] && $value <= $this->eligibilityValue[1];
    }

    /**
     * @return string
     */
    public function message()
    {
        return trans(sprintf('validation.%s', snake_case(class_basename(static::class))), [
            'min' => $this->optionName($this->eligibilityValue[0]),
            'max' => $this->optionName($this->eligibilityValue[1]),
        ]);
    }
}
