<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\User\Id\UserId;
use InvalidArgumentException as StandardInvalidArgumentException;

/**
 * UserIdType
 */
class UserIdType extends AbstractStringableType
{
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
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return UserId::class;
    }
}
