<?php

namespace WalletAccountant\Projection\Account;

use WalletAccountant\Common\Exceptions\Account\AccountNotFoundException;
use WalletAccountant\Document\Account;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\AccountProjectionRepositoryInterface;
use WalletAccountant\Domain\Account\Id\AccountId;
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

        $newAccount = new Account($oldAccount->getId(), $oldAccount->getBankId(), $ownerId, $oldAccount->getIban());

        $this->accountProjectionRepository->persist($newAccount, $oldAccount);
    }
}
