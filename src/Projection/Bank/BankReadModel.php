<?php

namespace WalletAccountant\Projection\Bank;

use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Document\Common\Authored;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Infrastructure\MongoDB\DroppableRepositoryInterface;
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
     * @param BankId   $id
     * @param string   $name
     * @param Authored $updated
     *
     * @throws InvalidArgumentException
     * @throws BankNotFoundException
     */
    public function update(BankId $id, string $name, Authored $updated): void
    {
        $oldBank = $this->bankProjectionRepository->getById($id);

        $newBank = new Bank($oldBank->id(), $name, $oldBank->created(), $updated);

        $this->bankProjectionRepository->persist($newBank, $oldBank);
    }
}
