<?php

namespace WalletAccountant\Domain\Common;

use function get_class;
use Ramsey\Uuid\Uuid;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Stringable\StringableInterface;

/**
 * Id
 */
abstract class Id implements ValueObjectInterface, StringableInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @param string $id
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $id)
    {
        if (!Uuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('Id (%s) is not a valid UUID', __CLASS__));
        }

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->id;
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
        return $that instanceof Id
            && get_class($this) === get_class($that)
            && $this->toString() === $that->toString();
    }
}
