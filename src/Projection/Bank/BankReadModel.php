<?php

namespace WalletAccountant\Projection\Bank;

use Prooph\EventStore\Projection\AbstractReadModel;
use function var_dump;
use WalletAccountant\Common\DateTime\DateTime;
use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Document\Common\Authored;
use WalletAccountant\Document\User;
use WalletAccountant\Document\User\Recovery;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
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
     * @var BankProjectionRepositoryInterface
     */
    private $bankProjectionRepository;

    /**
     * @param BankProjectionRepositoryInterface $bankProjectionRepository
     *
     * @throws InvalidArgumentException
     */
    public function __construct(BankProjectionRepositoryInterface $bankProjectionRepository)
    {
        if (!$bankProjectionRepository instanceof DroppableRepositoryInterface) {
            throw New InvalidArgumentException(
                'Bank projection repository must implement the DroppableRepositoryInterface'
            );
        }

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
        $this->bankProjectionRepository->persist($bank, null);
    }

    /**
     * @param string   $aggregateId
     * @param string   $name
     * @param Authored $updated
     *
     * @throws InvalidArgumentException
     * @throws BankNotFoundException
     */
    public function update(string $aggregateId, string $name, Authored $updated): void
    {
        $oldBank = $this->bankProjectionRepository->getByAggregateId($aggregateId);

        $newBank = new Bank($oldBank->getAggregateId(), $name, $oldBank->getCreated(), $updated);

        $this->bankProjectionRepository->persist($newBank, $oldBank);
    }
}
