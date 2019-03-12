<?php

namespace WalletAccountant\Projection\Account;

use WalletAccountant\Common\Exceptions\Account\AccountNotFoundException;
use WalletAccountant\Document\Account;
use WalletAccountant\Document\Account\Movement as MovementDocument;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\AccountProjectionRepositoryInterface;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Account\Ledger\Movement;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Infrastructure\MongoDB\DroppableRepositoryInterface;
use WalletAccountant\Projection\AbstractMongoDBReadModel;

/**
 * AccountReadModel
 */
final class AccountReadModel extends AbstractMongoDBReadModel
{
    /**
     * @var AccountProjectionRepositoryInterface
     */
    private $accountProjectionRepository;

    /**
     * @param AccountProjectionRepositoryInterface $accountProjectionRepository
     *
     * @throws InvalidArgumentException
     */
    public function __construct(AccountProjectionRepositoryInterface $accountProjectionRepository)
    {
        if (!$accountProjectionRepository instanceof DroppableRepositoryInterface) {
            throw New InvalidArgumentException(
                'Account projection repository must implement the DroppableRepositoryInterface'
            );
        }

        $this->accountProjectionRepository = $accountProjectionRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): DroppableRepositoryInterface
    {
        return $this->accountProjectionRepository;
    }

    /**
     * @param Account $account
     *
     * @throws InvalidArgumentException
     */
    public function insert(Account $account): void
    {
        $this->accountProjectionRepository->persist($account, null);
    }

    /**
     * @param AccountId $id
     * @param UserId    $ownerId
     *
     * @throws InvalidArgumentException
     * @throws AccountNotFoundException
     */
    public function updateOwner(AccountId $id, UserId $ownerId): void
    {
        $oldAccount = $this->accountProjectionRepository->getById($id);

        $newAccount = new Account(
            $oldAccount->getId(),
            $oldAccount->getBankId(),
            $ownerId,
            $oldAccount->getIban(),
            $oldAccount->getLedger()
        );

        $this->accountProjectionRepository->persist($newAccount, $oldAccount);
    }

    /**
     * @param AccountId $id
     * @param Movement  $movement
     *
     * @throws InvalidArgumentException
     * @throws AccountNotFoundException
     */
    public function addMovementToLedger(AccountId $id, Movement $movement): void
    {
        $oldAccount = $this->accountProjectionRepository->getById($id);

        $newAccount = new Account(
            $oldAccount->getId(),
            $oldAccount->getBankId(),
            $oldAccount->getOwnerId(),
            $oldAccount->getIban(),
            $oldAccount->getLedger()->addMovement(MovementDocument::createFromDomain($movement))
        );

        $this->accountProjectionRepository->persist($newAccount, $oldAccount);
    }
}
