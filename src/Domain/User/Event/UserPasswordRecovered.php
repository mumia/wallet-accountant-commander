<?php

namespace WalletAccountant\Domain\User\Event;

use WalletAccountant\Domain\Common\AbstractAggregateChanged;

/**
 * UserPasswordRecovered
 */
final class UserPasswordRecovered extends AbstractAggregateChanged
{
    private const PASSWORD = 'password';

    /**
     * @param string $id
     * @param string $password
     */
    public function __construct(string $id, string $password)
    {
        parent::__construct($id, [self::PASSWORD => $password]);
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
    public function password(): string
    {
        return $this->payload()[self::PASSWORD];
    }
}
