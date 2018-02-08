<?php

namespace WalletAccountant\Domain\Account\Event;

use Prooph\EventSourcing\AggregateChanged;
use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * AccountOwnerWasUpdated
 */
class AccountOwnerWasUpdated extends AggregateChanged
{
    private const OWNER_ID = 'owner_id';

    /**
     * @param AccountId $id
     * @param UserId    $ownerId
     */
    public function __construct(AccountId $id, UserId $ownerId)
    {
        parent::__construct($id, [self::OWNER_ID => $ownerId->toString()]);
    }

    /**
     * @return AccountId
     *
     * @throws InvalidArgumentException
     */
    public function id(): AccountId
    {
        return AccountId::createFromString($this->aggregateId());
    }

    /**
     * @return UserId
     *
     * @throws InvalidArgumentException
     */
    public function ownerId(): UserId
    {
        return UserId::createFromString($this->payload()[self::OWNER_ID]);
    }
}
