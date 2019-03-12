<?php

namespace WalletAccountant\Domain\Account\Command;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Common\AbstractCommand;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * UpdateAccountOwner
 */
class UpdateAccountOwner extends AbstractCommand
{
    public const ID = 'id';
    public const OWNER_ID = 'owner_id';

    /**
     * @return AccountId
     *
     * @throws InvalidArgumentException
     */
    public function accountId(): AccountId
    {
        return AccountId::createFromString($this->payload()[self::ID]);
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
