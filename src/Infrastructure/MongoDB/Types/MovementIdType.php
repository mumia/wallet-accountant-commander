<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\Account\Ledger\Id\MovementId;

/**
 * MovementIdType
 */
class MovementIdType extends AbstractStringableType
{
    /**
     * @param mixed $value
     *
     * @return null|MovementId
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?MovementId
    {
        try {
            if ($value === null) {
                return null;
            }

            return MovementId::createFromString($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return MovementId::class;
    }
}
