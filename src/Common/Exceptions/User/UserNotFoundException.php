<?php

namespace WalletAccountant\Common\Exceptions\User;

use LogicException;
use function sprintf;

/**
 * UserNotFoundException
 */
class UserNotFoundException extends LogicException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct(sprintf('user with %s was not found', $message), 0, null);
    }

    /**
     * @param string $userId
     *
     * @return UserNotFoundException
     */
    public static function withId(string $userId): self
    {
        return new self(sprintf('id "%s"', $userId));
    }

    /**
     * @param string $email
     *
     * @return UserNotFoundException
     */
    public static function withEmail(string $email): self
    {
        return new self(sprintf('email "%s"', $email));
    }

    /**
     * @param string $passwordRecoveryCode
     *
     * @return UserNotFoundException
     */
    public static function withPasswordRecoveryCode(string $passwordRecoveryCode): self
    {
        return new self(sprintf('password recovery code "%s"', $passwordRecoveryCode));
    }
}
