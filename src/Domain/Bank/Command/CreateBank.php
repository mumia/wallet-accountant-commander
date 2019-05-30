<?php

namespace WalletAccountant\Domain\Bank\Command;

use WalletAccountant\Common\Exceptions\InvalidArgumentException;
use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Bank\Name\Name;
use WalletAccountant\Domain\Common\AbstractCommand;

/**
 * CreateBank
 */
final class CreateBank extends AbstractCommand
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
     * @return Name
     *
     * @throws InvalidArgumentException
     */
    public function name(): Name
    {
        $payload = $this->payload();

        return new Name($payload[self::NAME]);
    }
}
