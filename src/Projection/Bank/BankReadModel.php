<?php

namespace WalletAccountant\Projection\Bank;

use Prooph\EventStore\Projection\AbstractReadModel;
use WalletAccountant\Document\Bank;
use WalletAccountant\Infrastructure\MongoDB\BankProjectionRepository;

/**
 * Class BankReadModel
 * @package WalletAccountant\Projection\Bank
 */
final class BankReadModel extends AbstractReadModel
{
    /**
     * @var BankProjectionRepository
     */
    private $bankProjectionRepository;

    /**
     * BankReadModel constructor.
     * @param BankProjectionRepository $bankProjectionRepository
     */
    public function __construct(BankProjectionRepository $bankProjectionRepository)
    {
        $this->bankProjectionRepository = $bankProjectionRepository;
    }

    public function init(): void
    {
        // MongoDB collection will be created automatically
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        // MongoDB collection will be initialized automatically

        return true;
    }

    public function reset(): void
    {
        $this->bankProjectionRepository->dropCollection();
    }

    public function delete(): void
    {
        $this->bankProjectionRepository->dropCollection();
    }

    /**
     * @param Bank $bank
     */
    public function insert(Bank $bank): void
    {
        $this->bankProjectionRepository->persist($bank);
    }
}
