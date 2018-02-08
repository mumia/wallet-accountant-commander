<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * BankIdType
 */
class BankIdType extends AbstractStringableType
{
    /**
     * @param mixed $value
     *
     * @return null|BankId
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?BankId
    {
        try {
            if ($value === null) {
                return null;
            }

            return BankId::createFromString($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return BankId::class;
    }
}
