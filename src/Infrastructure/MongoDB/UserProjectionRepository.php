<?php

namespace WalletAccountant\Infrastructure\MongoDB;

use InvalidArgumentException as StandardInvalidArgumentException ;
use WalletAccountant\Document\User;
use WalletAccountant\Domain\User\Email\Email;
use WalletAccountant\Domain\User\Id\UserId;
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
    public function emailExists(Email $email): bool
    {
        return $this->getByEmailOrNull($email) instanceof User;
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmailOrNull(Email $email): ?User
    {
        $repository = $this->client->getRepository(User::class);

        return $repository->findOneBy(['email' => $email->toString()]);
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmail(Email $email): User
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
    public function getByIdOrNull(UserId $id): ?User
    {
        $repository = $this->client->getRepository(User::class);

        return $repository->find($id->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function getById(UserId $id): User
    {
        $user = $this->getByIdOrNull($id);

        if (!$user instanceof User) {
            throw UserNotFoundException::withId($id);
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
