<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\Account\Id\AccountId;

/**
 * AccountIdType
 */
class AccountIdType extends AbstractStringableType
{
    /**
     * @param mixed $value
     *
     * @return null|AccountId
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?AccountId
    {
        try {
            if ($value === null) {
                return null;
            }

            return AccountId::createFromString($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return AccountId::class;
    }
}
