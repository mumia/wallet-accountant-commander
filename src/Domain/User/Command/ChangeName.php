<?php

namespace WalletAccountant\Domain\User\Command;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Common\AbstractCommand;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;

/**
 * ChangeName
 */
final class ChangeName extends AbstractCommand
{
    public const ID = 'id';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function id(): UserId
    {
        return UserId::createFromString($this->payload()[self::ID]);
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->payload()[self::FIRST_NAME];
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->payload()[self::LAST_NAME];
    }
}
