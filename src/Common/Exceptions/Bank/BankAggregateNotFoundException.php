<?php

namespace WalletAccountant\Common\Exceptions\Bank;

use LogicException;
use function sprintf;

/**
 * UserAggregateNotFoundException
 */
class BankAggregateNotFoundException extends LogicException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct(sprintf('bank aggregate with %s was not found', $message), 0, null);
    }

    /**
     * @param string $userId
     *
     * @return BankAggregateNotFoundException
     */
    public static function withId(string $bankId): self
    {
        return new self(sprintf('id "%s"', $bankId));
    }
}
