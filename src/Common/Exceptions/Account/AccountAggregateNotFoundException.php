<?php

namespace WalletAccountant\Common\Exceptions\Account;

use LogicException;
use function sprintf;
use WalletAccountant\Domain\Account\Id\AccountId;

/**
 * AccountAggregateNotFoundException
 */
class AccountAggregateNotFoundException extends LogicException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct(sprintf('account aggregate with %s was not found', $message), 0, null);
    }

    /**
     * @param AccountId $accountId
     *
     * @return AccountAggregateNotFoundException
     */
    public static function withId(AccountId $accountId): self
    {
        return new self(sprintf('id "%s"', $accountId));
    }
}
