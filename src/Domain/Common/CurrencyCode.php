<?php

namespace WalletAccountant\Domain\Common;

use Respect\Validation\Validator;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * CurrencyCode
 */
class CurrencyCode implements ValueObjectInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $code)
    {
        if (!Validator::stringType()->notEmpty()->validate($code)) {
            throw new InvalidArgumentException('no currency code supplied');
        }

        if (!Validator::currencyCode()->validate($code)) {
            throw new InvalidArgumentException(sprintf('currency code "%s" is invalid', $code));
        }

        $this->code = $code;
    }

    /**
     * @param string $currencyCode
     *
     * @return CurrencyCode
     *
     * @throws InvalidArgumentException
     */
    public static function createFromString(string $currencyCode): CurrencyCode
    {
        return new self($currencyCode);
    }

    /**
     * @return CurrencyCode
     *
     * @throws InvalidArgumentException
     */
    public static function createEuro(): self
    {
        return new self('EUR');
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->getCode();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof self
            && $this->getCode() === $that->getCode();
    }
}
