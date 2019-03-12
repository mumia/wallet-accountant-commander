<?php

namespace WalletAccountant\Domain\Bank\Event;

use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;

/**
 * BankWasUpdated
 */
class BankWasUpdated extends AbstractAggregateChanged
{
    private const NAME = 'name';

    /**
     * @param BankId $id
     * @param string $name
     */
    public function __construct(BankId $id, string $name)
    {
        parent::__construct($id, [self::NAME => $name]);
    }

    /**
     * @return BankId
     */
    public function id(): BankId
    {
        return BankId::createFromString($this->aggregateId());
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->payload()[self::NAME];
    }
}
