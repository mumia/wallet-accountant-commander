<?php

namespace WalletAccountant\Domain\User;

use WalletAccountant\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\User as UserDocument;

/**
 * UserProjectionRepositoryInterface
 */
interface UserProjectionRepositoryInterface
{
    /**
     * @param UserDocument $document
     *
     * @throws InvalidArgumentException
     */
    public function persist(UserDocument $document): void;

    /**
     * @param string $email
     *
     * @return bool
     */
    public function emailExists(string $email): bool;
}
