<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use InvalidArgumentException as StandardInvalidArgumentException ;
use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;

/**
 * Class BankProjectionRepository
 * @package WalletAccountant\Infrastructure\MongoDB
 */
final class BankProjectionRepository extends AbstractProjectionRepository implements BankProjectionRepositoryInterface
{
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

        /** @var null|Bank $bank */
        $bank = $repository->findOneBy(['aggregate_id' => $aggregateId]);

        return $bank;
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
