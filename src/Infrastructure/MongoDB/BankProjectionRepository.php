<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use InvalidArgumentException as StandardInvalidArgumentException ;
use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;

/**
 * BankProjectionRepository
 */
final class BankProjectionRepository extends AbstractProjectionRepository implements BankProjectionRepositoryInterface
{
    protected const COLLECTION_NAME = 'bank';

    /**
     * {@inheritdoc}
     */
    public function persist(Bank $document): void
    {
        try {
            $manager = $this->client->getManager();
            $manager->persist($document);
            $manager->flush();
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByAggregateIdOrNull(string $aggregateId): ?Bank
    {
        $repository = $this->client->getRepository(Bank::class);

        return $repository->find($aggregateId);
    }

    /**
     * {@inheritdoc}
     */
    public function getByAggregateId(string $aggregateId): Bank
    {
        $bank = $this->getByAggregateIdOrNull($aggregateId);

        if (!$bank instanceof Bank) {
            throw BankNotFoundException::withId($aggregateId);
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
