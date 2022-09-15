<?php namespace App\Doctrine\Types;

use App\Doctrine\Types\RecurrenceConfigType\IRecurrenceConfig;
use App\Doctrine\Types\RecurrenceConfigType\ConfigFactory;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

class RecurrenceConfigType extends JsonType
{
    const NAME = 'recurrence_config';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return IRecurrenceConfig
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        return ConfigFactory::fromConfig(parent::convertToPHPValue($value, $platform));
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed|null|string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof IRecurrenceConfig) {
            return parent::convertToDatabaseValue($value->toArray(), $platform);
        }

        throw ConversionException::conversionFailedSerialization($value, $this->getName(),
            sprintf('Value must be instance of %s', IRecurrenceConfig::class)
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
