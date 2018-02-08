<?php

namespace WalletAccountant\Domain\Bank\Event;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;

/**
 * BankWasCreated
 */
class BankWasCreated extends AbstractAggregateChanged
{
    private const NAME = 'name';

    /**
     * @param BankId $id
     * @param string $name
     */
    public function __construct(BankId $id, string $name)
    {
        parent::__construct($id->toString(), [self::NAME => $name]);
    }

    /**
     * @return BankId
     *
     * @throws InvalidArgumentException
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
