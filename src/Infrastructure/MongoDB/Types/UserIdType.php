<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\Type;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\User\UserId;

/**
 * UserIdType
 */
class UserIdType extends Type
{
    /**
     * @param mixed $value
     *
     * @return string
     */
    public function convertToDatabaseValue($value): string
    {
        /** @var UserId $value */
        if ($value === null) {
            return $value;
        }

        return $value->toString();
    }

    /**
     * @param mixed $value
     *
     * @return null|UserId
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?UserId
    {
        try {
            if ($value === null) {
                return null;
            }

            return UserId::createFromString($value);
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
            'else { $return $value->toString(); }';
    }

    /**
     * @return string
     */
    public function closureToPHP(): string
    {
        return 'if ($value === null) { $return = null; } ' .
            'else { $return = \WalletAccountant\Document\User\UserId::createFromString($value); }';
    }
}
