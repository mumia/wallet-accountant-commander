<?php

namespace WalletAccountant\Domain\Common;

use Respect\Validation\Validator;
use function sprintf;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * Money
 */
class Money implements ValueObjectInterface
{
    /**
     * @var int
     */
    private $amount;

    /**
     * @var CurrencyCode
     */
    private $currency;

    /**
     * @param int          $amount
     * @param CurrencyCode $currency
     */
    public function __construct(int $amount, CurrencyCode $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return CurrencyCode
     */
    public function getCurrency(): CurrencyCode
    {
        return $this->currency;
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof self
            && $this->getAmount() === $that->getAmount()
            && $this->getCurrency()->sameValueAs($that->getCurrency());
    }
}
