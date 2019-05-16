<?php

namespace WalletAccountant\Domain\User\Command;

use WalletAccountant\Domain\Common\Command;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * CreateUser
 */
final class CreateUser extends Command
{
    private const ID = 'id';
    private const EMAIL = 'email';
    private const FIRST_NAME = 'first_name';
    private const LAST_NAME = 'last_name';

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
