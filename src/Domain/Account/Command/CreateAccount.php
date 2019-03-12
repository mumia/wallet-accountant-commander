<?php

namespace WalletAccountant\Domain\Account\Command;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Account\Iban\Iban;
use WalletAccountant\Domain\Account\Id\AccountId;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\AbstractCommand;
use WalletAccountant\Domain\User\Id\UserId;

/**
 * CreateAccount
 */
class CreateAccount extends AbstractCommand
{
    public const ID = 'id';
    public const BANK_ID = 'bank_id';
    public const OWNER_ID = 'owner_id';
    public const IBAN = 'iban';

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
     * @return BankId
     *
     * @throws InvalidArgumentException
     */
    public function bankId(): BankId
    {
        return BankId::createFromString($this->payload()[self::BANK_ID]);
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

    /**
     * @return Iban
     *
     * @throws InvalidArgumentException
     */
    public function iban(): Iban
    {
        return Iban::createFromString($this->payload()[self::IBAN]);
    }
}
