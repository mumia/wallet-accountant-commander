<?php

namespace WalletAccountant\Domain\Account\Iban;

use function get_class;
use IsoCodes\Iban as IbanValidator;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Common\ValueObjectInterface;

/**
 * Iban
 */
class Iban implements ValueObjectInterface
{
    /**
     * @var string
     */
    protected $iban;

    /**
     * @param string $iban
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $iban)
    {
        if (!IbanValidator::validate($iban)) {
            throw new InvalidArgumentException(sprintf('IBAN "%s" is invalid', $iban));
        }

        $this->iban = $iban;
    }

    /**
     * @param string $iban
     *
     * @return Iban
     */
    public static function createFromString(string $iban): self
    {
        return new self($iban);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->iban;
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
            && get_class($this) === get_class($that)
            && $this->toString() === $that->toString();
    }
}
