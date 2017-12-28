<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use WalletAccountant\Document\User;
use InvalidArgumentException;

/**
 * UserRepository
 */
final class UserRepository extends AbstractMongoDBRepository
{
    const COLLECTION_NAME = 'user';

    /**
     * @param User $document
     *
     * @throws InvalidArgumentException
     */
    public function persist(User $document): void
    {
        $manager = $this->client->getManager();
        $manager->persist($document);
        $manager->flush();
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        $repository = $this->client->getRepository(User::class);
        var_dump($repository->find($email));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function collectionName(): string
    {
        return self::COLLECTION_NAME;
    }
}
