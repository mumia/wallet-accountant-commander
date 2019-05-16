<?php

namespace WalletAccountant\Domain\Bank\Command;

use WalletAccountant\Domain\Bank\Id\BankId;
use WalletAccountant\Domain\Common\Command;
use WalletAccountant\Domain\Bank\Name\Name;

final class CreateBank extends Command
{
    private const ID = 'id';
    public const NAME = 'name';

    /**
     * @return BankId
     */
    public function id(): BankId
    {
        return BankId::createFromString($this->payload()[self::ID]);
    }

    /**
     * @return Name
     */
    public function name(): Name
    {
        $payload = $this->payload();

        return new Name($payload[self::NAME]);
    }
}
