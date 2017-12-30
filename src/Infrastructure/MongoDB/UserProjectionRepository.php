<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;

/**
 * UserProjectionRepository
 */
final class UserProjectionRepository extends AbstractProjectionRepository implements UserProjectionRepositoryInterface
{
    protected const COLLECTION_NAME = 'user';

    /**
     * {@inheritdoc}
     */
    public function persist(User $document): void
    {
        $manager = $this->client->getManager();
        $manager->persist($document);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function emailExists(string $email): bool
    {
        $repository = $this->client->getRepository(User::class);

        return $repository->find($email) instanceof User;
    }

    /**
     * {@inheritdoc}
     */
    public function collectionName(): string
    {
        return self::COLLECTION_NAME;
    }
}
