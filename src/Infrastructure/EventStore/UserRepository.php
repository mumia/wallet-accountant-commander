<?php

namespace WalletAccountant\Infrastructure\EventStore;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\User;
use WalletAccountant\Domain\User\UserRepositoryInterface;

/**
 * UserProjectionRepository
 */
final class UserRepository extends AggregateRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(User $user): void
    {
        $this->saveAggregateRoot($user);
    }

    /**
     * {@inheritdoc}
     */
    public function get(UserId $userId): ?User
    {
        /** @var User $user */
        $user = $this->getAggregateRoot($userId->toString());

        return $user;
    }
}
