<?php

namespace WalletAccountant\Domain\User\Event;

use Prooph\EventSourcing\AggregateChanged;
use function sprintf;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;
use WalletAccountant\Domain\User\Status\Status;

/**
 * UserWasCreated
 */
final class UserWasCreated extends AggregateChanged
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
     * UserWasCreated constructor.
     * @param UserId $id
     * @param Email $email
     * @param Name $name
     * @param string $password
     * @param string $salt
     * @param array $roles
     * @param Status $status
     */
    public function __construct(
        UserId $id,
        Email $email,
        Name $name,
        string $password,
        string $salt,
        array $roles,
        Status $status
    ) {
        parent::__construct(
            $id,
            [
                self::EMAIL => $email->toString(),
                self::FIRST_NAME => $name->first(),
                self::LAST_NAME => $name->last(),
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
     * @return UserId
     */
    public function id(): UserId
    {
        return UserId::createFromString($this->aggregateId());
    }

    /**
     * @return Email
     */
    public function email(): Email
    {
        return Email::createFromString($this->payload()[self::EMAIL]);
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
     * @return Name
     */
    public function name(): Name
    {
        return new Name($this->firstName(), $this->lastName());
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
