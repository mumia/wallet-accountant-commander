<?php

namespace WalletAccountant\Domain\Bank;

use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Bank as BankDocument;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * BankProjectionRepositoryInterface
 */
interface BankProjectionRepositoryInterface
{
    /**
     * @param BankDocument      $newDocument
     * @param null|BankDocument $oldDocument
     *
     * @throws InvalidArgumentException
     */
    public function persist(BankDocument $newDocument, ?BankDocument $oldDocument): void;

    /**
     * @param BankId $id
     *
     * @return null|BankDocument
     */
    public function getByIdOrNull(BankId $id): ?BankDocument;

    /**
     * @param BankId $id
     *
     * @return BankDocument
     *
     * @throws BankNotFoundException
     */
    public function getById(BankId $id): BankDocument;
}
