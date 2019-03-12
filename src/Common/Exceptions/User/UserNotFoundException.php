<?php

namespace WalletAccountant\Common\Exceptions\User;

use LogicException;
use function sprintf;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;

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
     * @param UserId $userId
     *
     * @return UserNotFoundException
     */
    public static function withId(UserId $userId): self
    {
        return new self(sprintf('id "%s"', $userId->toString()));
    }

    /**
     * @param Email $email
     *
     * @return UserNotFoundException
     */
    public static function withEmail(Email $email): self
    {
        return new self(sprintf('email "%s"', $email->toString()));
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
