<?php

namespace WalletAccountant\Domain\User\Event;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * UserPasswordRecovered
 */
final class UserPasswordRecovered extends AbstractAggregateChanged
{
    private const PASSWORD = 'password';

    /**
     * @param UserId $id
     * @param string $password
     */
    public function __construct(UserId $id, string $password)
    {
        parent::__construct($id->toString(), [self::PASSWORD => $password]);
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function id(): UserId
    {
        return UserId::createFromString($this->aggregateId());
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->payload()[self::PASSWORD];
    }
}
