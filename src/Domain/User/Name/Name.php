<?php

namespace WalletAccountant\Domain\User\Name;

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
    protected $first;

    /**
     * @var string
     */
    protected $last;

    /**
     * @param string $first
     * @param string $last
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $first, string $last)
    {
        if (!Validator::stringType()->notEmpty()->validate($first)) {
            throw new InvalidArgumentException(sprintf('Invalid first name "%s" found', $first));
        }

        if (!Validator::stringType()->notEmpty()->validate($last)) {
            throw new InvalidArgumentException(sprintf('Invalid last name "%s" found', $last));
        }

        $this->first = $first;
        $this->last = $last;
    }

    /**
     * @return string
     */
    public function first(): string
    {
        return $this->first;
    }

    /**
     * @return string
     */
    public function last(): string
    {
        return $this->last;
    }

    /**
     * {@inheritdoc}
     */
    public function sameValueAs(ValueObjectInterface $that): bool
    {
        return $that instanceof Name
            && get_class($this) === get_class($that)
            && $this->first() === $that->first()
            && $this->last() === $that->last();
    }
}
