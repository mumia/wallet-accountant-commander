<?php

namespace WalletAccountant\Domain\Bank;

use WalletAccountant\Common\Exceptions\Bank\BankAggregateNotFoundException;
use WalletAccountant\Domain\Bank\Id\BankId;

/**
 * Interface BankRepositoryInterface
 * @package WalletAccountant\Domain\Bank
 */
interface BankRepositoryInterface
{
    /**
     * @param Bank $bank
     */
    public function save(Bank $bank): void;

    /**
     * @param BankId $bankId
     *
     * @return null|Bank
     */
    public function getOrNull(BankId $bankId): ?Bank;

    /**
     * @param BankId $bankId
     *
     * @return Bank
     *
     * @throws BankAggregateNotFoundException
     */
    public function get(BankId $bankId): Bank;
}
