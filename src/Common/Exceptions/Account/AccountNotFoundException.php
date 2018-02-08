<?php

namespace WalletAccountant\Common\Exceptions\Account;

use LogicException;
use function sprintf;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * AccountNotFoundException
 */
class AccountNotFoundException extends LogicException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct(sprintf('account with %s was not found', $message), 0, null);
    }

    /**
     * @param AccountId $accountId
     *
     * @return AccountNotFoundException
     */
    public static function withId(AccountId $accountId): self
    {
        return new self(sprintf('id "%s"', $accountId->toString()));
    }
}
