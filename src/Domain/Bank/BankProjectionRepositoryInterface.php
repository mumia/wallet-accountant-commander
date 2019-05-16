<?php

namespace WalletAccountant\Domain\Bank;

use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Bank as BankDocument;

/**
 * Interface BankProjectionRepositoryInterface
 * @package WalletAccountant\Domain\Bank
 */
interface BankProjectionRepositoryInterface
{
    public const COLLECTION_NAME = 'bank';

    /**
     * @param BankDocument $document
     *
     * @throws InvalidArgumentException
     */
    public function persist(BankDocument $document): void;

    /**
     * @param string $aggregateId
     *
     * @return null|BankDocument
     */
    public function getByAggregateIdOrNull(string $aggregateId): ?BankDocument;

    /**
     * @param string $aggregateId
     *
     * @return BankDocument
     *
     * @throws BankNotFoundException
     */
    public function getByAggregateId(string $aggregateId): BankDocument;
}
