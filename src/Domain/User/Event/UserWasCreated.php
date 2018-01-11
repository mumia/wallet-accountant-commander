<?php

namespace WalletAccountant\Domain\User\Event;

use Prooph\EventSourcing\AggregateChanged;
use function sprintf;

/**
 * UserWasCreated
 */
final class UserWasCreated extends AggregateChanged
{
    const EMAIL = 'email';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';

    /**
     * @param string $id
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(
        string $id,
        string $email,
        string $firstName,
        string $lastName
    ) {
        parent::__construct(
            $id,
            [
                self::EMAIL => $email,
                self::FIRST_NAME => $firstName,
                self::LAST_NAME => $lastName
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
    public function name(): string
    {
        return sprintf("%s %s", $this->firstName(), $this->lastName());
    }
}
