<?php

namespace WalletAccountant\Projection\Bank;

use Prooph\EventStore\Projection\AbstractReadModel;
use function var_dump;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Document\User;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Infrastructure\MongoDB\BankProjectionRepository;
use WalletAccountant\Infrastructure\MongoDB\DroppableRepositoryInterface;
use WalletAccountant\Infrastructure\MongoDB\UserProjectionRepository;
use WalletAccountant\Projection\AbstractMongoDBReadModel;

/**
 * BankReadModel
 */
final class BankReadModel extends AbstractMongoDBReadModel
{
    /**
     * @var BankProjectionRepository
     */
    private $bankProjectionRepository;

    /**
     * @param BankProjectionRepository $bankProjectionRepository
     */
    public function __construct(BankProjectionRepository $bankProjectionRepository)
    {
        $this->bankProjectionRepository = $bankProjectionRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): DroppableRepositoryInterface
    {
        return $this->bankProjectionRepository;
    }

    /**
     * @param Bank $bank
     *
     * @throws InvalidArgumentException
     */
    public function insert(Bank $bank): void
    {
        $this->bankProjectionRepository->persist($bank);
    }

    /**
     * @param string $aggregateId
     * @param string $name
     *
     * @throws InvalidArgumentException
     * @throws BankNotFoundException
     */
    public function update(string $aggregateId, string $name): void
    {
        $bank = $this->bankProjectionRepository->getByAggregateId($aggregateId);

        $this->bankProjectionRepository->persist(new Bank($bank->getAggregateId(), $name));
    }
}
