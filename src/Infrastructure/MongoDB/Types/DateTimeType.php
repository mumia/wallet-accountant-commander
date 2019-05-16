<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * DateTimeAnnotation
 */
class DateTimeType extends Type
{
    /**
     * @param mixed $value
     *
     * @return string
     */
    public function convertToDatabaseValue($value): string
    {
        /** @var DateTime $value */
        if ($value === null) {
            return $value;
        }

        return $value->toDateTimeMicro();
    }

    /**
     * @param mixed $value
     *
     * @return null|DateTime
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?DateTime
    {
        try {
            if ($value === null) {
                return null;
            }

            return DateTime::createFromDateTimeMicroFormat($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * @return string
     */
    public function closureToMongo(): string
    {
        return 'if ($value === null || $value instanceof \MongoDate) { $return = $value; } ' .
            'else { $return $value->toDateTimeMicro(); }';
    }

    /**
     * @return string
     */
    public function closureToPHP(): string
    {
        return 'if ($value === null) { $return = null; } ' .
            'else { $return = \WalletAccountant\Common\DateTime\DateTime::createFromDateTimeMicroFormat($value); }';
    }
}
