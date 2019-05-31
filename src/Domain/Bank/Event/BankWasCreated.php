<?php

namespace WalletAccountant\Domain\Bank\Event;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Bank\Name\Name;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;

/**
 * BankWasCreated
 */
final class BankWasCreated extends AbstractAggregateChanged
{
    private const NAME = 'name';

    /**
     * @param BankId $id
     * @param string $name
     */
    public function __construct(BankId $id, Name $name)
    {
        parent::__construct($id->toString(), [self::NAME => $name->value()]);
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
     * @return Name
     */
    public function name(): Name
    {
        return new Name($this->payload()[self::NAME]);
    }
}
