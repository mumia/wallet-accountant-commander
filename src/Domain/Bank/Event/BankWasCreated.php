<?php

namespace WalletAccountant\Domain\Bank\Event;

use Prooph\EventSourcing\AggregateChanged;

class BankWasCreated extends AggregateChanged
{
    private const NAME = 'name';
    private const OWNER_ID = 'owner_id';

    /**
     * BankWasCreated constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        parent::__construct(
            $id,
            [
                self::NAME => $name
            ]
        );
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
