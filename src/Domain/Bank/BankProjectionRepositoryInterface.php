<?php

namespace WalletAccountant\Domain\Bank;

use WalletAccountant\Common\Exceptions\Bank\BankNotFoundException;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Document\Bank;

/**
 * BankProjectionRepositoryInterface
 */
interface BankProjectionRepositoryInterface
{
    /**
     * @param Bank      $newDocument
     * @param null|Bank $oldDocument
     *
     * @throws InvalidArgumentException
     */
    public function persist(Bank $newDocument, ?Bank $oldDocument): void;

    /**
     * @param string $aggregateId
     *
     * @return null|Bank
     */
    public function getByAggregateIdOrNull(string $aggregateId): ?Bank;

    /**
     * @param string $aggregateId
     *
     * @return Bank
     *
     * @throws BankNotFoundException
     */
    public function getByAggregateId(string $aggregateId): Bank;
}
