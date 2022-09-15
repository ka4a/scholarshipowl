<?php namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

abstract class AbstractEligibilityRule implements Rule
{
    /**
     * The eligibility value set on field.
     *
     * @var mixed
     */
    protected $eligibilityValue;

    /**
     * Field options that can be used for translation.
     *
     * @var array
     */
    protected $options;

    /**
     * EligibilityGte constructor.
     * @param mixed $eligibilityValue
     * @param array $options
     */
    public function __construct($eligibilityValue, array $options = [])
    {
        $this->eligibilityValue = $eligibilityValue;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function message()
    {
        $key = sprintf('validation.%s', snake_case(class_basename(static::class)));
        return trans($key, ['eligibilityValue' => $this->transValue()]);
    }

    /**
     * @return string
     */
    protected function transValue()
    {
        if (is_array($this->eligibilityValue)) {
            return implode(', ', array_map([$this, 'optionName'], $this->eligibilityValue));
        }

        return $this->optionName($this->eligibilityValue);
    }

    /**
     * Try to translate option name if it is defined on field.
     *
     * @param mixed $value
     * @return string
     */
    protected function optionName($value)
    {
        if (isset($this->options[$value])) {
            if (is_array($this->options[$value]) && isset($this->options[$value]['name'])) {
                return sprintf('"%s"', $this->options[$value]['name']);
            }
            return sprintf('"%s"', $this->options[$value]);
        }
        return $value;
    }
}
