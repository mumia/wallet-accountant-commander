<?php

namespace WalletAccountant\Domain\Bank\Name;

use function get_class;
use WalletAccountant\Domain\Common\ValueObjectInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use Respect\Validation\Validator;

/**
 * Name
 */
class Name implements ValueObjectInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (!Validator::stringType()->notEmpty()->validate($value)) {
            throw new InvalidArgumentException(sprintf('Invalid name "%s" found', $value));
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof self
            && get_class($this) === get_class($that)
            && $this->value() === $that->value();
    }
}
