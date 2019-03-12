<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use InvalidArgumentException as StandardInvalidArgumentException ;
use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Document\Bank;
use WalletAccountant\Domain\Bank\BankProjectionRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * BankProjectionRepository
 */
final class BankProjectionRepository extends AbstractProjectionRepository implements BankProjectionRepositoryInterface
{
    protected const COLLECTION_NAME = 'bank';

    /**
     * {@inheritdoc}
     */
    public function persist(Bank $newDocument, ?Bank $oldDocument): void
    {
        try {
            $manager = $this->client->getManager();
            $manager->persist($newDocument);
            $manager->flush();

            if ($oldDocument instanceof Bank) {
                $manager->refresh($oldDocument);
            }

        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByIdOrNull(BankId $id): ?Bank
    {
        $repository = $this->client->getRepository(Bank::class);

        return $repository->find($id->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function getById(BankId $id): Bank
    {
        $bank = $this->getByIdOrNull($id);

        if (!$bank instanceof Bank) {
            throw BankNotFoundException::withId($id);
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
