<?php

namespace WalletAccountant\Domain\Account\Ledger\Type;

use Respect\Validation\Validator;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Stringable\StringableInterface;
use WalletAccountant\Domain\Common\ValueObjectInterface;

/**
 * Type
 */
class Type implements ValueObjectInterface, StringableInterface
{
    private const DEBIT = 'debit';
    private const CREDIT = 'credit';

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $type)
    {
        if (!Validator::stringType()->notEmpty()->validate($type)) {
            throw new InvalidArgumentException('no movement type supplied');
        }

        if ($type !== self::DEBIT && $type !== self::CREDIT) {
            throw new InvalidArgumentException(sprintf('movement type "%s" is invalid', $type));
        }

        $this->type = $type;
    }

    /**
     * @param string $type
     *
     * @return Type
     *
     * @throws InvalidArgumentException
     */
    public static function createFromString(string $type): self
    {
        return new self($type);
    }

    /**
     * @return Type
     *
     * @throws InvalidArgumentException
     */
    public static function createDebit(): self
    {
        return new self(self::DEBIT);
    }

    /**
     * @return Type
     *
     * @throws InvalidArgumentException
     */
    public static function createCredit(): self
    {
        return new self(self::CREDIT);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isDebit(): bool
    {
        return $this->getType() === self::DEBIT;
    }

    /**
     * @return bool
     */
    public function isCredit(): bool
    {
        return $this->getType() === self::CREDIT;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->getType();
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
            && $this->getType() === $that->getType();
    }
}
