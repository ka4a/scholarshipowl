<?php namespace App\Doctrine\Types\RecurrenceConfigType;

use Carbon\CarbonInterval;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ConfigFactory
{
    /**
     * @param array $config
     * @return IRecurrenceConfig
     */
    static public function fromConfig(array $config)
    {
        $type = static::getValidationFactory()
            ->make($config, [
                IRecurrenceConfig::KEY_TYPE => [
                    'required', Rule::in([
                        OneTimeConfig::TYPE,
                        WeeklyConfig::TYPE,
                        MonthlyConfig::TYPE,
                        AdvancedConfig::TYPE,
                    ])
                ]
            ])
            ->validate()[IRecurrenceConfig::KEY_TYPE];

        switch ($type) {
            case OneTimeConfig::TYPE:
                return static::buildOneTimeConfig($config);
                break;

            case WeeklyConfig::TYPE:
                return static::buildWeeklyConfig($config);
                break;

            case MonthlyConfig::TYPE:
                return static::buildMonthlyConfig($config);
                break;

            case AdvancedConfig::TYPE:
                return static::buildAdvancedConfig($config);
                break;

            default:
                throw new \RuntimeException(sprintf('Recurrence type "%s" is unknown', $type));
                break;
        }
    }

    /**
     * @param array $config
     * @return OneTimeConfig
     */
    static protected function buildOneTimeConfig(array $config)
    {
        $validated = static::getValidationFactory()
            ->make($config, [
                OneTimeConfig::KEY_START       => [
                    'required', 'date'
                ],
                OneTimeConfig::KEY_DEADLINE       => [
                    'required', 'date', 'after:'.OneTimeConfig::KEY_START,
                ],
            ])
            ->validate();

        return new OneTimeConfig(
            new \DateTime($validated[OneTimeConfig::KEY_START]),
            new \DateTime($validated[OneTimeConfig::KEY_DEADLINE])
        );
    }

    /**
     * @param array $config
     * @return WeeklyConfig
     */
    static protected function buildWeeklyConfig(array $config)
    {
        $weekdays = range(1, 7);
        $validated = static::getValidationFactory()
            ->make($config, [
                WeeklyConfig::KEY_START_DAY => [
                    'required', 'numeric', Rule::in($weekdays)
                ],
                WeeklyConfig::KEY_DEADLINE_DAY => [
                    'required', 'numeric', Rule::in($weekdays),
                ],
                WeeklyConfig::KEY_OCCURRENCES => [
                    'sometimes', 'numeric', 'nullable'
                ],
                WeeklyConfig::KEY_STARTS_AFTER_DEADLINE => [
                    'sometimes', 'boolean', 'nullable'
                ]
            ])
            ->validate();

        return new WeeklyConfig(
            intval($validated[WeeklyConfig::KEY_START_DAY]),
            intval($validated[WeeklyConfig::KEY_DEADLINE_DAY]),
            intval($validated[WeeklyConfig::KEY_OCCURRENCES] ?? 0),
            boolval($validated[MonthlyConfig::KEY_STARTS_AFTER_DEADLINE] ?? false)
        );
    }

    /**
     * @param array $config
     * @return MonthlyConfig
     */
    static protected function buildMonthlyConfig(array $config)
    {
        $monthsDays = range(1, 31);
        $validated = static::getValidationFactory()
            ->make($config, [
                MonthlyConfig::KEY_START_DATE => [
                    'required', 'numeric', Rule::in($monthsDays),
                ],
                MonthlyConfig::KEY_DEADLINE_DATE => [
                    'required', 'numeric', Rule::in($monthsDays),
                ],
                MonthlyConfig::KEY_DEADLINE_END_OF_MONTH => [
                    'sometimes', 'boolean', 'nullable'
                ],
                MonthlyConfig::KEY_STARTS_AFTER_DEADLINE => [
                    'sometimes', 'boolean', 'nullable'
                ],
                MonthlyConfig::KEY_OCCURRENCES => [
                    'sometimes', 'numeric', 'nullable'
                ],
                MonthlyConfig::KEY_EXCEPTIONS => [
                    'sometimes', 'array',
                ],
                MonthlyConfig::KEY_EXCEPTIONS . '.*.' . MonthlyConfig::KEY_EXCEPTIONS_MONTH => [
                    'required', 'numeric', Rule::in(range(1, 12))
                ],
                MonthlyConfig::KEY_EXCEPTIONS . '.*.' . MonthlyConfig::KEY_START_DATE => [
                    'nullable', 'numeric', Rule::in($monthsDays)
                ],
                MonthlyConfig::KEY_EXCEPTIONS . '.*.' . MonthlyConfig::KEY_DEADLINE_DATE => [
                    'nullable', 'numeric', Rule::in($monthsDays)
                ]
            ])
            ->validate();

        $occurrencesKey = MonthlyConfig::KEY_OCCURRENCES;
        return new MonthlyConfig(
            intval($validated[MonthlyConfig::KEY_START_DATE]),
            intval($validated[MonthlyConfig::KEY_DEADLINE_DATE]),
            boolval($validated[MonthlyConfig::KEY_DEADLINE_END_OF_MONTH] ?? false),
            boolval($validated[MonthlyConfig::KEY_STARTS_AFTER_DEADLINE] ?? false),
            isset($validated[$occurrencesKey]) ? intval($validated[$occurrencesKey]) : null,
            $validated[MonthlyConfig::KEY_EXCEPTIONS] ?? []
        );
    }

    /**
     * @param array $config
     * @return AdvancedConfig
     */
    static protected function buildAdvancedConfig(array $config)
    {
        $validated = static::getValidationFactory()
            ->make($config, [
                AdvancedConfig::KEY_START_DATE      => [
                    'required', 'date'
                ],
                AdvancedConfig::KEY_DEADLINE_DATE   => [
                    'required' , 'date', 'after:'.AdvancedConfig::KEY_START_DATE,
                ],
                AdvancedConfig::KEY_PERIOD_VALUE    => [
                    'required', 'numeric',
                ],
                AdvancedConfig::KEY_PERIOD_TYPE     => [
                    'required', 'string', Rule::in(array_keys(AdvancedConfig::$recurrenceTypes)),
                ],
                AdvancedConfig::KEY_OCCURRENCES     => [
                    'sometimes', 'numeric', 'nullable'
                ],
            ])
            ->validate();

        $startDate = new \DateTime($validated[AdvancedConfig::KEY_START_DATE]);
        $deadlineDate = new \DateTime($validated[AdvancedConfig::KEY_DEADLINE_DATE]);
        $periodType = $validated[AdvancedConfig::KEY_PERIOD_TYPE];
        $periodValue = intval($validated[AdvancedConfig::KEY_PERIOD_VALUE]);

        $recurrencePeriod = CarbonInterval::fromString("$periodValue $periodType");
        $scholarshipInterval = $startDate->diff($deadlineDate);

        /**
         * Validate that recurrence period is bigger than full scholarship period.
         */
        static::getValidationFactory()
            ->make([
                    AdvancedConfig::KEY_PERIOD_VALUE => $recurrencePeriod,
                ], [
                    AdvancedConfig::KEY_PERIOD_VALUE => function($attr, $value, $fail) use ($scholarshipInterval) {

                        $now = new \DateTime();
                        $compareRecurrencePeriod = clone $now;
                        $compareRecurrencePeriod->add($value);
                        $compareScholarshipPeriod = clone $now;
                        $compareScholarshipPeriod->add($scholarshipInterval);
                        if ($compareRecurrencePeriod > $compareScholarshipPeriod) {
                            return true;
                        }

                        return $fail('Recurrence period must be bigger than scholarship duration time.');
                    }
                ])
            ->validate();

        $occurrencesKey = MonthlyConfig::KEY_OCCURRENCES;
        return new AdvancedConfig(
            $startDate,
            $deadlineDate,
            $periodType,
            $periodValue,
            isset($validated[$occurrencesKey]) ? intval($validated[$occurrencesKey]) : null
        );
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    static protected function getValidationFactory()
    {
        return app(Factory::class);
    }
}
