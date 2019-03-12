<?php

namespace WalletAccountant\Domain\User\Event;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Common\AbstractAggregateChanged;
use WalletAccountant\Domain\User\Id\UserId;
use WalletAccountant\Domain\User\Name\Name;

/**
 * UserNameChanged
 */
class UserNameChanged extends AbstractAggregateChanged
{
    private const FIRST_NAME = 'first_name';
    private const LAST_NAME = 'last_name';

    /**
     * @param UserId $id
     * @param Name   $name
     */
    public function __construct(UserId $id, Name $name)
    {
        parent::__construct($id->toString(), [self::FIRST_NAME => $name->first(), self::LAST_NAME => $name->last()]);
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function id(): UserId
    {
        return UserId::createFromString($this->aggregateId());
    }

    /**
     * @return Name
     *
     * @throws InvalidArgumentException
     */
    public function name(): Name
    {
        return new Name($this->payload()[self::FIRST_NAME], $this->payload()[self::LAST_NAME]);
    }
}
