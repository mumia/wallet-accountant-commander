<?php

namespace WalletAccountant\Domain\User\Command;

use WalletAccountant\Domain\Common\AbstractCommand;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * UserInitiatePasswordRecovery
 */
final class UserInitiatePasswordRecovery extends AbstractCommand
{
    public const EMAIL = 'email';

    /**
     * @return Email
     *
     * @throws InvalidArgumentException
     */
    public function email(): Email
    {
        return Email::createFromString($this->payload()[self::EMAIL]);
    }
}
