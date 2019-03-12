<?php

namespace WalletAccountant\Domain\User\Email;

use function get_class;
use WalletAccountant\Common\Stringable\StringableInterface;
use WalletAccountant\Domain\Common\ValueObjectInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use Respect\Validation\Validator;

/**
 * Email
 */
class Email implements ValueObjectInterface, StringableInterface
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @param string $email
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $email)
    {
        if (!Validator::email()->validate($email)) {
            throw new InvalidArgumentException(sprintf('Invalid email "%s" found', $email));
        }

        $this->email = $email;
    }

    /**
     * @param string $email
     *
     * @return Email
     *
     * @throws InvalidArgumentException
     */
    public static function createFromString(string $email): self
    {
        return new self($email);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->email;
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
