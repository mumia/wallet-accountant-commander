<?php

namespace WalletAccountant\Domain\User\Event;

use function sprintf;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;
use WalletAccountant\Domain\User\Status\Status;

/**
 * UserWasCreated
 */
final class UserWasCreated extends AbstractAggregateChanged
{
    private const EMAIL = 'email';
    private const FIRST_NAME = 'first_name';
    private const LAST_NAME = 'last_name';
    private const PASSWORD = 'password';
    private const SALT = 'salt';
    private const ROLES = 'roles';
    private const STATUS = 'status';
    private const ACCOUNT_EXPIRED = 'account_expired';
    private const ACCOUNT_LOCKED = 'account_locked';
    private const CREDENTIALS_EXPIRED = 'credentials_expired';
    private const ENABLED = 'enabled';

    /**
     * @param string $id
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @param string $salt
     * @param array  $roles
     * @param Status $status
     */
    public function __construct(
        string $id,
        string $email,
        string $firstName,
        string $lastName,
        string $password,
        string $salt,
        array $roles,
        Status $status
    ) {
        parent::__construct(
            $id,
            [
                self::EMAIL => $email,
                self::FIRST_NAME => $firstName,
                self::LAST_NAME => $lastName,
                self::PASSWORD => $password,
                self::SALT => $salt,
                self::ROLES => $roles,
                self::STATUS => [
                    self::ACCOUNT_EXPIRED => $status->isAccountExpired(),
                    self::ACCOUNT_LOCKED => $status->isAccountLocked(),
                    self::CREDENTIALS_EXPIRED => $status->isCredentialsExpired(),
                    self::ENABLED => $status->isEnabled()
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->aggregateId();
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->payload()[self::EMAIL];
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


    /**
     * @return string
     */
    public function password(): string
    {
        return $this->payload()[self::PASSWORD];
    }


    /**
     * @return string
     */
    public function salt(): string
    {
        return $this->payload()[self::SALT];
    }


    /**
     * @return array
     */
    public function roles(): array
    {
        return $this->payload()[self::ROLES];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return sprintf('%s %s', $this->firstName(), $this->lastName());
    }

    /**
     * @return Status
     */
    public function status(): Status
    {
        return new Status(
            $this->payload()[self::STATUS][self::ACCOUNT_EXPIRED],
            $this->payload()[self::STATUS][self::ACCOUNT_LOCKED],
            $this->payload()[self::STATUS][self::CREDENTIALS_EXPIRED],
            $this->payload()[self::STATUS][self::ENABLED]
        );
    }
}
