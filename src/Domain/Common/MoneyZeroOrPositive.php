<?php

namespace WalletAccountant\Domain\Common;

use Respect\Validation\Validator;
use function sprintf;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * Money
 */
class MoneyZeroOrPositive extends Money
{
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function __construct(int $amount, CurrencyCode $currency)
    {
        if (!Validator::intVal()->min(0)->validate($amount)) {
            throw new InvalidArgumentException(sprintf('invalid amount value "%d"', $amount));
        }

        parent::__construct($amount, $currency);
    }

    /**
     * @param int    $amount
     * @param string $currencyCode
     *
     * @return MoneyZeroOrPositive
     *
     * @throws InvalidArgumentException
     */
    public static function createFromAmountCurrency(int $amount, string $currencyCode): self
    {
        return new self($amount, CurrencyCode::createFromString($currencyCode));
    }
}
