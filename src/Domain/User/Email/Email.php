<?php

namespace WalletAccountant\Domain\User\Email;

use function get_class;
use WalletAccountant\Domain\Common\ValueObjectInterface;
use WalletAccountant\Exceptions\InvalidArgumentException;
use Respect\Validation\Validator;

/**
 * Email
 */
class Email implements ValueObjectInterface
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
            throw new InvalidArgumentException(sprintf('Invalid email (%s) found', $email));
        }

        $this->email = $email;
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
