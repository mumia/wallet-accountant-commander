<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use InvalidArgumentException as StandardInvalidArgumentException ;
use WalletAccountant\Common\Exceptions\Account\AccountNotFoundException;
use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Document\Account;
use WalletAccountant\Domain\Account\AccountProjectionRepositoryInterface;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * AccountProjectionRepository
 */
final class AccountProjectionRepository extends AbstractProjectionRepository implements AccountProjectionRepositoryInterface
{
    protected const COLLECTION_NAME = 'account';

    /**
     * {@inheritdoc}
     */
    public function persist(Account $newDocument, ?Account $oldDocument): void
    {
        try {
            $manager = $this->client->getManager();
            $manager->persist($newDocument);
            $manager->flush();

            if ($oldDocument instanceof Account) {
                $manager->refresh($oldDocument);
            }

        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByIdOrNull(AccountId $id): ?Account
    {
        $repository = $this->client->getRepository(Account::class);

        return $repository->find($id->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function getById(AccountId $aggregateId): Account
    {
        $bank = $this->getByIdOrNull($aggregateId);

        if (!$bank instanceof Account) {
            throw AccountNotFoundException::withId($aggregateId);
        }

        return $bank;
    }

    /**
     * {@inheritdoc}
     */
    public function collectionName(): string
    {
        return self::COLLECTION_NAME;
    }
}
