<?php

namespace WalletAccountant\Infrastructure\MongoDB\Types;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use InvalidArgumentException as StandardInvalidArgumentException;
use WalletAccountant\Domain\Common\CurrencyCode;
use WalletAccountant\Domain\User\Email\Email;

/**
 * CurrencyCodeType
 */
class CurrencyCodeType extends AbstractStringableType
{
    /**
     * @param mixed $value
     *
     * @return null|CurrencyCode
     *
     * @throws InvalidArgumentException
     */
    public function convertToPHPValue($value): ?CurrencyCode
    {
        try {
            if ($value === null) {
                return null;
            }

            return CurrencyCode::createFromString($value);
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClass(): string
    {
        return CurrencyCode::class;
    }
}
