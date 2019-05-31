<?php

namespace WalletAccountant\Domain\Bank\Command;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\AbstractCommand;

/**
 * UpdateBank
 */
final class UpdateBank extends AbstractCommand
{
    public const ID = 'id';
    public const NAME = 'name';

    /**
     * @return BankId
     *
     * @throws InvalidArgumentException
     */
    public function bankId(): BankId
    {
        return BankId::createFromString($this->payload()[self::ID]);
    }

    /**
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function name(): string
    {
        return $this->payload()[self::NAME];
    }
}
