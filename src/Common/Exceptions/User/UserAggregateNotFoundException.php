<?php

namespace WalletAccountant\Common\Exceptions\User;

use LogicException;
use function sprintf;

/**
 * UserAggregateNotFoundException
 */
class UserAggregateNotFoundException extends LogicException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct(sprintf('user aggregate with %s was not found', $message), 0, null);
    }

    /**
     * @param string $userId
     *
     * @return UserAggregateNotFoundException
     */
    public static function withId(string $userId): self
    {
        return new self(sprintf('id "%s"', $userId));
    }
}
