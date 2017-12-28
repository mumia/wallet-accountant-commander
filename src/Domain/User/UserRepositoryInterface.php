<?php

namespace WalletAccountant\Domain\User;

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
    public function get(UserId $userId): ?User;
}
