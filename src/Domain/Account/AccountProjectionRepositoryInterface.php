<?php

namespace WalletAccountant\Domain\Account;

use WalletAccountant\Common\Exceptions\Account\AccountNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Account;
use WalletAccountant\Domain\Account\Id\AccountId;

/**
 * AccountProjectionRepositoryInterface
 */
interface AccountProjectionRepositoryInterface
{
    /**
     * @param Account      $newDocument
     * @param null|Account $oldDocument
     *
     * @throws InvalidArgumentException
     */
    public function persist(Account $newDocument, ?Account $oldDocument): void;

    /**
     * @param AccountId $id
     *
     * @return null|Account
     */
    public function getByIdOrNull(AccountId $id): ?Account;

    /**
     * @param AccountId $id
     *
     * @return Account
     *
     * @throws AccountNotFoundException
     */
    public function getById(AccountId $id): Account;
}
