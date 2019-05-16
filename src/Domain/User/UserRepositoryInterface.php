<?php

namespace WalletAccountant\Domain\User;

use WalletAccountant\Common\Exceptions\User\UserAggregateNotFoundException;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * UserRepositoryInterface
 */
interface UserRepositoryInterface
{
    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param UserId $userId
     *
     * @return null|User
     */
    public function getOrNull(UserId $userId): ?User;

    /**
     * @param UserId $userId
     *
     * @return User
     *
     * @throws UserAggregateNotFoundException
     */
    public function get(UserId $userId): User;
}
