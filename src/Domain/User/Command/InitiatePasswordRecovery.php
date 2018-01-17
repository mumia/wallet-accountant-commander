<?php

namespace WalletAccountant\Domain\User\Command;

use WalletAccountant\Domain\Common\Command;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * InitiatePasswordRecovery
 */
final class InitiatePasswordRecovery extends Command
{
    public const ID = 'id';

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function userId(): UserId
    {
        return UserId::createFromString($this->payload()[self::ID]);
    }
}
