<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\User\Email\Email;

/**
 * EmailType
 */
class EmailType extends AbstractStringableType
{
    /**
     * @param mixed $value
     *
     * @return null|Email
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?Email
    {
        try {
            if ($value === null) {
                return null;
            }

            return Email::createFromString($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return Email::class;
    }
}
