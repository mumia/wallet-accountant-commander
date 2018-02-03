<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use InvalidArgumentException as StandardInvalidArgumentException ;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\UserProjectionRepositoryInterface;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Common\Exceptions\User\UserNotFoundException;

/**
 * UserProjectionRepository
 */
final class UserProjectionRepository extends AbstractProjectionRepository implements UserProjectionRepositoryInterface
{
    protected const COLLECTION_NAME = 'user';

    /**
     * {@inheritdoc}
     */
    public function persist(User $newDocument, ?User $oldDocument): void
    {
        try {
            $manager = $this->client->getManager();
            $manager->persist($newDocument);
            $manager->flush();

            if ($oldDocument instanceof User) {
                $manager->refresh($oldDocument);
            }
        } catch (StandardInvalidArgumentException $exception) {
            throw InvalidArgumentException::createFromStandardException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function emailExists(string $email): bool
    {
        return $this->getByEmailOrNull($email) instanceof User;
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmailOrNull(string $email): ?User
    {
        $repository = $this->client->getRepository(User::class);

        return $repository->find($email);
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmail(string $email): User
    {
        $user = $this->getByEmailOrNull($email);

        if (!$user instanceof User) {
            throw UserNotFoundException::withEmail($email);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getByAggregateIdOrNull(string $aggregateId): ?User
    {
        $repository = $this->client->getRepository(User::class);

        return $repository->findOneBy(['aggregate_id' => $aggregateId]);
    }

    /**
     * {@inheritdoc}
     */
    public function getByAggregateId(string $aggregateId): User
    {
        $user = $this->getByAggregateIdOrNull($aggregateId);

        if (!$user instanceof User) {
            throw UserNotFoundException::withId($aggregateId);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getByPasswordRecoveryCode(string $passwordRecoveryCode): User
    {
        $repository = $this->client->getRepository(User::class);
        $user = $repository->findOneBy(['recovery.code' => $passwordRecoveryCode]);

        if (!$user instanceof User) {
            throw UserNotFoundException::withPasswordRecoveryCode($passwordRecoveryCode);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function collectionName(): string
    {
        return self::COLLECTION_NAME;
    }
}
