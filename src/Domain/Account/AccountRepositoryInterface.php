<?php

namespace WalletAccountant\Domain\Account;

use WalletAccountant\Common\Exceptions\Account\AccountAggregateNotFoundException;
use WalletAccountant\Domain\Account\Id\AccountId;

/**
 * AccountRepositoryInterface
 */
interface AccountRepositoryInterface
{
    /**
     * @param Account $account
     */
    public function save(Account $account): void;

    /**
     * @param AccountId $accountId
     *
     * @return null|Account
     */
    public function getOrNull(AccountId $accountId): ?Account;

    /**
     * @param AccountId $accountId
     *
     * @return Account
     *
     * @throws AccountAggregateNotFoundException
     */
    public function get(AccountId $accountId): Account;
}
