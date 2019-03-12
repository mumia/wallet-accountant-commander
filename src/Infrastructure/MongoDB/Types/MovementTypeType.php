<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\Account\Ledger\Type\Type;

/**
 * MovementTypeType
 */
class MovementTypeType extends AbstractStringableType
{
    /**
     * @param mixed $value
     *
     * @return null|Type
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?Type
    {
        try {
            if ($value === null) {
                return null;
            }

            return Type::createFromString($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return Type::class;
    }
}
