<?php

namespace WalletAccountant\Domain\Account\Ledger;

/**
 * Ledger
 */
class Ledger
{
    /**
     * @var array
     */
    private $movements;

    /**
     * @return array
     */
    public function getMovements(): array
    {
        return $this->movements;
    }

    /**
     * @param Movement $movement
     */
    public function append(Movement $movement): void
    {
        $this->movements[] = $movement;
    }
}
