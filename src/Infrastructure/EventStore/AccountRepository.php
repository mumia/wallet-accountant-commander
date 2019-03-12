<?php

namespace WalletAccountant\Infrastructure\EventStore;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use WalletAccountant\Common\Exceptions\Account\AccountAggregateNotFoundException;
use WalletAccountant\Common\Exceptions\Bank\BankAggregateNotFoundException;
use WalletAccountant\Domain\Account\Account;
use WalletAccountant\Domain\Account\AccountRepositoryInterface;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Bank;
use WalletAccountant\Domain\Bank\BankRepositoryInterface;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * AccountRepository
 */
final class AccountRepository extends AggregateRepository implements AccountRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(Account $account): void
    {
        $this->saveAggregateRoot($account);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrNull(AccountId $accountId): ?Account
    {
        /** @var Account $account */
        $account = $this->getAggregateRoot($accountId->toString());

        return $account;
    }

    /**
     * {@inheritdoc}
     */
    public function get(AccountId $accountId): Account
    {
        $account = $this->getOrNull($accountId);

        if (!$account instanceof Account) {
            throw AccountAggregateNotFoundException::withId($accountId);
        }

        return $account;
    }
}
