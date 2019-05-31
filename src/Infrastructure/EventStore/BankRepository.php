<?php

namespace WalletAccountant\Infrastructure\EventStore;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use WalletAccountant\Common\Exceptions\Bank\BankAggregateNotFoundException;
use WalletAccountant\Domain\Bank\Bank;
use WalletAccountant\Domain\Bank\BankRepositoryInterface;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * Class BankRepository
 * @package WalletAccountant\Infrastructure\EventStore
 */
final class BankRepository extends AggregateRepository implements BankRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(Bank $bank): void
    {
        $this->saveAggregateRoot($bank);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrNull(BankId $bankId): ?Bank
    {
        /** @var Bank $bank */
        $bank = $this->getAggregateRoot($bankId->toString());

        return $bank;
    }

    /**
     * {@inheritdoc}
     */
    public function get(BankId $bankId): Bank
    {
        $bank = $this->getOrNull($bankId);

        if (!$bank instanceof Bank) {
            throw BankAggregateNotFoundException::withId($bankId);
        }

        return $bank;
    }
}
