<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\Account\Iban\Iban;

/**
 * IbanType
 */
class IbanType extends AbstractStringableType
{
    /**
     * @param mixed $value
     *
     * @return null|Iban
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?Iban
    {
        try {
            if ($value === null) {
                return null;
            }

            return Iban::createFromString($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return Iban::class;
    }
}
