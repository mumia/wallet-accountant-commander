<?php

namespace WalletAccountant\Common\Exceptions\Bank;

use LogicException;
use function sprintf;

/**
 * BankAggregateNotFoundException
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
     * @param string $bankId
     *
     * @return BankAggregateNotFoundException
     */
    public static function withId(string $bankId): self
    {
        return new self(sprintf('id "%s"', $bankId));
    }
}
