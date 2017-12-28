<?php

namespace WalletAccountant\Domain\User\Command;

use WalletAccountant\Domain\Common\Command;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;
use WalletAccountant\Exceptions\InvalidArgumentException;

/**
 * CreateUser
 */
class CreateUser extends Command
{
    const ID = 'id';
    const EMAIL = 'email';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function userId(): UserId
    {
        return UserId::createFromString($this->payload()[self::ID]);
    }

    /**
     * @return Email
     *
     * @throws InvalidArgumentException
     */
    public function email(): Email
    {
        return new Email($this->payload()[self::EMAIL]);
    }

    /**
     * @return Name
     *
     * @throws InvalidArgumentException
     */
    public function name(): Name
    {
        $payload = $this->payload();

        return new Name($payload[self::FIRST_NAME], $payload[self::LAST_NAME]);
    }
}
