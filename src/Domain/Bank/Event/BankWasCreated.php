<?php

namespace WalletAccountant\Domain\Bank\Event;

use WalletAccountant\Domain\Common\AbstractAggregateChanged;

/**
 * BankWasCreated
 */
class BankWasCreated extends AbstractAggregateChanged
{
    private const NAME = 'name';

    /**
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        parent::__construct($id, [self::NAME => $name]);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->aggregateId();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->payload()[self::NAME];
    }
}
